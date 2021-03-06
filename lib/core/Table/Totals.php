<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false) {
	header('location: index.php');
	exit;
}

/**
 * Class Table_Totals
 * This is a public class for checking necessary preferences or tablesorter status
 *
 * @package Tiki
 * @subpackage Table
 */
class Table_Totals
{
	/**
	 * @param array $s
	 * @param $count
	 * @return bool
	 */
	static public function getTotalsHtml(array $s, $count)
	{
		if (Table_Check::isEnabled()) {
			if (!empty($s['math'])) {
				$smarty = TikiLib::lib('smarty');
				$smarty->assign('fieldcount', $count);
				$smarty->assign('tstotals', $s['math']);
				return $smarty->fetch('tablesorter/totals.tpl');
			}
		} else {
			return false;
		}
	}

	/**
	 * @param array $s
	 */
	static public function setTotals(array $s)
	{
		if (Table_Check::isEnabled()) {
			if (!empty($s['math'])) {
				$smarty = TikiLib::lib('smarty');
				$smarty->assign('tstotals', $s['math']);
			}
		}
	}


}
