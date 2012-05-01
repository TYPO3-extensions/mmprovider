<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::allowTableOnStandardPages('tx_mmprovider_selection');

$TCA['tx_mmprovider_selection'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'default_sortby' => 'ORDER BY title',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_mmprovider_selection.png',
	),
);
?>