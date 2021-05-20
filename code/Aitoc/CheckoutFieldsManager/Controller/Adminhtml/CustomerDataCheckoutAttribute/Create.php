<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Controller\Adminhtml\CustomerDataCheckoutAttribute;

use Aitoc\CheckoutFieldsManager\Controller\Adminhtml\CustomerDataCheckoutAttribute;

class Create extends CustomerDataCheckoutAttribute
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aitoc_CheckoutFieldsManager::actions_create';

    /**
     * Edit order address form
     *
     * @inheritdoc
     */
    public function execute()
    {
        return $this->getResult();
    }
}
