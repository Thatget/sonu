<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Model\Entity\Source;

class Table extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * Retrieve Full Option values array
     *
     * {@inheritdoc}
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $result = parent::getAllOptions($withEmpty, $defaultValues);

        if ($withEmpty) {
            foreach ($result as $key => $item) {
                if (empty(str_replace(' ', '', $item['label']))) {
                    $result[$key]['label'] = __('Please Select');
                }
            }
        }

        return $result;
    }
}
