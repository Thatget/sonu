<?php

namespace MW\CustomCurrency\Block;

use Magento\Directory\Block\Currency as defaultCurrency;

Class Currency extends defaultCurrency{


    protected $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        array $data = [],
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $currencyFactory, $postDataHelper, $localeResolver, $data);
    }

    /**
     * @param $currencyCode
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWebsiteUrl($currencyCode) {
		foreach($this->_storeManager->getStores() as $store){
			$currencyStoreCode = $this->_storeManager->getStore($store->getId())->getBaseCurrencyCode();
			if($currencyCode === $currencyStoreCode){
				return $store->getBaseUrl();
			}
		}
		return "";
    }
}