<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

	// Register as Data Provider service
	// Note that the subtype corresponds to the name of the database table
t3lib_extMgm::addService($_EXTKEY,  'dataprovider' /* sv type */,  'tx_mmprovider_selection' /* sv key */,
	array(
		'title' => 'MM-tables Provider',
		'description' => 'Data Provider based on selections from MM relations',

		'subtype' => 'tx_mmprovider_selection',

		'available' => TRUE,
		'priority' => 50,
		'quality' => 50,

		'os' => '',
		'exec' => '',

		'classFile' => t3lib_extMgm::extPath($_EXTKEY, 'Classes/Services/Provider.php'),
		'className' => 'Tx_Mmprovider_Services_Provider',
	)
);
?>