<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Block\Adminhtml\Sales\Order\OrderCustomerData;

use Aitoc\CheckoutFieldsManager\Block\Adminhtml\Sales\Order\OrderCustomerData;

/**
 * Edit order address form container block
 */
class Create extends OrderCustomerData
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_mode = 'checkoutFields_create';
    }
}
