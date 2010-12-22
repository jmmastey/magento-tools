<?php

// use this as $magento path if we can't find the proper magento path
$default_path       = "/var/www/ll";

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

  // external paths for easier navigation
  'sb'          	=> '/var/www/sb',
  'psb'         	=> '/var/www/psb',
  'enp'         	=> '/var/www/enp',
  'll'          	=> '/var/www/ll',
  'utils'       	=> '/var/www/utils/magento',
);

