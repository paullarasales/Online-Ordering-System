@component('mail::message')
# Order Receipt

Thank you for your order!

**Order ID:** {{ $order->id }}  
**Address:** {{ $order->address }}  
**Payment Method:** {{ $order->payment_method }}

@component('mail::table')
| Product       | Quantity      | Price  |
| ------------- |:-------------:| ------:|
@foreach ($order->items as $item)
| {{ $item->product->product_name }} | {{ $item->quantity }} | ₱{{ number_format($item->product->price * $item->quantity + 60, 2) }} |
@endforeach
| **Total**     |               | **₱{{ number_format($order->items->sum(function ($item) { return $item->product->price * $item->quantity + 60; }), 2) }}** |
@endcomponent

Thanks for shopping with us!  
{{ config('app.name') }}
@endcomponent
