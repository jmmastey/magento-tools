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
class %CLASS% extends Mage_Eav_Model_Entity_Setup {

    /**
	 * Get any entities that we want installed. For array format,
	 * take a look at Mage_Catalog_Model_Resource_Mysql4_Setup.
	 *
	 * @return array
	 */
	public function getDefaultEntities() {
        return array(
            'catalog_product'					=> array(
                'entity_model'					=> 'catalog/product',
                'attribute_model'   			=> 'catalog/resource_eav_attribute',
                'table'             			=> 'catalog/product',
				'additional_attribute_table'	=> 'catalog/eav_attribute',
				'entity_attribute_collection'	=> 'catalog/product_attribute_collection',
                'attributes'        			=> array(    
                    'new_attribute_name'			=> array(   
                        // attribute data
                    ),
                ),
            ),
        );
    }//end getDefaultEntities
  
}//class %CLASS%
