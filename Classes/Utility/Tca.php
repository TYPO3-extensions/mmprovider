<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Francois Suter (Cobweb) <typo3@cobweb.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Helper class with methods related to TCA manipulation
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_mmprovider
 *
 * $Id$
 */
class Tx_Mmprovider_Utility_Tca {
	/**
	 * Fetches all MM tables from the database and puts them in the items array of the current TCA configuration
	 *
	 * @param array $configuration Field TCA configuration
	 * @return array Modified configuration
	 */
	public function listMmTables($configuration) {
		$items = array();
			// Get all tables from the database
		$tables = $GLOBALS['TYPO3_DB']->admin_get_tables();
			// Keep only tables that contain "_mm", assuming these are all MM tables
			// NOTE 1: this is rather rough and could lead to false positives
			// NOTE 2: we exclude the table from this very extension (tx_mmprovider_selection),
			// which is a known false positive
		foreach ($tables as $tableData) {
			if (strpos($tableData['Name'], '_mm') !== FALSE && $tableData['Name'] !== 'tx_mmprovider_selection') {
				$items[] = array($tableData['Name'], $tableData['Name']);
			}
		}

		$configuration['items'] = $items;
		return $configuration;
	}
}
?>