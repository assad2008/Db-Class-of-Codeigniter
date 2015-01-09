<?php
/**
* @file index.php
* @synopsis  CI db
* @author Yee, <rlk002@gmail.com>
* @version 1.0
* @date 2015-01-09 15:17:00
*/

define('IN_GM', TRUE);
define('GM_ROOT', '');  //root path

function database($params = '', $return = TRUE, $active_record = NULL)
{
	require_once(GM_ROOT.'/database/DB.php');
	if ($return === TRUE)
	{
		return DB($params, $active_record);
	}
	$db = null;
	$db =& DB($params, $active_record);
	return $db;
}

$config = array();
$configdb['hostname'] = 'localhost';
$config['username'] = '';
$config['password'] = '';
$config['database'] = '';
$config['dbdriver'] = 'mysqli';
$config['dbprefix'] = '';
$config['pconnect'] = TRUE;
$config['db_debug'] = TRUE;
$config['cache_on'] = FALSE;
$config['cachedir'] = '';
$config['char_set'] = 'utf8';
$config['dbcollat'] = 'utf8_general_ci';
$config['swap_pre'] = '';
$config['autoinit'] = TRUE;
$config['stricton'] = FALSE;

$db = database($config, $return = TRUE, $active_record = True);

