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
 * Implementation of the MM provider Data Provider
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_mmprovider
 *
 * $Id$
 */
class Tx_Mmprovider_Services_Provider extends tx_tesseract_providerbase {

	/**
	 * Returns the type of data structure that the Data Provider can prepare
	 *
	 * @return string Type of the provided data structure
	 */
	public function getProvidedDataStructure() {
		return tx_tesseract::IDLIST_STRUCTURE_TYPE;
    }

	/**
	 * Indicates whether the Data Provider can create the type of data structure requested or not
	 *
	 * @param string $type Type of data structure
	 * @return boolean TRUE if it can handle the requested type, FALSE otherwise
	 */
	public function providesDataStructure($type) {
		if ($type == tx_tesseract::IDLIST_STRUCTURE_TYPE) {
			$result = TRUE;
		} else {
			$result = FALSE;
		}
		return $result;
    }

	/**
	 * Returns the type of data structure that the Data Provider can receive as input
	 *
	 * @return string Type of used data structures
	 */
	public function getAcceptedDataStructure() {
		return tx_tesseract::IDLIST_STRUCTURE_TYPE;
    }

	/**
	 * Indicates whether the Data Provider can use as input the type of data structure requested or not
	 * This particular provider does not accept anything
	 *
	 * @param string $type Type of data structure
	 * @return boolean TRUE if it can use the requested type, FALSE otherwise
	 */
	public function acceptsDataStructure($type) {
		return FALSE;
    }

	/**
	 * This method assembles the data structure and returns it
	 *
	 * @return array standardised data structure
	 */
	public function getDataStructure() {
		if ($this->getEmptyDataStructureFlag()) {
			$this->initEmptyDataStructure($this->providerData['target'], tx_tesseract::IDLIST_STRUCTURE_TYPE);
			$structure = $this->outputStructure;
		} else {
			$structure = $this->assembleStructure();
		}
		return $structure;
    }

	/**
	 * Assembles an id list-type data structure from the selected records
	 *
	 * @return array Id list-type data structure
	 */
	protected function assembleStructure() {
		$uids = array();
		$uidList = array();

			// Get the keys defined in the selection
		$keys = $this->calculateSelectionKeys();
			// Get all related keys (if any selection keys were returned)
		if (count($keys) > 0) {
			$relatedInformation = $this->getRelatedKeys($keys);
				// Loop on all found relations
			if (count($relatedInformation) > 0) {
				$uidsPerTable = array();
				$matchesPerItem = array();
					// Define in which sense the relation is used
				if ($this->providerData['uid_local'] == 'source') {
					$sourceField = 'uid_local';
					$targetField = 'uid_foreign';
				} else {
					$sourceField = 'uid_foreign';
					$targetField = 'uid_local';
				}
					// Sort all relations per table and per id of the "other" side
				foreach ($relatedInformation as $row) {
					$sourceId = $row[$sourceField];
					$targetId = $row[$targetField];
						// Get the table name and initialize some arrays, if needed
					$table = (empty($row['tablenames'])) ? $this->providerData['target'] : $row['tablenames'];
					if (!isset($uidsPerTable[$table])) {
						$uidsPerTable[$table] = array();
						$matchesPerItem[$table] = array();
					}
					if (!isset($matchesPerItem[$table][$targetId])) {
						$matchesPerItem[$table][$targetId] = array();
					}
					$uidsPerTable[$table][$targetId] = $targetId;
					$matchesPerItem[$table][$targetId][] = $sourceId;
				}
					// If the items should match all selected keys ("AND" logical operator chosen)
					// perform some post-process filtering, because such a condition
					// cannot be expressed simply in the SELECT query
				if ($this->providerData['logical_operator'] == 'and') {
					foreach ($matchesPerItem as $table => $tableRows) {
						foreach ($tableRows as $uidfromTarget => $uidfromSource) {
								// Check if all chosen keys are matched by keys found per item
							$rest = array_diff($keys, $uidfromSource);
								// At least one tag was not matched,
								// remove item from list
							if (count($rest) > 0) {
								unset($uidsPerTable[$table][$uidfromTarget]);
							}
						}
					}
				}
					// Now assemble the list of uid keys as needed for the data structure
				foreach ($uidsPerTable as $table => $listOfIds) {
					$uids = array_merge($uids, $listOfIds);
					foreach ($listOfIds as $id) {
						$uidList[] = $table . '_' . $id;
					}
				}
			}
		}

			// Assemble the data structure and return it
		$numberOfRecords = count($uidList);
		$dataStructure = array(
			'uniqueTable' => $this->providerData['target'],
			'uidList' => implode(',', $uids),
			'uidListWithTable' => implode(',', $uidList),
			'count' => $numberOfRecords,
			'totalCount' => $numberOfRecords,
		);
		return $dataStructure;
	}

