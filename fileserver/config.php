<?php

$FS_MAINFOLDER = "/fileserver/" ;
$FS_DBHOST = 'localhost';
$FS_DBUSERNAME = 'root';
$FS_DBPASSWORD = '';
$FS_DBNAME = 'fileserver';


class PMWRAP {
  var $DB_HOST = '' ;
	var $DB_USERNAME = '' ;
	var $DB_PASSWORD = '' ;	
	var $DB_PORT = 3306 ;
	var $DB_NAME = '' ;	
	var $DB_LINK ;
	var $DB_TIMEZONE = '+5:30' ;
	var $query_count = 0;
	var $DB_RESULT ;
	var $DB_LAST_INSERT_ID ;
	var $SQODC = 0 ; // should_quit_on_db_connectionFail
	var $effectedRows = 0;
	
	public function db_init(){
		try {
			$this->DB_LINK = new PDO( "mysql:host={$this->DB_HOST};port={$this->DB_PORT};dbname={$this->DB_NAME}" , $this->DB_USERNAME , $this->DB_PASSWORD );
		} catch (PDOException $e) {
			//echo 'Connection failed: ' . $e->getMessage();
			if($this->SQODC){
				exit();
			}else{
				return false;
			}
		}
		
		$this->DB_LINK->query( " SET time_zone = '{$this->DB_TIMEZONE}' " ) ;
		return true;
	}
	


	public function exequery($query){
		$this->effectedRows = 0 ;
		if( $this->query_count == 0 ){
			$this->db_init();
		}
		$this->query_count++ ;
		try{
			$this->DB_RESULT = $this->DB_LINK->query( $query ) ;
			if($this->DB_RESULT){
				$this->effectedRows = $this->DB_RESULT->rowCount();
			}else{
				$this->effectedRows = 0;
			}
		} catch (PDOException $e) {
			// print "Error!: " . $e->getMessage() . "\n Query: {$query} \n";
		}
		
		return $this->DB_RESULT ;
	}
	


	public function exequery_return_single_val($query){
		$res_array = $this->exequery_return_strict_array($query);
		return (count($res_array)==1 ) ? $res_array[0] : '' ;
	}
	


	public function exequery_return_strict_array($query){
		$vars = array();
		$this->exequery( $query );
		$result = $this->DB_RESULT->setFetchMode( PDO::FETCH_ASSOC );
		while( $row = $this->DB_RESULT->fetch() ){
			foreach($row as $key => $val){
				array_push($vars, $val);
			}
		}
		return $vars;
	}



	public function exequery_return_MultiAssocArray($query){
		$vars = array();		
		$this->exequery( $query );
		$result = $this->DB_RESULT->setFetchMode( PDO::FETCH_ASSOC );
		while( $row = $this->DB_RESULT->fetch() ){
			$vars[] = $row ;
		}
		return $vars;
	}
	


	public function exequery_return_single_AssocArray_select_pairs_of_key_vals($query){
		// ex: select username , emailid from tbl
		// returns array('chandu' => 'chandus@emailId' , 'sagar' => 'sagars@emailId'  )
		$result = $this->exequery_return_MultiAssocArray($query);
		if(!count($result)){ return array(); }
		
		$toReturn = array();
		$heads = array();
		if( count($result[0]) !=1 && count($result[0]) !=2  ){ return array(); } // ALERT: INVALID USE		
		
		$heads = array_keys($result[0]);
		if( count($heads) == 1 ){
			$heads[] = $heads[0] ;
		}

		foreach( $result as $this_row ){
			$toReturn[ $this_row[$heads[0]] ] = $this_row[ $heads[1] ] ;
		}
		return $toReturn ;
	}


	
	public function exequery_return_single_row_as_AssocArray($query){
		$result = $this->exequery_return_MultiAssocArray($query);
		return (count($result) == 1 ) ? $result[0] : array();
	}
	



