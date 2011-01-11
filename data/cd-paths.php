<?php

// use this as $magento path if we can't find the proper magento path
// TODO: this needs to be refactored out
$default_path       = "/var/www/ll";

// places where projects may exist
$project_paths      = array(
    "/var/www", "/var/www/utils"
);

// paths within a project
$cd_paths           = array(
  'app'         	=> '%magento%/app',
  'local'       	=> '%magento%/app/code/local',
  'community'   	=> '%magento%/app/code/community',

  'design'      	=> '%magento%/app/design/frontend/%edition%/%theme%',
  'theme'       	=> '%magento%/app/design/frontend/%edition%/%theme%',
  'template'    	=> '%magento%/app/design/frontend/%edition%/%theme%/template',
  'layout'      	=> '%magento%/app/design/frontend/%edition%/%theme%/layout',

  'tbase'       	=> '%magento%/app/design/frontend/base/default/template',
  'templatebase'    => '%magento%/app/design/frontend/base/default/template',
  'lbase'           => '%magento%/app/design/frontend/base/default/layout',
  'layoutbase'      => '%magento%/app/design/frontend/base/default/layout',

  'etc'         	=> '%magento%/app/etc',
  'modules'     	=> '%magento%/app/etc/modules',

  'var'         	=> '%magento%/var',
  'log'         	=> '%magento%/var/log',
  'logs'        	=> '%magento%/var/log',
  'report'      	=> '%magento%/var/report',
  'backups'      	=> '%magento%/var/backups',

  'skin'        	=> '%magento%/skin/frontend/%edition_package%/%theme%',
  'css'         	=> '%magento%/skin/frontend/%edition_package%/%theme%/css',
  'images'      	=> '%magento%/skin/frontend/%edition_package%/%theme%/images',

  'locale'      	=> '%magento%/app/locale/en_US',
  'email'       	=> '%magento%/app/locale/en_US/template/email',
  'emails'      	=> '%magento%/app/locale/en_US/template/email',

  'media'       	=> '%magento%/media',
  'images'      	=> '%magento%/media/catalog',
  'product'     	=> '%magento%/media/catalog/product',
  
  'root'        	=> '%magento%/',
  '-'           	=> '%magento%/',
  'lib'           	=> '%magento%/lib',
);
