<?php
	//administrator user/pass
	define('ADMIN_USER', 'admin');
	define('ADMIN_PASS', 'pass');
	//system admin user/pass
	define('SYSADMIN_USER', 'sysadmin');
	define('SYSADMIN_PASS', 'syspass');
	//user/pass into array
	$user_array = array(0 => userinit,1 => SYSADMIN_USER, 2 => ADMIN_USER);
	$pw_array = array(0 => passinit,1 => SYSADMIN_PASS, 2 => ADMIN_PASS);
	//generate keys
	$user_key = array_search($_SERVER['PHP_AUTH_USER'], $user_array);
	$pw_key = array_search($_SERVER['PHP_AUTH_PW'], $pw_array);
	//authenticate with headers
	if(	!isset($_SERVER['PHP_AUTH_USER']) ||
		!isset($_SERVER['PHP_AUTH_PW']) || 
		($user_key !== $pw_key) ||
		(!$user_key) || (!pw_key)
	  )
		{
		//incorrect, send authentication
		header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="FLADPS Admin"');
		exit('<h3>FLA:DPS Database Administration</h3><p>You must enter a valid user name and password to access this page.</p>');
	}
?>