	/**
	 * Parses the selection configuration and returns a list of uid keys
	 *
	 * This method also validates the existence of and the access to the selected keys
	 *
	 * @return array List of uid keys
	 */
	protected function calculateSelectionKeys() {
			// Parse the selection field
		$selection = tx_tesseract_utilities::parseConfigurationField($this->providerData['selection']);
			// Loop on the selection lines and evaluate them
		$selectionKeys = array();
		foreach ($selection as $selectionLine) {
				// Try evaluating the line, in case of failure issue a notice
			try {
				$selectionKeys[] = intval(tx_expressions_parser::evaluateExpression($selectionLine));
			}
			catch (Exception $e) {
				$this->getController()->addMessage(
					'mmprovider',
					'For line: ' . $selectionLine,
					'Empty selection',
					t3lib_FlashMessage::NOTICE
				);
			}
		}

			// If no keys were found eventually, issue warning
		if (count($selectionKeys) == 0) {
			$this->getController()->addMessage(
				'mmprovider',
				'The MM selection returned no result at all ',
				'Completely empty selection',
				t3lib_FlashMessage::WARNING,
				$this->providerData['selection']
			);

			// Check if the keys are indeed available (i.e. not deleted, hidden, etc., also taking user rights
			// in consideration)
		} else {
			$where = 'uid IN (' . implode(',', $selectionKeys) . ')';
				// Add where clause depending on context
			if (TYPO3_MODE == 'BE') {
				$where .= t3lib_BEfunc::deleteClause($this->providerData['source']);
			} else {
				$where .= $GLOBALS['TSFE']->sys_page->enableFields($this->providerData['source']);
			}
			$databaseResult = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', $this->providerData['source'], $where, '', '', '', 'uid');
				// If the query returned no results, issue warning
			if (count($databaseResult) == 0) {
				$this->getController()->addMessage(
					'mmprovider',
					'The MM selection was found empty after verification with the database',
					'Empty validated selection',
					t3lib_FlashMessage::WARNING,
					$GLOBALS['TYPO3_DB']->SELECTquery('uid', $this->providerData['source'], $where)
				);

				// If the query returned at least one row, replace the previously calculated selection
				// by the values returned from the database
			} else {
				$selectionKeys = array_keys($databaseResult);
				$this->getController()->addMessage(
					'mmprovider',
					sprintf('The MM selection returned %d keys', count($selectionKeys)),
					'Selected keys',
					t3lib_FlashMessage::OK,
					$selectionKeys
				);
			}
		}
		return $selectionKeys;
	}

	/**
	 * Gets all MM records that match the list of given keys
	 *
	 * @param array $keys List of uid keys (either uid_local or uid_foreign depending on provider settings)
	 * @return array MM relations built with the given keys
	 */
	protected function getRelatedKeys(array $keys) {
		$relatedInformation = array();
			// Early return if there are no keys (this should not happen)
		if (count($keys) == 0) {
			return $relatedInformation;
		}

			// Define which relation field fills which role depending on the side we tackle the relation from
		if ($this->providerData['uid_local'] == 'source') {
			$conditionField = 'uid_local';
		} else {
			$conditionField = 'uid_foreign';
		}
		$where = $conditionField . ' IN (' . implode(', ', $keys) . ')';

			// Get all fields from the MM table
		$fields = $GLOBALS['TYPO3_DB']->admin_get_fields($this->providerData['mm']);

			// Add a condition on tablenames, if defined
		if ($this->providerData['tablenames'] != 'none' && isset($fields['tablenames'])) {
			$where .= ' AND tablenames = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr(
				$this->providerData[$this->providerData['tablenames']],
				$this->providerData['mm']
			);
		}
			// Add a condition on ident, if defined
		if (!empty($this->providerData['ident']) && isset($fields['ident'])) {
			$where .= ' AND ident = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr(
				$this->providerData['ident'],
				$this->providerData['mm']
			);
		}

			// Define the fields to select
		$select = 'uid_local, uid_foreign';
			// Add the tablenames field, if it exists in the table
		if (isset($fields['tablenames'])) {
			$select .= ', tablenames';
		}
			// Define the sorting
			// If taking the relation from the foreign side and the sorting_foreign key exists, use it,
			// otherwise just use the sorting field
		if ($this->providerData['uid_local'] == 'target' && isset($fields['sorting_foreign'])) {
			$sorting = 'sorting_foreign ASC';
		} else {
			$sorting = 'sorting ASC';
		}
			// Execute the query
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $this->providerData['mm'], $where, '', $sorting);
			// If the query failed, report about it
		if ($res == FALSE) {
			$this->getController()->addMessage(
				'mmprovider',
				'The SQL query returned the following error: ' . $GLOBALS['TYPO3_DB']->sql_error(),
				'Error getting related keys',
				t3lib_FlashMessage::WARNING,
				$GLOBALS['TYPO3_DB']->SELECTquery($select, $this->providerData['mm'], $where, '', $sorting)
			);

			// If the query was successful, get all results and return them
		} else {
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$relatedInformation[] = $row;
			}
		}
		return $relatedInformation;
	}

	/**
     * Returns an empty array instead of a list of fields, since it returns only primary keys
     *
     * @param string $language 2-letter iso code for language
	 * @param string $table Name of a specific table to fetch the information for
     * @return array List of tables and fields
     */
	public function getTablesAndFields($language = '', $table = '') {
		return array();
    }
}
?>