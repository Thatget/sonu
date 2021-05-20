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

interface AttributeInterface
{
    /**
     * @return string
     */
    public function getCheckoutStep();

    /**
     * @param string $step
     */
    public function setCheckoutStep($step);

    /**
     * @param string $displayArea
     */
    public function setDisplayArea($displayArea);

    /**
     * @return string
     */
    public function getDisplayArea();

    /**
     * @param bool $isVisible
     * @return void
     */
    public function setIsVisible($isVisible);

    /**
     * @return bool
     */
    public function getIsVisible();
}
