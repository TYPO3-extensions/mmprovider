<?php

########################################################################
# Extension Manager/Repository config file for ext "mmprovider".
#
# Auto generated 01-05-2012 12:37
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'MM-tables Provider (Tesseract project)',
	'description' => 'This Data Provider for Tesseract returns list of primary keys based on selection from MM tables.',
	'category' => 'misc',
	'author' => 'Francois Suter (Cobweb)',
	'author_email' => 'typo3@cobweb.ch',
	'shy' => '',
	'dependencies' => 'tesseract',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'tesseract' => '1.3.0-0.0.0',
			'typo3' => '4.5.0-4.6.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"9a9a";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"1bdc";s:14:"ext_tables.php";s:4:"003e";s:14:"ext_tables.sql";s:4:"c829";s:32:"icon_tx_mmprovider_selection.gif";s:4:"475a";s:16:"locallang_db.xml";s:4:"ccc3";s:7:"tca.php";s:4:"0163";s:19:"doc/wizard_form.dat";s:4:"bdfe";s:20:"doc/wizard_form.html";s:4:"c156";}',
);

?>