<?php

$installer = $this;


// install by class
//$installer->installEntities();


// run DDL directly
$installer->startSetup();
$installer->run("
    ADD DDL STATEMENTS HERE
");
$installer->endSetup(); 

