<?php

// use this as $magento path if we can't find the proper magento path
$default_path       = "/var/www/psb";

$cd_paths           = array(
  'app'         => '%magento%/app',
  'local'       => '%magento%/app/code/local',
  'community'   => '%magento%/app/code/community',

  'design'      => '%magento%/app/design/frontend/%edition%/%theme%',
  'theme'       => '%magento%/app/design/frontend/%edition%/%theme%',
  'template'    => '%magento%/app/design/frontend/%edition%/%theme%/template',
  'layout'      => '%magento%/app/design/frontend/%edition%/%theme%/layout',

  'etc'         => '%magento%/app/etc',
  'modules'     => '%magento%/app/etc/modules',

  'log'         => '%magento%/var/log',
  'report'      => '%magento%/var/report',

  'skin'        => '%magento%/skin/frontend/%edition%/%theme%',
  'css'         => '%magento%/skin/frontend/%edition%/%theme%/css',
  'images'      => '%magento%/skin/frontend/%edition%/%theme%/images',

  'locale'      => '%magento%/app/locale/en_US',
  'email'       => '%magento%/app/locale/en_US/template/email',

  'media'       => '%magento%/media',
  'images'      => '%magento%/media/catalog',
  'product'     => '%magento%/media/catalog/product',
  
  'root'        => '%magento%/',
  '-'           => '%magento%/',

  // external paths for easier navigation
  'sb'          => '/var/www/sb',
  'psb'         => '/var/www/psb',
  'enp'         => '/var/www/enp',
  'll'          => '/var/www/ll',
  'utils'       => '/var/www/utils/magento',
);

