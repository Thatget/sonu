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

interface QuoteCustomerDataInterface extends AbstractCustomerDataInterface
{
    const KEY_QUOTE_ID = 'quote_id';

    /**
     * @return int|null
     */
    public function getQuoteId();

    /**
     * @param int $quoteId
     *
     * @return $this
     */
    public function setQuoteId($quoteId);
}
