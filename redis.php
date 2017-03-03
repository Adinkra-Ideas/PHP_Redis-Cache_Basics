<?php
	require './classes/Db.php' ;
	require './classes/Bootstrap.php' ;
	require './includes/utils.php' ;

	// Instantiate DB object
	$db = new Db() ;
	
	// New foo DB. Bootstrap 'products' Table
	$bootstrap = new Bootstrap() ;
	$bootstrap->products_table( $db->foo(), 'products' ) ;

	// Establish conn to redis server
	// Man at https://github.com/phpredis/phpredis
	$redis = new Redis() ;
	try {
		$redis->connect( '127.0.0.1', 6379 ) ;
		// $redis->auth('root') ;	// Redis Server Password if_isset
	} catch ( RedisException $e ) {
		$redis_away = true ;
	}

	// Process (and cache) a SQL Request
	$fetched_from = 'MySQL Server' ;
	$key = 'ALL_PRODUCTS' ;
	$query_all_products = key_to_query( $key );
	if( isset($redis_away) ) {
		// Redis server not reachable. Use MySQL and return
		$output = mysqli_query( $db->foo(), $query_all_products ) ;
		$converted = sql_output_to_array( $output ) ;
	} else {
		if ( $redis->get($key) ) {
			// This Key already present in Redis's cache. Retrieve it and return
			$fetched_from = 'Redis Server' ;
			$converted = unserialize( $redis->get($key) ) ;
		}
		else {
			// Redis Server available but this Key not yet present in Redis' cache.
			// Retrieve from MYSQL, save copy to Redis's cache, and return
			$output = mysqli_query( $db->foo(), $query_all_products ) ;
			$converted = sql_output_to_array( $output ) ;
			$redis->set( $key, serialize($converted) ) ;
			$redis->expire( $key, 10 ) ;	// this key will expire from Redis' cache after 10 seconds
			echo 'Key set to Redis Cache !' ;	// for testing purposes :)
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Redis</title>
</head>
	<style type="text/css">
		table{
		padding: 0;
		background-color: #dedede;
		color: black;
		}
		table tr td{
		padding: 5px;
		border-right: 1px solid red;
		width: 20em;
		}
		table tr td:last-child{
		border-right: none;
		}
	</style>
<body>

	<?php
		if ( isset($converted) && $converted )
		{
			echo "<h1>Fetched From $fetched_from</h1>";
			foreach ( $converted as $row ) {
				$product_id = $row['product_id'] ;
				$product_name =   $row['product_name'];
				$price =   $row['price'];
				echo '<table ><tr><td> ' . $product_id . ' </td>' . '<td> ' . $product_name . ' </td> ' . '<td> ' . $price . ' </td></tr></table> ';
			}
		}
	?>
</body>
</html>
