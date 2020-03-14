<?php

/**
 * ProductHolderPage
 *
 * @package StripeProductCheckout\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace StripeProductCheckout\Models;

use SilverStripe\Lumberjack\Model\Lumberjack;
use StripeProductCheckout\Models\Product;

class ProductHolderPage extends \Page
{
    private static $extensions = [
        Lumberjack::class,
    ];

    private static $allowed_children = [
        Product::class,
    ];

    private static $description = 'Page that holds multiple products.';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.ChildPages')->setTitle('Products');
        $fields->fieldByName('Root.ChildPages.ChildPages')->setTitle('Products');

        return $fields;
    }
}
