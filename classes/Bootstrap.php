<?php
class	Bootstrap {
	
	// Constructor AKA __construct()
	function __construct() {
	}
	
	// Destructor AKA __destruct()
	function __destruct() {
	}

	// PRIMARY KEY: is used for sorting z.B (SELECT * FROM products ORDER BY product_id DESC). Value in each row has to be unique'.
	// INDEX: MYSQL uses INDEX as the 'node key' for storing each row into its Red Black Tree binary storage. This means INDEX
	// has to be unique (z.B. usernames), and search requests(AKA 'SELECT *') should be done using INDEX for faster searches.
	function products_table( $db, $table_name ) {
		$query = 'SELECT 1 FROM ' . $table_name . ' LIMIT 1' ;
		$output = mysqli_query( $db, $query ) ;
		if( $output === FALSE ) // we create table
		{
			$query = 'CREATE TABLE ' . $table_name . ' (
				product_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				product_name VARCHAR(64),
				price INT UNSIGNED,
				INDEX(price)
			)' ;
			$output = mysqli_query( $db, $query ) ;

			if ( $output ) {
				// Populate the 'products' Table with dummy product rows
				$query_arrays = array() ;
				$query_arrays[] = "INSERT INTO products (product_name, price) VALUE ('Virtual Private Servers', 5)" ;
				$query_arrays[] = "INSERT INTO products (product_name, price) VALUE ('Managed Databases', 15)" ;
				$query_arrays[] = "INSERT INTO products (product_name, price) VALUE ('Block Storage', 10)" ;
				$query_arrays[] = "INSERT INTO products (product_name, price) VALUE ('Managed Kubernetes', 60)" ;
				$query_arrays[] = "INSERT INTO products (product_name, price) VALUE ('Load Balancer', 10)" ;
				foreach ( $query_arrays as $query ) {
					$output = mysqli_query( $db, $query ) ;
				}
			}
		}
		return $output;
	}

};

?>
