@extends('storefront::public.layout')

@section('title', trans('storefront::cart.cart'))

@section('content')
    <div x-data="Cart">
        <section class="cart-wrap">
            <div class="container">
                @if (!$isCartEmpty)
                    @include('storefront::public.cart.index.skeleton')

                    <template x-if="!cartIsEmpty">
                        <div>
                            @include('storefront::public.cart.index.steps')

                            <div class="cart">
                                <div class="cart-inner">
                                    @include('storefront::public.cart.index.cart_table')
                                    
                                    <div class="cart-actions mt-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{ route('products.index') }}" class="btn btn-primary update-cart-btn">
                                                    <i class="las la-arrow-left me-2"></i>
                                                    {{ trans('storefront::cart.continue_shopping') }}
                                                </a>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <button type="button" class="btn btn-primary update-cart-btn" onclick="updateCartFunction()">
                                                    <i class="las la-sync me-2"></i>
                                                    Update Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('storefront::public.cart.index.cart_summary')
                            </div>
                        </div>
                    </template>

                    <template x-cloak x-if="$store.cart.fetched && cartIsEmpty">
                        @include('storefront::public.cart.index.empty_cart')
                    </template>
                @else
                    @include('storefront::public.cart.index.empty_cart')
                @endif
            </div>
        </section>
        
        @if ($crossSellProducts->isNotEmpty())
            @include('storefront::public.partials.landscape_products', [
                'title' => trans('storefront::product.you_might_also_like'),
                'url' => '/cart/cross-sell-products',
                'watchState' => '$store.cart.isEmpty'
            ])
        @endif
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/cart/main.scss',  
        'modules/Storefront/Resources/assets/public/js/pages/cart/main.js',
    ])
@endpush

@push('scripts')
<script>
async function updateCartFunction() {
    const button = document.querySelector('.update-cart-btn');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    button.disabled = true;
    
    try {
        const { data } = await axios.get("/cart/get");
        // Access the cart store through Alpine
        Alpine.store('cart').updateCart(data);
        notify("Cart updated successfully");
    } catch (error) {
        console.error('Update cart error:', error);
        notify(error.response?.data?.message || "Something went wrong");
    } finally {
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}
</script>
@endpush
