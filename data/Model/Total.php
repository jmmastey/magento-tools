<?php

/**
 * %FILENAME%
 * 
 * Class definition file
 * 
 * @author      %AUTHOR%
 * @author      $Author$
 * @version     $Id$
 * @package     %PACKAGE%
 * @subpackage  %PLUGIN%
 * @copyright	Copyright (c) %COPYRIGHT%
 */

/**
 * %DESCRIPTION%
 *
 * @author      %AUTHOR%
 * @author      $Author$
 * @package     %PACKAGE%
 * @subpackage  %PLUGIN%
 * @version     $Id$
 * @copyright	Copyright (c) %COPYRIGHT%
 * 
 * @name        %CLASS%
 *
 */
class %CLASS% extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    protected $_totalCode = 'ADD_CODE_HERE';
    protected $_label = 'LABEL';
    protected $_charge = 0;

    public function __construct(){
        $this->setCode($_totalCode);
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
    	// Only display for the shipping address quote
        if($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_BILLING) {
        	return $this;
        }
        
        if ($this->_charge != 0) {
	        $address->addTotal(array(
	            'code'  => $this->getCode(),
	            'title' => Mage::helper('sales')->__($this->_label),
	            'value' => $this->_charge //This is for display only
	        ));
        }
       
        return $this; 
    }

    //This triggers right after the subtotal is calulated
    public function collect(Mage_Sales_Model_Quote_Address $address) {     	
        $charge = $this->_getCharge($address);

        $address->setSubtotal($address->getSubtotal() + $charge );
        $address->setBaseSubtotal($address->getBaseSubtotal() + $charge );

        //Then update the grandtotals
        $address->setGrandTotal($address->getSubtotal());
        $address->setBaseGrandTotal($address->getBaseSubtotal());
     
        return $this;
    }

    protected function _getCharge(Mage_Sales_Model_Quote_Address $address) {
        $items = $address->getAllItems();

        $this->_charge = 0;
        return $this->_charge;
    }
  
}//class %CLASS%
