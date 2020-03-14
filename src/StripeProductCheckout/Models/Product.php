<?php

/**
 * Product
 *
 * @package StripeProductCheckout\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace StripeProductCheckout\Models;

use Bummzack\SortableFile\Forms\SortableUploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\HiddenField;
use StripeProductCheckout\Controllers\ProductController;

class Product extends \Page
{
    private static $db = [
        'UnitPrice' => 'Currency',
    ];

    private static $many_many = [
        'Images' => Image::class,
    ];

    private static $many_many_extraFields = [
        'Images' => [
            'SortOrder' => 'Int',
        ],
    ];

    private static $owns = [
        'Images',
    ];

    private static $can_be_root = false;

    private static $allowed_children = [];

    private static $show_in_sitetree = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                CurrencyField::create('UnitPrice'),
                SortableUploadField::create('Images')
                    ->setDescription('First image will be the thumbnail of the product'),
            ],
            'Content'
        );

        return $fields;
    }

    public function BuyNowButton()
    {
        $form = ProductController::create($this)->CheckoutForm();

        $form->Fields()->replaceField('Quantity', HiddenField::create('Quantity')->setValue(1));
        $form->Actions()
            ->fieldByName('action_doCheckout')
            ->addExtraClass('w-100')
            ->setTitle('Buy Now');

        return $form;
    }

    public function getControllerName()
    {
        return ProductController::class;
    }
}
