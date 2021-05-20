<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MW\CustomCurrency\Plugin\Catalog\Model\Product\Type;

class AbstractType
{

    /**
     * contains required products in its bundle, magento by default does not allow adding these bundles
     * to cart without specifying the bundle products.
     * @param \Magento\Catalog\Model\Product\Type\AbstractType $subject
     * @param $result
     * @param $product
     * @return bool
     */
    public function afterIsPossibleBuyFromList(
        \Magento\Catalog\Model\Product\Type\AbstractType $subject,
        $result,
        $product
    ) {
        if (!$result && ($product->getTypeId() == 'bundle')) {
            return true;
        }
        return $result;
    }
}