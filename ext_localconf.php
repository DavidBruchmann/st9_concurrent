<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['st9_concurrent'] = 'EXT:st9_concurrent/pi1/class.tx_st9concurrent_pi1.php:&tx_st9concurrent_pi1->hook';

?>