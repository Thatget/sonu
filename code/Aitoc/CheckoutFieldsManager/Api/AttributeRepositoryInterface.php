<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Api;

use Aitoc\CheckoutFieldsManager\Api\Data\AttributeInterface;

//todo: add save/delete methods

interface AttributeRepositoryInterface
{
    /**
     * @param $id
     * @return AttributeInterface
     */
    public function get($id);
}