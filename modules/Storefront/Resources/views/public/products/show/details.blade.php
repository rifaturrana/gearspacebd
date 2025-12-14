<div class="product-details-info position-relative flex-grow-1"> 
    <div class="details-info-top">
        <h1 class="product-name">{{ $product->name }}</h1>

        @if (setting('reviews_enabled'))
            @include('storefront::public.partials.product_rating')
        @endif

        <template x-cloak x-if="isInStock">
            <div>
                <template x-if="doesManageStock">
                    <div
                        class="availability in-stock"
                        x-text="trans('storefront::product.left_in_stock', { count: item.qty })"
                    >
                    </div>
                </template>
                
                <template x-if="!doesManageStock">
                    <div class="availability in-stock">
                        {{ trans('storefront::product.in_stock') }}
                    </div>
                </template>
            </div>
        </template>
        
        <template x-if="!isInStock">
            <div class="availability out-of-stock">
                {{ trans('storefront::product.out_of_stock') }}
            </div>
        </template>

        <div class="brief-description">
            {!! $product->short_description !!}
        </div>

        <div class="details-info-top-actions">
            <button
                class="btn btn-wishlist"
                :class="{ 'added': inWishlist }"
                @click="syncWishlist"
            >
                <template x-if="inWishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M16.44 3.1001C14.63 3.1001 13.01 3.9801 12 5.3301C10.99 3.9801 9.37 3.1001 7.56 3.1001C4.49 3.1001 2 5.6001 2 8.6901C2 9.8801 2.19 10.9801 2.52 12.0001C4.1 17.0001 8.97 19.9901 11.38 20.8101C11.72 20.9301 12.28 20.9301 12.62 20.8101C15.03 19.9901 19.9 17.0001 21.48 12.0001C21.81 10.9801 22 9.8801 22 8.6901C22 5.6001 19.51 3.1001 16.44 3.1001Z" fill="#292D32"/>
                    </svg>
                </template>
                
                <template x-if="!inWishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12.62 20.81C12.28 20.93 11.72 20.93 11.38 20.81C8.48 19.82 2 15.69 2 8.68998C2 5.59998 4.49 3.09998 7.56 3.09998C9.38 3.09998 10.99 3.97998 12 5.33998C13.01 3.97998 14.63 3.09998 16.44 3.09998C19.51 3.09998 22 5.59998 22 8.68998C22 15.69 15.52 19.82 12.62 20.81Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </template>

                {{ trans('storefront::product.wishlist') }}
            </button>

            <button
                class="btn btn-compare"
                :class="{ 'added': inCompareList }"
                @click="syncCompareList"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3.58008 5.15991H17.4201C19.0801 5.15991 20.4201 6.49991 20.4201 8.15991V11.4799" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M6.74008 2L3.58008 5.15997L6.74008 8.32001" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.4201 18.84H6.58008C4.92008 18.84 3.58008 17.5 3.58008 15.84V12.52" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.26 21.9999L20.42 18.84L17.26 15.6799" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                
                {{ trans('storefront::product.compare') }}
            </button>
 </div>
    </div>

    <div class="details-info-middle">
        @if ($product->variant)
            <template x-if="isActiveItem">
                <div class="product-price">
                    <template x-if="hasSpecialPrice">
                        <span class="special-price" x-text="formatCurrency(specialPrice)"></span>
                    </template>

                    <span class="previous-price" x-text="formatCurrency(regularPrice)">
                        {!! $item->is_active ? $item->hasSpecialPrice() ? $item->special_price->format() : $item->price->format() : '' !!}
                    </span>
                </div>
            </template>
        @else
            <div class="product-price">
                <template x-if="hasSpecialPrice">
                    <span class="special-price" x-text="formatCurrency(specialPrice)"></span>
                </template>

                <span class="previous-price" x-text="formatCurrency(regularPrice)">
                    {{ $item->hasSpecialPrice() ? $item->special_price->format() : $item->price->format() }}
                </span>
            </div>
        @endif

        <form
            @input="errors.clear($event.target.name)"
            @submit.prevent="addToCart"
        >
            @csrf
            @if ($product->variant)
                <div class="product-variants">
                    @include('storefront::public.products.show.variations')
                </div>
            @endif
            
            @if ($product->options->isNotEmpty())
                <div class="product-variants">
                    @foreach ($product->options as $option)
                        @includeIf("storefront::public.products.show.custom_options.{$option->type}")
                    @endforeach
                </div>
            @endif

            <div class="details-info-middle-actions">
                <div class="number-picker-lg">
                    <label for="qty">{{ trans('storefront::product.quantity') }}</label>

                    <div class="input-group-quantity">
                        <input
                            x-ref="inputQuantity"
                            type="text"
                            :value="cartItemForm.qty"
                            autocomplete="off"
                            min="1"
                            :max="maxQuantity"
                            id="qty"
                            class="form-control input-number input-quantity"
                            :disabled="isAddToCartDisabled"
                            @focus="$event.target.select()"
                            @input="updateQuantity(Number($event.target.value))"
                            @keydown.up="updateQuantity(cartItemForm.qty + 1)"
                            @keydown.down="updateQuantity(cartItemForm.qty - 1)"
                        >

                        <span class="btn-wrapper">
                            <button
                                type="button"
                                aria-label="quantity"
                                class="btn btn-number btn-plus"
                                :disabled="isQtyIncreaseDisabled"
                                @click="updateQuantity(cartItemForm.qty + 1)"
                            >
                                +
                            </button>

                            <button
                                type="button"
                                aria-label="quantity"
                                class="btn btn-number btn-minus"
                                :disabled="isQtyDecreaseDisabled"
                                @click="updateQuantity(cartItemForm.qty - 1)"
                            >
                                -
                            </button>
                        </span>
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-add-to-cart"
                    :class="{'btn-loading': addingToCart }"
                    :disabled="isAddToCartDisabled"
                    x-text="isActiveItem ? '{{ trans('storefront::product.add_to_cart') }}' : '{{ trans('storefront::product.unavailable') }}'"
                >
                    {{ trans($item->is_active ? 'storefront::product.add_to_cart' : 'storefront::product.unavailable') }}
                </button>



            </div>
        </form>
        <button
    type="button"
    class="btn btn-primary btn-buy-now"
    :class="{'btn-loading': addingToCart }"
    :disabled="isAddToCartDisabled"
    onclick="buyNowProduct({{ $product->id }}, {{ $product->variant ? $product->variant->id : 'null' }})"
    style="margin-top: 10px; width: 100%;"
