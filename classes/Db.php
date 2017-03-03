<?php
// Class managing all SQL DB errands
class	Db {

	// Constructor AKA __construct()
	function __construct() {
	}

	// Destructor AKA __destruct()
	function __destruct() {
	}

	// connect to foo DB
	function foo() {
		$host = '127.0.0.1' ;
		$username = 'root' ;
		$password = 'root' ;
		$db = 'foo' ;
		$ms_con = mysqli_connect($host, $username, $password, $db) ;
		if ( ! $ms_con ) {
			die('500 Database Offline: Please try again later') ;
		}
		return $ms_con ;
	}

};

?>