	public function insert_row( $my_table , $my_array ){
		// Insert values into a MySQL database
	   //	execute_sqlInsert("tablename", array(col1=>$val1, col2=>$val2, col3=>"val3", col4=>720, col5=>834.987));
	   // Sends the following query:
	   //	INSERT INTO 'tablename' (col1, col2, col3, col4, col5) values ('foobar', 495, 'val3', 720, 834.987)
		if( $this->query_count == 0 ){
			$this->db_init();
			$this->query_count++ ;
		}
		
		$columns = array_keys( $my_array );
		$values = array_values( $my_array ); // Find all the values from the array $my_array
		$values_number = count($values); // quote_smart the values
		for ($i = 0; $i < $values_number; $i++){
			$value = $values[$i];
			if (get_magic_quotes_gpc()) { $value = stripslashes($value); }
			if (!is_numeric($value)){
				if( $value == "CURRENT_TIMESTAMP" ){ // we do not want "CURRENT_TIMESTAMP" in SQL, just CURRENT_TIMESTAMP 

				}else{
					$value = $this->DB_LINK->quote($value) ; 
				}
			}
			$values[$i] = $value;
		}

		// Compose the query
		$sql = "INSERT INTO $my_table ";
		$sql .= "( `" . implode("`, `", $columns) . "` )";
		$sql .= " values ";
		$sql .= "(" . implode(", ", $values) . ")";
		
		$result = $this->exequery($sql);
		$this->DB_LAST_INSERT_ID = $this->DB_LINK->lastInsertId(); 
		
	   return ($result) ? true : false;
	}





	
	public function update_table( $my_table ,  $update_array , $where_array ){
		// update values in table
	   //	execute_sqlUpdate("tablename", array(col1=>$val1, col2=>$val2) , array(col3=>"val3", col4=>720) );
	   // Sends the following query:
	   //	update 'tablename' set col1='$val1', col2='$val2' where col3='val3' and col4=720 ;
		if( $this->query_count == 0 ){
			$this->db_init();
			$this->query_count++ ;
		}
		
		$tmp_setArray = array();
		$tmp_whereArray = array();
		foreach ($update_array as $key => $value) {
			if (!is_numeric($value)){
				$value = $this->DB_LINK->quote($value) ; 
				$tmp_setArray[] = $key . "=" . $value ;
			}else{
				$tmp_setArray[] = $key . "=" . $value ;
			}
		}
		
		foreach ($where_array as $key => $value) {
			if(is_array($value)){ // 
				foreach($value as &$this_val){
					$this_val = $this->DB_LINK->quote($this_val) ; 
				}
				$tmp_whereArray[] = " ( {$key} = " . implode(" OR {$key}= " , $value). " )" ;
			}else{
				if (!is_numeric($value)){
					$value = $this->DB_LINK->quote($value) ; 
					$tmp_whereArray[] = $key . "=" . $value . "" ;
				}else{
					$tmp_whereArray[] = $key . "=" . $value ;
				}
			}
		}
		// Compose the query
		$sql = "update $my_table ";
		$sql .= "set " . implode(", ", $tmp_setArray ) . " " ;
		$sql .= " where " . implode(" and ", $tmp_whereArray ) ;
		
		$result = $this->exequery($sql);
		return ($result) ? true : false;
	}
	





	
	public function delete_from_tbl($my_table, $where_array){
		// delete all matching rows from table
	   //	delete_from_tbl("tablename", array(col1=>$val1, col2=>$val2) );
	   // Sends the following query:
	   //	delete from 'tablename' where col1='$val1', col2='$val2' ;
		if( $this->query_count == 0 ){
			$this->db_init();
			$this->query_count++ ;
		}
		
		$tmp_whereArray = array();
		foreach ($where_array as $key => $value) {
			if(is_array($value)){ // 
				$tmp_whereArray[] = " ( {$key} ='" . implode("' OR {$key}='", $value). "' )" ;
			}else{
				if (!is_numeric($value)){
					$tmp_whereArray[] = $key . "='" . $value . "' " ;
				}else{
					$tmp_whereArray[] = $key . "=" . $value ;
				}
			}
		}
		// Compose the query
		$sql = "delete from $my_table ";
		$sql .= " where " . implode(" and ", $tmp_whereArray ) ;

		$result = $this->exequery($sql);
		return ($result) ? true : false;
	}



}


$PMWRAP = new PMWRAP();
$PMWRAP->DB_HOST = $FS_DBHOST ;
$PMWRAP->DB_USERNAME = $FS_DBUSERNAME ;
$PMWRAP->DB_PASSWORD = $FS_DBPASSWORD ;
$PMWRAP->DB_NAME = $FS_DBNAME ;
$PMWRAP->db_init();


function getaRandomString($length){
	if(!$length){$length=16;}
	for ($s = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')-1; $i != $length; $x = rand(0,$z), $s .= $a{$x}, $i++);
	return $s;
}


function generate_newRandomFile(){
	global $given_file_parts ;
	global $UPload_ToThisFolder ;
	
	$random_filename = getaRandomString(32).".".$given_file_parts['extension'] ;
	$UPload_ToThisFile = $UPload_ToThisFolder ."/".$random_filename ;	
	
	if( file_exists( $UPload_ToThisFile ) ){
		$random_filename = generate_newRandomFile();
	}
	
	return $random_filename ;
}


?>