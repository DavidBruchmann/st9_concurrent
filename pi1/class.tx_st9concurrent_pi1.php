<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 studioneun <info@studioneun.de>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'concurrent user check' for the 'st9concurrent' extension.
 *
 * @author	studioneun <info@studioneun.de>
 * @package	TYPO3
 * @subpackage	tx_st9concurrent
 */
class tx_st9concurrent_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_st9concurrent_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_st9concurrent_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'st9_concurrent';	// The extension key.

	/**
	 * main function of the hook, called by felogin
	 *
	 * @return	int	concurrent status (1 = ok; -1 = too much logins)
	 */
	function hook() {
		$conf['timeout'] = $GLOBALS['TSFE']->TYPO3_CONF_VARS['FE']['lifetime']; //seconds
		$conf['timeout'] = $conf['timeout'] ? $conf['timeout'] : (10*60); //default 10 minutes inactive

		//user data
		$user = $GLOBALS['TSFE']->fe_user->user;
		//logged in users
		$concurrent = $this->getconcurrent($user, $conf);

		if ($concurrent > intval($user['tx_st9concurrent_concurrent'])) {
			$this->logout($user);
			$login = -1;
		} else {
			$login = 1;
		}

		return $login;
	}


	/**
	 * fetch number of current sessions for a fe-user
	 *
	 * @param	array	$user: user data
	 * @param	array	$conf: configuration array
	 * @return	int		number of active sessions
	 */
	function getconcurrent($user, $conf) {
		$fields = 'count(*)';
		$table = 'fe_sessions';
		$where[] = 'ses_userid=' . intval($user['ses_userid']);
		$where[] = 'ses_tstamp>=' . (time() - intval($conf['timeout']));
		$where = implode(' AND ', $where);
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, $table, $where);
		$concurrent = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$concurrent = $concurrent['count(*)'];
		return $concurrent;
	}


	/**
	 * force logout of an user
	 *
	 * @param	array	$user: user data
	 */
	function logout($user) {
		$GLOBALS['TSFE']->fe_user->logoff();
		$_POST['logintype'] = 'logout';
		$_POST['user'] = $user['username'];
		$_POST['pass'] = '';
		$_POST['no_cache'] = '1';
		$GLOBALS['TSFE']->initFEUser();
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/st9_concurrent/pi1/class.tx_st9concurrent_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/st9_concurrent/pi1/class.tx_st9concurrent_pi1.php']);
}
?>