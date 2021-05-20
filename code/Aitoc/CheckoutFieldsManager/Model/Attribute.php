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

use Aitoc\CheckoutFieldsManager\Api\Data\AttributeInterface;

class Attribute extends \Magento\Eav\Model\Attribute implements AttributeInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'customer_entity_attribute';

    /**
     * Prefix of model events object
     *
     * @var string
     */
    protected $_eventObject = 'attribute';

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Aitoc\CheckoutFieldsManager\Model\ResourceModel\Attribute');
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setDisplayArea($data)
    {
        return $this->setData('display_area', $data);
    }

    /**
     * Get Display Area, contained in additional EAV table (aitoc_checkout_eav_attribute)
     *
     * @return mixed
     */
    public function getDisplayArea()
    {
        return $this->_getData('display_area');
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setCheckoutStep($data)
    {
        return $this->setData('checkout_step', $data);
    }

    /**
     * Get Checkout Step, contained in additional EAV table (aitoc_checkout_eav_attribute)
     *
     * @return string|null
     */
    public function getCheckoutStep()
    {
        return $this->_getData('checkout_step');
    }

    /**
     * @param bool $data
     *
     * @return $this
     */
    public function setIsVisible($data)
    {
        return $this->setData('is_visible', $data);
    }

    /**
     * @return bool
     */
    public function getIsVisible()
    {
        return (bool) $this->_getData('is_visible');
    }
}