>
    {{ trans('storefront::product.buy_now') }}
</button>
     <button
    type="button"
class="btn btn-primary btn-buy-now"
    style="background: #25D366; color: white; margin-top: 10px; width: 100%;"
    onclick="window.open('https://wa.me/+8801626435955?text=Hi, I\'m interested in this product: {{ urlencode($product->name) }} - {{ urlencode(route('products.show', $product->slug)) }}', '_blank')"
>
    <i class="lab la-whatsapp"></i>
   Chat on WhatsApp
</button>

<script>
function buyNowProduct(productId, variantId) {
    const qty = document.getElementById('qty').value || 1;
    
    // Collect all product data
    const productData = {
        product_id: productId,
        qty: qty
    };
    
    if (variantId) {
        productData.variant_id = variantId;
    }
    
    // Get all option values
    const options = {};
    document.querySelectorAll('select, input[type="radio"]:checked, input[type="checkbox"]:checked, input[type="text"], input[type="date"], input[type="datetime-local"], input[type="time"], textarea').forEach(el => {
        if (el.name && el.name.includes('options[')) {
            const optionId = el.name.match(/\d+/)[0];
            if (el.type === 'checkbox') {
                if (!options[optionId]) options[optionId] = [];
                options[optionId].push(el.value);
            } else {
                options[optionId] = el.value;
            }
        }
    });
    
    if (Object.keys(options).length > 0) {
        productData.options = options;
    }
    
    // Disable button and show loading
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    
    // Add to cart first, then redirect to checkout
    let csrfToken = null;
    
    // Try multiple ways to get CSRF token
    if (document.querySelector('meta[name="csrf-token"]')) {
        csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    } else if (document.querySelector('input[name="_token"]')) {
        csrfToken = document.querySelector('input[name="_token"]').value;
    } else if (typeof window.Laravel !== 'undefined' && window.Laravel.csrfToken) {
        csrfToken = window.Laravel.csrfToken;
    } else if (typeof window.FleetCart !== 'undefined' && window.FleetCart.csrfToken) {
        csrfToken = window.FleetCart.csrfToken;
    }
    
    // If still no token, try to get from any input with name containing token
    if (!csrfToken) {
        const tokenInput = document.querySelector('input[name*="token"]');
        if (tokenInput) {
            csrfToken = tokenInput.value;
        }
    }
    
    // If still no token, we can't proceed
    if (!csrfToken) {
        alert('Security token not found. Please refresh the page and try again.');
        btn.disabled = false;
        btn.innerHTML = '{{ trans('storefront::product.buy_now') }}';
        return;
    }
    
    // Build form data for cart API
    const formData = new FormData();
    formData.append('product_id', productData.product_id);
    formData.append('qty', productData.qty);
    if (productData.variant_id) {
        formData.append('variant_id', productData.variant_id);
    }
    
    // Add options to form data
    if (productData.options) {
        Object.keys(productData.options).forEach(key => {
            if (Array.isArray(productData.options[key])) {
                productData.options[key].forEach(value => {
                    formData.append(`options[${key}][]`, value);
                });
            } else {
                formData.append(`options[${key}]`, productData.options[key]);
            }
        });
    }
    
    // Add to cart
    fetch('/cart/items', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Update cart if needed
        if (window.Alpine && window.Alpine.store) {
            window.Alpine.store('cart').updateCart(data);
        }
        // Redirect to checkout
        window.location.href = '/checkout';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        // Reset button
        btn.disabled = false;
        btn.innerHTML = '{{ trans('storefront::product.buy_now') }}';
    });
}
</script>
    </div>

    <div class="details-info-bottom">
        <ul class="list-inline additional-info">
            <template x-cloak x-if="item.sku">
                <li class="sku">
                    <label>{{ trans('storefront::product.sku') }}</label>
                    
                    <span x-text="item.sku">{{ $item->sku }}</span>
                </li>
            </template>
<!--<div>{{$product}}</div>-->
@if (optional($product->categories)->isNotEmpty())
                <li>
                    <label>{{ trans('storefront::product.categories') }}</label>

                    @foreach ($product->categories as $category)
                        <a href="{{ $category->url() }}">{{ $category->name }}</a>{{ $loop->last ? '' : ',' }}
                    @endforeach
                </li>
            @endif
 @if (!empty($product->brand))
    <li>
        <label>Brand: </label>
        <a href="{{ url('brands/' . $product->brand->slug . '/products') }}">
            {{ $product->brand->name }}
        </a>
    </li>
@endif

@if (optional($product->tags)->isNotEmpty())
                <li>
                    <label>{{ trans('storefront::product.tags') }}</label>

                    @foreach ($product->tags as $tag)
                        <a href="{{ $tag->url() }}">{{ $tag->name }}</a>{{ $loop->last ? '' : ',' }}
                    @endforeach
                </li>
            @endif
        </ul>

        @include('storefront::public.products.show.social_share')
    </div>
</div>
