<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_st9concurrent_concurrent' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:st9_concurrent/locallang_db.xml:fe_users.tx_st9concurrent_concurrent',
		'config' => array (
			'type'     => 'input',
			'size'     => '4',
			'max'      => '4',
			'eval'     => 'int',
			'checkbox' => '0',
			'range'    => array (
				'upper' => '1000',
				'lower' => '0'
			),
			'default' => 1
		)
	),
);


t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('fe_users', 'tx_st9concurrent_concurrent;;;;1-1-1');

?>