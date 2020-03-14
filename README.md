## Stripe Checkout for SilverStripe 4

This does not intend to be a full e-commerce solution.

### Installation

`composer require jinjie/stripe-product-checkout`

### Configuration

Add Stripe keys to `.env` file

```
STRIPE_PK="<PUBLISHABLE KEY>"
STRIPE_SK="<SECRET KEY>"
```

### Extending

By default, only the product will be used in line_items in checkout.
You can change this with something like below.

```
StripeProductCheckout\Controllers\ProductController:
  extensions:
    - MyProductExtension
```

```
// ...
public function updateCheckoutItems(&$items)
{
    // Manipulate $items array here to be used in line_items
}
// ...
```
