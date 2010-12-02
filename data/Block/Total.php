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
class %CLASS% extends Mage_Checkout_Block_Total_Default {

    // add a template that echoes a <tr><td>label</td><td>total</td></tr>
    // remember to use getColspan to get the columns right for checkout & cart
    protected $_template = "path/to/template.phtml";


    protected function _construct() {
        parent::_construct();
        $this->setTemplate($this->_template);
    }

    public function getTotal() {
        // grab the total charge
    }

}//class %CLASS%
