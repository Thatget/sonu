<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Api\Data;

interface OrderCustomerDataInterface extends AbstractCustomerDataInterface
{
    const KEY_ORDER_ID = 'order_id';

    /**
     * @return int|null
     */
    public function getOrderId();

    /**
     * @param int $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId);
}
