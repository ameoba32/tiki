<?php
// Initialization

require_once('tiki-setup.php');

if($feature_custom_home != 'y') {
    $smarty->assign('msg',tra("This feature has been disabled"));
    $smarty->display('error.tpl');
    die;
}

// Display the template
$smarty->assign('mid','tiki-custom_home.tpl');
$smarty->display('tiki.tpl');
?>