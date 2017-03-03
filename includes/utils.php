<?php
// class	QueryKeys {
// 	public	$predefined;

// 	// Constructor AKA __construct()
// 	function __construct() {
// 		$this->predefined = array();
// 		$this->predefined['ALL_PRODUCTS'] = 'SELECT * FROM products';
// 	}
	
// 	// Destructor AKA __destruct()
// 	function __destruct() {
// 	}

// 	// get query from our $predefined
// 	function	get_query( $key ) {
// 		return $this->predefined[$key];
// 	}

// };

function key_to_query( $key ) {
	$predefined = array() ;
	$predefined['ALL_PRODUCTS'] = 'SELECT * FROM products' ;
	// more sql commands can be tied to a key here if needed

	if ( isset($predefined[$key]) ) {
		return $predefined[$key] ;
	}
	return false ;
}

// Parameter $output expects an object returned
// from mysqli_query(). 
// Function simply stores each row into an ordered
// array and return the array if successful. Else
// it returns false
function	sql_output_to_array( $output ) {
	if ( $output ) {
		$row_count = mysqli_num_rows( $output ) ;
		if ( $row_count > 0 ) {
			$converted = array() ;
		    while ( $row = mysqli_fetch_array($output) ) {
				$converted[] = $row ;
			}
			return $converted ;
		}
	}
	return false ;
}

?>
