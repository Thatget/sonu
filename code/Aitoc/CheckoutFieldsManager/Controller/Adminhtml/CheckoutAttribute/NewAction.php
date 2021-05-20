<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Controller\Adminhtml\CheckoutAttribute;

use Aitoc\CheckoutFieldsManager\Controller\Adminhtml\CheckoutAttribute;

class NewAction extends CheckoutAttribute
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
