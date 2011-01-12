<?php

$installer = $this;


// install by class. you will probably
// need to define your setup class in the config
// file and have that class descend from
// eav/entity_setup
//$installer->installEntities();


// run DDL directly
$installer->startSetup();
/*$installer->run("
    ADD DDL STATEMENTS HERE
");*/
$installer->endSetup(); 
                                                                                          
