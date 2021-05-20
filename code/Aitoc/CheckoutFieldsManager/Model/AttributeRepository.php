<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Model;

use Aitoc\CheckoutFieldsManager\Api\AttributeRepositoryInterface;
use Aitoc\CheckoutFieldsManager\Api\Data\AttributeInterface;
use Aitoc\CheckoutFieldsManager\Api\Data\AttributeInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class AttributeRepository implements AttributeRepositoryInterface
{
    /**
     * @var AttributeInterfaceFactory
     */
    private $attributeFactory;

    public function __construct(AttributeInterfaceFactory $attributeFactory)
    {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $attribute = $this->attributeFactory->create();
        $resource = $attribute->getResource();
        $resource->load($attribute, $id);

        if (!$attribute->getId()) {
            throw new NoSuchEntityException(__('Unable to find attribute with ID "%1"', $id));
        }

        return $attribute;
    }

}
