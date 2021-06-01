<?php
/**------------------------------------------------------------------------

* Author: MW Company

-------------------------------------------------------------------------*/

namespace Sm\SearchBox\Controller\Advanced;

use Magento\Framework\Escaper;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Result extends \Magento\Framework\App\Action\Action{

	/**
     * @var Escaper
     */
    private $escaper;
	
	/**
     * @var ProductFactory
     */
    protected $productFactory;
	
	/**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
	
	public function __construct(
		Escaper $escaper,
		ProductFactory $productFactory,
		JsonFactory $resultJsonFactory,
		\Magento\Framework\App\Action\Context $context
	)
	{
		$this->escaper = $escaper;
		$this->productFactory = $productFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		parent::__construct($context);
	}

	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		$response = array();
		$product = $this->productFactory->create();
		$sku = trim($data['sku']);
		$productBySku = $product->load($product->getIdBySku($sku));
		if($productBySku->getId()){
			if (!($product->getVisibility() == Visibility::VISIBILITY_NOT_VISIBLE)){
				$response['url'] = $productBySku->getProductUrl();
				$response['redirect'] = true;
			}else{
			$response['url'] = $this->escaper->escapeHtml($sku);
			$response['redirect'] = false;
		}
		}else{
			$response['url'] = $this->escaper->escapeHtml($sku);
			$response['redirect'] = false;
		}
		return $this->resultJsonFactory->create()->setData(['data' =>$response]);
	}

}