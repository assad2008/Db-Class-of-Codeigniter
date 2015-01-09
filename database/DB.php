<?php 

if(!defined('IN_GM'))
{
	exit('Access Denied');
}


function &DB($params = '', $active_record_override = NULL)
{
		if (($dns = @parse_url($params)) === FALSE)
		{
			show_error('Invalid DB Connection String');
		}
		if(is_array($params))
		{
			$nparams  = array();
			$nparams['hostname'] = rawurldecode($params['host']);
			$nparams['username'] = rawurldecode($params['username']);
			$nparams['password'] = rawurldecode($params['password']);
			$nparams['path'] = $params['dbname'];
			$nparams['database'] = $params['dbname'];
			$nparams['port'] = $params['port'];
			$nparams['dbprefix'] = '';
			$nparams['char_set'] = $params['charset'];
			$nparams['pconnect'] = TRUE;
			$nparams['autoinit'] = TRUE;
			$nparams['dbdriver'] = 'mysqli';
		}

	// No DB specified yet?  Beat them senseless...
	if ( ! isset($nparams['dbdriver']) OR $nparams['dbdriver'] == '')
	{
		show_error('You have not selected a database type to connect to.');
	}

	// Load the DB classes.  Note: Since the active record class is optional
	// we need to dynamically create a class that extends proper parent class
	// based on whether we're using the active record class or not.
	// Kudos to Paul for discovering this clever use of eval()

	if ($active_record_override !== NULL)
	{
		$active_record = $active_record_override;
	}

	require_once(GM_ROOT.'./database/DB_driver.php');

	if ( ! isset($active_record) OR $active_record == TRUE)
	{
		require_once(GM_ROOT.'./database/DB_active_rec.php');

		if ( ! class_exists('CI_DB'))
		{
			eval('class CI_DB extends CI_DB_active_record { }');
		}
	}
	else
	{
		if ( ! class_exists('CI_DB'))
		{
			eval('class CI_DB extends CI_DB_driver { }');
		}
	}

	require_once(GM_ROOT.'./database/drivers/'.$nparams['dbdriver'].'/'.$nparams['dbdriver'].'_driver.php');

	// Instantiate the DB adapter
	$driver = 'CI_DB_'.$nparams['dbdriver'].'_driver';
	$DB = new $driver($nparams);

	if ($DB->autoinit == TRUE)
	{
		$DB->initialize();
	}

	if (isset($nparams['stricton']) && $nparams['stricton'] == TRUE)
	{
		$DB->query('SET SESSION sql_mode="STRICT_ALL_TABLES"');
	}

	return $DB;
}
