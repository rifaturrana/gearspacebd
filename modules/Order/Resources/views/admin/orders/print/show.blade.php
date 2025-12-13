<!DOCTYPE html>
<html lang="{{ locale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ trans('order::print.invoice') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            width: 120px;
            height: 60px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
        }

        .store-address {
            text-align: right;
            font-size: 12px;
            line-height: 1.6;
        }

        .store-address strong {
            font-weight: 600;
        }

        .document-title {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .customer-order-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .customer-details, .order-details {
            flex: 1;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .detail-row {
            margin-bottom: 3px;
            font-size: 12px;
        }

        .order-details {
            text-align: right;
        }

        .thank-you-message {
            margin: 20px 0;
            font-size: 12px;
            line-height: 1.6;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        .products-table thead {
            background: #f8f8f8;
        }

        .products-table th {
            padding: 8px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }

        .products-table th:last-child,
        .products-table td:last-child {
            text-align: right;
        }

        .products-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-name {
            font-weight: 500;
        }

        .product-sku {
            color: #666;
            font-size: 9px;
        }

        .product-variations {
            color: #666;
            font-size: 9px;
            margin-top: 3px;
        }

        .totals-section {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .totals-table {
            width: 300px;
            font-size: 12px;
        }

        .totals-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .totals-table td {
            padding: 8px 0;
        }

        .totals-table td:first-child {
            text-align: left;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 500;
        }

        .totals-table .total-row {
            font-weight: 600;
            font-size: 12px;
            border-top: 2px solid #333;
            border-bottom: none;
        }

        .totals-table .total-row td {
            padding-top: 12px;
        }

        .customer-note {
            margin-top: 20px;
            font-size: 12px;
        }

        .customer-note-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body class="{{ is_rtl() ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">
                    @if ($logo = setting('storefront_header_logo'))
                        <img src="https://gearspacebd.com/storage/media/Lat9tWCwwXa0hdX7j1kM8YTGZV2AnhzVHP2Ldl8W.jpg" alt="{{ setting('store_name') }}" style="max-width: 200px; max-height: 100px;">
                    @else
                <div class="company-name">{{ setting('store_name') }}</div>
                <div class="company-tagline">Premium Gadgets || Apple Accessories</div>                    @endif
                </div>

            </div>

            <div class="store-address">
                Motalib Plaza, Hatirpool<br>
                Dhaka 1205, Bangladesh<br>
                <strong>Phone/Nagad:</strong> {{ setting('store_phone') }}<br>
                <strong>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#25D366" style="vertical-align: middle; margin-right: 5px;">
 <path d="M17.5 14.4l-1.8-1.3c-.1-.1-.3-.1-.4 0l-1.2 1.4c-.1.1-.3.1-.4 0-1.2-.9-2-2.1-2.1-2.2-.1-.1 0-.3 0-.4l1.4-1.2c.1-.1.1-.3 0-.4l-1.3-1.8s-.1-.1-.2-.1c-.1 0-.2 0-.3.1l-1.8 1.9c-.2.2-.3.5-.3.8 0 2.5 1.2 4.9 3.1 6.6 2.1 1.9 4.7 2.8 7.4 2.8h.1c.3 0 .6-.1.8-.3l1.9-1.8c.1-.1.1-.2.1-.3 0-.1-.1-.2-.1-.2l-2.4-1.8z"/>
 <path d="M12 0C5.4 0 0 5.4 0 12c0 2.1.5 4.1 1.6 5.9L0 24l6.3-1.6c1.7 1 3.7 1.5 5.7 1.5 6.6 0 12-5.4 12-12S18.6 0 12 0zm7.1 18.9c-1.8 1.2-4 1.9-6.3 1.9-1.7 0-3.4-.4-4.9-1.2l-.4-.2-3.6 1 1-3.5-.2-.4C3.6 14.4 3 13.2 3 12c0-5 4-9 9-9s9 4 9 9c0 2.2-.8 4.2-2.1 5.8l.2.1z"/>
 </svg>    
                WhatsApp: 
                     
                </strong>
                <a href="https://wa.me/+8801626435955" target="_blank" style="color: #000; text-decoration: none;">

 01817275333
 </a>
            </div>
        </div>

        <!-- <div class="document-title">Document</div> -->

        <div class="customer-order-section">
            <div class="customer-details">
                <div class="section-title">Customer Details:</div>
                <div class="detail-row"><strong>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</strong></div>
                <div class="detail-row"><strong>Phone:</strong> {{ $order->customer_phone }}</div>
                <div class="detail-row"><strong>Address:</strong> {{ $order->shipping_address_1 }}@if($order->shipping_address_2), {{ $order->shipping_address_2 }}@endif</div>
            </div>

            <div class="order-details">
                <div class="detail-row"><strong>Order No:</strong> {{ $order->id }}</div>
                <div class="detail-row"><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                <div class="detail-row"><strong>Payable:</strong> ৳ {{ number_format($order->total->amount(), 1) }}</div>
            </div>
        </div>

        <div class="thank-you-message">
            Dear <strong>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</strong>,<br>
            Thank you very much for your order and the trust you have placed in <strong>{{ setting('store_name') }}</strong>. I hereby invoice you for the following:
        </div>

        <table class="products-table">
            <thead>
                <tr>
                    <th>Pos.</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $product->name }}</div>
                        @if($product->sku)
                        <div class="product-sku">SKU: {{ $product->sku }}</div>
                        @endif
                        @if ($product->hasAnyVariation())
                        <div class="product-variations">
                            @foreach ($product->variations as $variation)
                            {{ $variation->name }}: {{ $variation->values()->first()?->label }}{{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </div>
                        @endif
                        @if ($product->hasAnyOption())
                        <div class="product-variations">
                            @foreach ($product->options as $option)
                            {{ $option->name }}: 
                            @if ($option->option->isFieldType())
                                {{ $option->value }}
                            @else
                                {{ $option->values->implode('label', ', ') }}
                            @endif
                            {{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </div>
                        @endif
                    </td>
                    <td>{{ $product->qty }} Pc(s)</td>
                    <td>৳ {{ number_format($product->unit_price->amount(), 1) }}</td>
                    <td>৳ {{ number_format($product->line_total->amount(), 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>৳ {{ number_format($order->sub_total->amount(), 1) }}</td>
                </tr>
                @if ($order->hasShippingMethod())
                <tr>
                    <td>Shipping:</td>
                    <td>৳ {{ number_format($order->shipping_cost->amount(), 1) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td>৳ {{ number_format($order->sub_total->amount() + ($order->hasShippingMethod() ? $order->shipping_cost->amount() : 0), 1) }}</td>
                </tr>
                @foreach ($order->taxes as $tax)
                <tr>
                    <td>{{ $tax->name }}</td>
                    <td>৳ {{ number_format($tax->order_tax->amount->amount(), 1) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>Total Paid</td>
                    <td>৳ {{ number_format($order->total->amount(), 1) }}</td>
                </tr>
                <tr>
                    <td>Total Due</td>
                    <td>৳ 0.0</td>
                </tr>
                <!-- <tr class="total-row">
                    <td></td>
                    <td>৳ {{ number_format($order->total->amount(), 1) }}</td>
                </tr> -->
            </table>
        </div>

        <div class="customer-note">
            <div class="customer-note-title">Customer Note:</div>
            <div>Yours sincerely,</div>
            <div><strong>{{ setting('store_name') }}</strong></div>
        </div>

        <div class="footer">
            https://store.{{ strtolower(str_replace(' ', '', setting('store_name'))) }}.com/sells
        </div>
    </div>

    <script type="module">
        window.print();
    </script>
</body>

</html>