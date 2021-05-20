<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MW\CustomCurrency\Plugin\Checkout\Controller\Cart;

use Magento\Catalog\Model\{ProductFactory, Product};

class Add
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @param ProductFactory $productFactory
     */
    public function __construct(
         ProductFactory $productFactory
    )
    {
        $this->productFactory = $productFactory;
    }

    /**
     * @param \Magento\Checkout\Controller\Cart\Add $subject
     * @return array
     */
    public function beforeExecute(
        \Magento\Checkout\Controller\Cart\Add $subject
    ) {
        $requestParams = $subject->getRequest()->getParams();
        // we already have bundle option, meaning we're on product detail page, skip further processing of bundle options
        if (isset($requestParams['bundle_option'])) {
            return [];
        }
        $productId = (int)$subject->getRequest()->getParam('product');
        $product = $this->productFactory->create()->load($productId);
        if ($product && ($product->getTypeId() == 'bundle')) {
            $bundleOptions['bundle_option'] = $this->getBundleOptions($product);
            $subject->getRequest()->setParams(array_merge($requestParams, $bundleOptions));
        }
        return [];
    }

    /**
     * get all the selection products used in bundle product
     * @param $product
     * @return mixed
     */
    private function getBundleOptions(Product $product)
    {
        $selectionCollection = $product->getTypeInstance()
            ->getSelectionsCollection(
                $product->getTypeInstance()->getOptionsIds($product),
                $product
            );
        $bundleOptions = [];
        foreach ($selectionCollection as $selection) {
            $bundleOptions[$selection->getOptionId()][$selection->getProductId()] = $selection->getSelectionId();
        }
        return $bundleOptions;
    }
}