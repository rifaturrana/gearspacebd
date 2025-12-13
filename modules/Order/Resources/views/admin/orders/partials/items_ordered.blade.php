<div class="items-ordered-wrapper">
    <h4 class="section-title">{{ trans('order::orders.items_ordered') }}</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="items-ordered">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ trans('order::orders.product') }}</th>
                                <th>{{ trans('order::orders.unit_price') }}</th>
                                <th>{{ trans('order::orders.quantity') }}</th>
                                <th>{{ trans('order::orders.line_total') }}</th>
                                @if (!in_array($order->status, [\Modules\Order\Entities\Order::COMPLETED, \Modules\Order\Entities\Order::CANCELED, \Modules\Order\Entities\Order::REFUNDED]))
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->products as $product)
                                <tr id="product-row-{{ $product->id }}">
                                    <td>
                                        @if ($product->trashed())
                                            {{ $product->name }}
                                        @else
                                            <a href="{{ route('admin.products.edit', $product->product->id) }}">{{ $product->name }}</a>
                                        @endif

                                        @if ($product->hasAnyVariation())
                                            <br>
                                            @foreach ($product->variations as $variation)
                                                <span>
                                                    {{ $variation->name }}:

                                                    <span>
                                                        {{ $variation->values()->first()?->label }}{{ $loop->last ? "" : "," }}
                                                    </span>
                                                </span>
                                            @endforeach
                                        @endif

                                        @if ($product->hasAnyOption())
                                            <br>
                                            @foreach ($product->options as $option)
                                                <span>
                                                    {{ $option->name }}:

                                                    <span>
                                                        @if ($option->option->isFieldType())
                                                            {{ $option->value }}
                                                        @else
                                                            {{ $option->values->implode('label', ', ') }}
                                                        @endif
                                                    </span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        {{ $product->unit_price->format() }}
                                    </td>

                                    <td>{{ $product->qty }}</td>

                                    <td class="line-total">
                                        {{ $product->line_total->format() }}
                                    </td>

                                    @if (!in_array($order->status, [\Modules\Order\Entities\Order::COMPLETED, \Modules\Order\Entities\Order::CANCELED, \Modules\Order\Entities\Order::REFUNDED]))
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-danger btn-xs delete-order-product" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-order-id="{{ $order->id }}">
                                                <i class="fa fa-trash"></i>
                                                {{ trans('admin::resource.delete', ['resource' => '']) }}
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-order-product').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const productId = button.data('product-id');
        const orderId = button.data('order-id');
        
        if (!confirm('Are you sure?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '{{ route("admin.orders.remove-product") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: orderId,
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    // Remove the product row with animation
                    $(`#product-row-${productId}`).fadeOut(400, function() {
                        $(this).remove();
                        
                        // If no products left, show empty state
                        if (response.data.remaining_products === 0) {
                            $('.items-ordered table tbody').html(`
                                <tr>
                                    <td colspan="5" class="text-center empty-state">
                                        {{ trans('order::messages.no_products_in_order') }}
                                    </td>
                                </tr>
                            `);
                            
                            // Update order status display
                            $('.order-status').text('{{ trans("order::statuses.canceled") }}');
                        }
                    });
                    
                    // Update order totals
                    if (response.data.order) {
                        $('.sub-total').text(response.data.order.sub_total);
                        $('.shipping-cost').text(response.data.order.shipping_cost);
                        $('.tax-amount').text(response.data.order.tax);
                        $('.discount').text(response.data.order.discount);
                        $('.total').text(response.data.order.total);
                    }
                    
                    // Show success message
                    alert('{{ trans("order::messages.product_removed") }}');
                }
            },
            error: function(xhr) {
                let message = '{{ trans("order::messages.error_removing_product") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fa fa-trash"></i> {{ trans('admin::resource.delete', ['resource' => '']) }}');
            }
        });
    });
});
</script>
@endpush