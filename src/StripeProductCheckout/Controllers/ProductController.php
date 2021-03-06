<?php

/**
 * ProductController
 *
 * @package StripeProductCheckout
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace StripeProductCheckout\Controllers;

use SilverStripe\Core\Environment;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\NumericField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use StripeProductCheckout\Models\Product;
use Stripe\Stripe;

class ProductController extends \PageController
{
    private static $allowed_actions = [
        'CheckoutForm',
        'success',
        'cancel',
    ];

    public function CheckoutForm()
    {
        $form = Form::create(
            $this,
            'CheckoutForm',
            FieldList::create([
                NumericField::create('Quantity')->setValue(1),
                HiddenField::create('ProductID')->setValue($this->ID)
            ]),
            FieldList::create([
                FormAction::create('doCheckout', 'Checkout'),
            ])
        );

        return $form;
    }

    public function doCheckout($data, $form)
    {
        $product = DataObject::get_one(Product::class, [
            'SiteTree_Live.ID' => $data['ProductID'],
        ]);

        if (!$product) {
            return $this->httpError(404);
        }

        $items = [
            [
                'name'        => $product->Title,
                'amount'      => $product->UnitPrice * 100,
                'description' => $product->obj('Content')->Plain(),
                'currency'    => 'sgd',
                'quantity'    => $data['Quantity'],
            ]
        ];

        $this->extend('updateCheckoutItems', $items);

        if ($product->Images()->Count()) {
            $items[0]['images'][] = $product->Images()->Sort('SortOrder')->First()->AbsoluteURL;
        }

        Stripe::setApiKey(Environment::getEnv('STRIPE_SK'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types'        => ['card'],
            'line_items'                  => $items,
            'success_url'                 => $this->AbsoluteLink('success'),
            'cancel_url'                  => $this->AbsoluteLink('cancel'),
            'shipping_address_collection' => [
                'allowed_countries' => [
                    'SG',
                    'MY',
                ]
            ],
        ]);

        return $this->customise([
            'Layout'          => $product->customise([
                'StripePK'        => Environment::getEnv('STRIPE_PK'),
                'StripeSessionID' => $session->id
            ])->renderWith('StripeCheckout'),
        ]);
    }

    public function success()
    {
        $data = ArrayData::create([
            'StripeMessage' => [
                'Type'    => 'success',
                'Message' => _t(self::class . '.SUCCESSMESSAGE', 'We have received your order. You will be notified by email regarding your order.'),
            ]
        ]);

        return $this->customise([
            'StripeMessage' => $data->renderWith('StripeMessage')
        ]);
    }

    public function cancel()
    {
        $data = ArrayData::create([
            'StripeMessage' => [
                'Type'    => 'danger',
                'Message' => _t('STRIPEPRODUCTCHECKOUT.SUCCESSMESSAGE', 'Your order is cancelled. Please try again.'),
            ]
        ]);

        return $this->customise([
            'StripeMessage' => $data->renderWith('StripeMessage')
        ]);
    }
}
