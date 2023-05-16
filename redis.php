<?php

// TABLE NAME == 'products' IN DATABASE foo
// +------------+-------------------------+-------+
// | product_id | product_name            | price |
// +------------+-------------------------+-------+
// |          1 | Virtual Private Servers |     5 |
// |          2 | Managed Databases       |    15 |
// |          3 | Block Storage           |    10 |
// |          4 | Managed Kubernetes      |    60 |
// |          5 | Load Balancer           |    10 |
// +------------+-------------------------+-------+

// function mysql_connect() {
// 	$host = "127.0.0.1" ;
// 	$username = "root" ;
// 	$password = "root" ;
// 	$db = "foo" ;
// 	$ms_con = mysqli_connect($host, $username, $password, $db) ;
// 	if ( ! $ms_con ) {
// 		die('500 Database Offline: Please try again later');
// 	}
// 	return $ms_con;
// }
	Establish conn to mysql server
	$host = "127.0.0.1" ;
	$username = "root" ;
	$password = "root" ;
	$db = "foo" ;
	$ms_con = mysqli_connect($host, $username, $password, $db) ;
	if ( ! $ms_con ) {
		die('500 Database Offline: Please try again later') ;
	}

	// Establish conn to redis server
	$rd_con = new Redis() ;
	$rd_con->auth('root') ;	// Redis Server Password
	$success = $rd_con->connect('127.0.0.1', 6379) ;
	if ( ! $success ) {
		$redis_down = true ;
	}

	// Process Request to "get all products"
	$query  = "SELECT * FROM products";
	$fetched_from = 'MySQL Server';
	$r_key_all_products = 'ALL_PRODUCTS';
	$output = false;
	if( isset( $redis_down ) ) {
		// Redis server not reachable. Use MySQL and return
		$output = mysqli_query($ms_con, $query);
	} else {
		if ( $redis->get($r_key_all_products) ) {
			// This Key already present in Redis. Retrieve it and return
			$fetched_from = 'Redis Server';
			$output = unserialize($redis->get($r_key_all_products));
		}
		else {
			// This Key is not present in Redis. ... CONTINUE FROM HERE

		}

	}

// 

?>
