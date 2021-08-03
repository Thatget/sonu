<?php
namespace Gaiterjones\BuyXGetY\Plugin;

	use Gaiterjones\BuyXGetY\Model\BuyXGetY;
    use Gaiterjones\BuyXGetY\Model\SpendXGetY;

class SalesQuoteMergeAfter
{
	
	protected $_buyxgety;
    protected $_spendxgety;

    public function __construct(
        BuyXGetY $buyxgety,
        SpendXGetY $spendxgety
    ) {
        $this->_buyxgety = $buyxgety;
        $this->_spendxgety = $spendxgety;
    }
		
    public function afterLoadCustomerQuote(
        \Magento\Checkout\Model\Session $subject,
        $result
    )
    {
        //$this->_buyxgety->log('BUYXGETY / checkout_cart_save_after Observer');
        $this->_buyxgety->CartUpdate();
        $this->_spendxgety->CartUpdate();
    }
}
