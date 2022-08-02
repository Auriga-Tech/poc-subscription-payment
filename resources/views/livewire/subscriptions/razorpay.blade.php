<div>
    <script src = "https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ config('services.razorpay.key') }}",
            "subscription_id": "{{ $subscription_id }}",
            "name": "{{ config('app.name') }}",
            "description": "{{ 'Payment for '.$product->name }}",
            "image": "/your_logo.png",
            "callback_url": "{{ route('buy-product.razorpay-subscription.payment') }}",
            "prefill": {
                "name": "{{ Auth::User()->name }}",
                "email": "{{ Auth::User()->email }}"
            },
            "notes": { 
                "subscription_id": "{{ $subscription_id }}"
            },
            "theme": {
                "color": "#F37254"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    </script>
</div>
