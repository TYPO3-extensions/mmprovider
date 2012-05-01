<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_mmprovider_selection'] = array(
	'ctrl' => $TCA['tx_mmprovider_selection']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,description,source,mm,target,tablenames,ident'
	),
	'feInterface' => $TCA['tx_mmprovider_selection']['feInterface'],
	'columns' => array(
		't3ver_label' => array(
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim',
			)
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.description',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'source' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.source',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.source.I.0', '0'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.source.I.1', '1'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.source.I.2', '2'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.source.I.3', '3'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'mm' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.mm',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.mm.I.0', '0'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.mm.I.1', '1'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.mm.I.2', '2'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.mm.I.3', '3'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'target' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.target',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.target.I.0', '0'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.target.I.1', '1'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.target.I.2', '2'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.target.I.3', '3'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'selection' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.selection',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'tablenames' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.tablenames',
			'config' => array(
				'type' => 'radio',
				'items' => array(
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.tablenames.I.0', 'none'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.tablenames.I.1', 'source'),
					array('LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.tablenames.I.2', 'target'),
				),
			)
		),
		'ident' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mmprovider/locallang_db.xml:tx_mmprovider_selection.ident',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, description;;;;3-3-3, source, mm, target, tablenames, ident')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>