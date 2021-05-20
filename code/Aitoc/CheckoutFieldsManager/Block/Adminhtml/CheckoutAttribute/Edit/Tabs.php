<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Block\Adminhtml\CheckoutAttribute\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Aitoc checkout fields left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Tabs constructor.
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('checkoutattribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Checkout attribute Information'));
    }

    /**
     * @inheritdoc
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getChildHtml('main'),
                'active' => true
            ]
        );

        $this->addTab(
            'labels_section',
            [
                'label' => __('Manage Labels'),
                'title' => __('Manage Labels'),
                'content' => $this->getChildHtml('labels'),
            ]
        );

        if (!$this->storeManager->isSingleStoreMode()) {
            $this->addTab(
                'websitesstoreviews_section',
                [
                    'label' => __('Websites / Store Views'),
                    'title' => __('Websites / Store Views'),
                    'content' => $this->getChildHtml('checkoutfieldsmanager_checkoutattribute_edit_tab_websitesstoreviews')
                ]
            );
        }

        $this->addTab(
            'front',
            [
                'label' => __('Storefront Properties'),
                'title' => __('Storefront Properties'),
                'content' => $this->getChildHtml('front')
            ]
        );

        return parent::_beforeToHtml();
    }
}
