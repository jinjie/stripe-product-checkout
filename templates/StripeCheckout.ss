<p><%t STRIPEPRODUCTCHECKOUT.REDIRECTING 'Redirecting to secured payment gateway. Please do not close this window...' %></p>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{$StripePK}');
    stripe.redirectToCheckout({
        sessionId: '{$StripeSessionID}'
    }).then(function (result) {
        alert(result.error.message);
    });
</script>
