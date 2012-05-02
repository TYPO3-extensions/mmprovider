<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::allowTableOnStandardPages('tx_mmprovider_selection');

$TCA['tx_mmprovider_selection'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:mmprovider/Resources/Private/Language/locallang_db.xml:tx_mmprovider_selection',
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/MmProvider.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Images/icon_tx_mmprovider_selection.png',
	),
);

	// Register mmprovider as a secondary Data Provider
t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'] .= ',tx_mmprovider_selection';

	// Add a wizard for adding a mmprovider
$addMmProviderWizard = array(
	'type' => 'script',
	'title' => 'LLL:EXT:mmprovider/Resources/Private/Language/locallang_db.xml:wizards.add_mmprovider',
	'script' => 'wizard_add.php',
	'icon' => 'EXT:mmprovider/Resources/Public/Images/add_mmprovider_wizard.png',
	'params' => array(
		'table' => 'tx_mmprovider_selection',
		'pid' => '###CURRENT_PID###',
		'setValue' => 'append'
	)
);
$TCA['tt_content']['columns']['tx_displaycontroller_provider2']['config']['wizards']['add_mmprovider'] = $addMmProviderWizard;
?>