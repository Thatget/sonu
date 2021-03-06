<?php
/**
 *  Gaiterjones BuyXGetY Model
 *
 *  @category    Gaiterjones
 *  @package     Gaiterjones_Buyxgety
 *  @author      modules@gaiterjones.com
 *
 */

namespace Gaiterjones\BuyXGetY\Model;
use Gaiterjones\BuyXGetY\Helper\Data;
use \Magento\Catalog\Model\ProductRepository;
use \Magento\Checkout\Model\Cart;
use \Magento\Quote\Api\CartRepositoryInterface;
use \Magento\Framework\Data\Form\FormKey;
use \Magento\Framework\Message\ManagerInterface;
use \Psr\Log\LoggerInterface;

/**
 * BuyXGetY Model
 */
class SpendXGetY extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var ProductRepository
     */
    protected $_productRepository;
    /**
     * @var Cart
     */
    protected $_cart;
    protected $_cartInterface;
    /**
     * @var FormKey
     */
    protected $formKey;
    /**
     * @var ManagerInterface
     */
    protected $_messageManager;
    /**
     * @var Helper
     */
    protected $_helperData;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var array
     */
    private $_buyxgety;
    /**
     * @var boolean
     */
    private $_debug;

    /**
     * @param ProductRepository $productRepository
     * @param Cart $cart
     * @param FormKey $formKey
     * @param ManagerInterface $messageManager
     * @param Data $helperData
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        ProductRepository $productRepository,
        Cart $cart,
        FormKey $formKey,
        ManagerInterface $messageManager,
        Data $helperData,
        LoggerInterface $loggerInterface,
        CartRepositoryInterface $cartInterface
    ) {
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
        $this->formKey = $formKey;
        $this->_messageManager = $messageManager;
        $this->_helperData = $helperData;
        $this->_logger = $loggerInterface;
        $this->_cartInterface=$cartInterface;
        $this->loadConfig();

    }

    /**
     * loadConfig load BUYXGETY config data
     *
     * @return boolean
     */
    protected function loadConfig(){

        $this->_debug=true;

        $productYSku=$this->cleanArray(explode(',',$this->_helperData->getConfig('buyxgety/spendxgety/spendproductysku')));
        if (empty($productYSku)){$productYSku=false;}

        $spendCartYLimit=$this->cleanArray(explode(',',$this->_helperData->getConfig('buyxgety/spendxgety/spendcartylimit')));
        if (empty($spendCartYLimit)){$spendCartYLimit=0;}

        $spendCartTotalRequired=$this->cleanArray(explode(',',$this->_helperData->getConfig('buyxgety/spendxgety/spendcarttotalrequired')));
        if (empty($spendCartTotalRequired)){$spendCartTotalRequired=false;}

        $productYDescription=$this->cleanArray(explode(',',$this->_helperData->getConfig('buyxgety/spendxgety/productydescription')));
        if (empty($productYDescription)){$productYDescription=false;}

        $config=array(
            'spendxgety' => array(
                'productysku'              => $productYSku,
                'spendcartylimit'          => $spendCartYLimit,
                'spendcarttotalrequired'   => $spendCartTotalRequired,
                'productydescription'      => $productYDescription
            )
        );

        $this->log($config);
        $this->_buyxgety['config']=$config;
    }

    /**
     * isConfigValid validate SPENDXGETY config data
     *
     * @return boolean
     */
    protected function isConfigValid()
    {
        foreach($this->_buyxgety['config']['spendxgety'] as $key => $configData)
        {
            // ensure config values are unique
            //
            if ($key==='productydescription' ){continue;}
            if ($key==='spendcarttotalrequired' ){continue;}
            if ($key==='spendcartylimit' ){continue;}

            if ($configData===false){return false;}

            if ($this->isUnique($configData)== true){
                return false;
            }

        }

        return true;
    }

    /**
     * CartUpdate SPENDXGETY Cart Update Logic
     *
     * @return boolean
     */
    public function CartUpdate()
    {
        // is module enabled
        //
        if (!$this->isEnabled()){
            $this->log('BUYXGETY SPEND X functionality is disabled in config.');
            return;
        }

        // is module config valid
        //
        if (!$this->isConfigValid())
        {
            $this->addMessage(__('Spend X Get Y configuration is invalid.'),'error');
            $this->log('BUYXGETY SPENDX configuration is invalid '. print_r($this->_buyxgety['config']['spendxgety'],true));
            return;
        }

        // get config
        //
        $productYSkus=$this->_buyxgety['config']['spendxgety']['productysku'];
        $spendCartYLimit=$this->_buyxgety['config']['spendxgety']['spendcartylimit'];
        $spendCartTotalRequired=$this->_buyxgety['config']['spendxgety']['spendcarttotalrequired'];
        $productYDescriptions=$this->_buyxgety['config']['spendxgety']['productydescription'];

        // get cart total
        //
        $subTotal = $this->_cart->getQuote()->getBaseSubtotalWithDiscount();

        //foreach ($productYSkus as $key => $productYSku)
		for($key = 0;$key < count($productYSkus); $key++)
        {
			$conditionTwo = false;
			if($key + 1 >= count($productYSkus)){
				$conditionTwo = true;
			}else{
				if($subTotal < $spendCartTotalRequired[$key+1]){
					$conditionTwo = true;
				}
			}
            if (($subTotal >= $spendCartTotalRequired[$key]) && ($conditionTwo))
            {
                $this->log('BUYXGETY cart sub total of '. $subTotal. ' meets minimum requirement of '. $spendCartTotalRequired[$key]);

                // LOGIC
                //
                $cartData=$this->getCartItems();
                if ($cartData)
                {

                    // LOGIC 1
                    if (
                            isset($cartData[$productYSkus[$key]])
                        )
                    {
                        // product y IN Cart
                        // minimum spend requirement met
                        $this->log('product y IN cart - minimum spend requirement met - do nothing');
                        if ($itemId=$cartData[$productYSkus[$key]]['qty'] > 1)
                        {
                            // control quantity
                            $this->log('product y IN cart - controlling quantity');
                            $itemId=$cartData[$productYSkus[$key]]['itemid'];
                            $this->checkProductCartQuantity($itemId);
                        }

                    } else {

                        // product y NOT IN Cart
                        // minimum spend requirement met
                        $this->log('product y NOT in cart - minimum spend requirement met - add to cart');
                        $this->addProductToCart($productYSkus[$key],1,$productYDescriptions[$key]);

                    }


                }

            } else {

                $cartData=$this->getCartItems();
                if ($cartData)
                {

                    $this->log('BUYXGETY cart sub total of '. $subTotal. ' does not meet minimum requirement of '. $spendCartTotalRequired[$key]);

                    if (
                            isset($cartData[$productYSkus[$key]])
                        )
                    {
                        // product y IN Cart
                        // minimum spend requirement NOT met - remove from cart
                        $this->log('product y IN cart - minimum spend requirement NOT met - remove from cart');
                        $itemId=$cartData[$productYSkus[$key]]['itemid'];
                        $this->removeProductFromCart($itemId,$productYDescriptions[$key]);

                    } else {

                        $this->log('product y NOT IN cart');
                    }
                }
            }
        }



    }


    /**
     * Gets cart from session
     *
     * @return array
     */
    public function getCartItems()
    {
        // build cart data arrqy
        //
        $cartItems = $this->_helperData->getCartAllItems();
        $cartItemQuantities=array();
        $cartItemQuantitiesBySku=array();
        $cartData=false;
        $count=0;

        foreach ($cartItems as $item)
        {
            $count++;

            // if item has parent, use parent
            //
            //if ($item->getParentItem()) {$item=$item->getParentItem();}

            $cartData[$item->getSku()]=array(
                'name' =>  $item->getName(),
                'qty' =>  $item->getQty(),
                'itemid' =>  $item->getId(),
                'type' => $item->getProduct()->getTypeId(),
                'productid' => $item->getProduct()->getId(),
            );

            // build quantities array to get true totals for products with parents, i.e. configurable products
            //
            $cartItemQuantities[$item->getProduct()->getId()][] = $item->getQty();
            $cartItemQuantitiesBySku[$item->getProduct()->getSku()][] = $item->getQty();
        }

        $cartData['cartItemQuantities']=$cartItemQuantities;
        $cartData['cartItemQuantitiesBySku']=$cartItemQuantitiesBySku;

        $this->log(array('BUYXGETY' => $cartData));
        $this->log('BUYXGETY Total Cart Items : '. $count);
        return $cartData;

    }

    /**
     * addProductToCart adds $productSku to cart default $qty = 1
     *
     * @param string $productSku
     * @param int $qty
     * @return void
     */
    protected function addProductToCart($productSku,$qty=1,$productYDescription='Free Product')
    {
        $this->log('trying to add product SKU '. $productSku. ' to the cart...');
        $product = $this->_productRepository->get($productSku);
        $productID=$product->getId();

        $params = array(
            'product' => $productID,
            'qty' => $qty
        );

        $this->_cart->addProduct($product,$params);
        $this->_cart->save();

        // https://magento.stackexchange.com/questions/138531/magento-2-how-to-update-cart-after-cart-update-event-checkout-cart-update-ite
        //$quoteObject = $this->_cartInterface->get($this->_cart->getQuote()->getId());
        //$quoteObject->setTriggerRecollect(1);
        //$quoteObject->setIsActive(true);
        //$quoteObject->collectTotals()->save();

        $this->addMessage(__('Your free gift %1 has been added to your cart.',$productYDescription),'success');
        $this->log('product SKU '. $productSku. ' was added to the cart.');

    }

    /**
     * removeProductFromCart removes $itemID from cart
     *
     * @param string $itemId
     * @return void
     */
    protected function removeProductFromCart($itemId,$productYDescription='Free Product')
    {

        $this->_cart->removeItem($itemId);
        $this->_cart->save();

        // does this need a message?
        $this->addMessage(__('Your free gift %1 has been removed from your cart.',$productYDescription),'success');

        $this->log('cart ID '. $itemId. ' was removed from the cart.');

    }

    /**
     * checkProductCartQuantity controls the allowed quantitiy for $item in the cart by setting $qty
     *
     * @param string $itemId
     * @param int $qty
     * @return void
     */
    protected function checkProductCartQuantity($itemId,$qty=1)
    {
        $params[$itemId]['qty'] = $qty;
        $this->_cart->updateItems($params);

        $this->_cart->save();

        //$this->addMessage('Only '. $qty. ' free product is allowed.','notice');

        $this->log('cart ID '. $itemId. ' was checked for qty.');

    }

    /**
     * Add Message - checks for message duplication
     *
     * @param string $newMessage
     * @param string $messageType - defaults to notice
     * @return void
     */
    protected function addMessage($newMessage,$messageType='notice')
    {

        $messages=$this->getMessages();
        foreach ($messages as $message)
        {
            // try not to duplicate messages
            if ($newMessage == $message){return false;}
        }

        if ($messageType==='notice'){$this->_messageManager->addNoticeMessage($newMessage);}
        if ($messageType==='error'){$this->_messageManager->addErrorMessage($newMessage);}
        if ($messageType==='success'){$this->_messageManager->addSuccessMessage($newMessage);}
        if ($messageType==='warning'){$this->_messageManager->addWarningMessage($newMessage);}

    }

    /**
     * getMessages from Message Manager and create array of messages
     *
     * @return array
     */
    protected function getMessages()
    {
        $messages = array();
        $collection = $this->_messageManager->getMessages();
        if ($collection && $collection->getItems()) {
            foreach ($collection->getItems() as $message) {
                $messages[] = $message->getText();
            }
        }

        return $messages;
    }

    /**
     * isEnabled
     *
     * @return boolean
     */
    protected function isEnabled()
    {
        return $this->_helperData->getConfig('buyxgety/spendxgety/spendxgetyenable');
    }

    /**
     * log
     *
     * @param string $data
     * @return boolean
     */
    public function log($data)
    {
        if (!$this->_debug) {return;}

        if (is_array($data))
        {
            $this->_logger->debug('debug BUYXGETY : '. print_r($data,true));
        } else {
            $this->_logger->debug('debug BUYXGETY : '. $data);
        }

    }

    /**
     * removes empty values from an array - useful when using explode()
     *
     * @param array $array
     * @return array
     */
    private function cleanArray($array)
    {
        foreach ($array as $key => $value) {
            if (empty($value)) {
               unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * isUnique check if array as unique values
     *
     * @param array $array
     * @return boolean
     */
    private function isUnique($array)
	{
        return (array_unique($array) != $array);
	}

}
