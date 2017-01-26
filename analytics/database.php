<?php

require __DIR__ . '/schema.php';

libxml_use_internal_errors(true);

// EDIT THE CONNECTION STUFFS

$db_host = "localhost";
$db_name = "something";
$db_user = "root";
$db_pass = "password";

// END

$db = "";



class safeMysqli extends mysqli {
	public $connected;
	//public $db;

	public function __construct($host, $username, $password, $dbname) { // db info
		//$conn = new mysqli($host, $username, $password, $dbname);
		parent::__construct($host, $username, $password, $dbname);

		if (!mysqli_connect_errno()) {
			$this->connected = true;
			//$this->db = $conn;
		}
		else $this->connected = false;
		
	}

	public function safeClose() {
		if ($this->connected) {
			mysqli_close($this);
			$this->connected = false;
		}
	}
}





function safeOpen()
{
	global $db;
	global $db_host;
	global $db_name;
	global $db_user;
	global $db_pass;

	if(is_resource($db) && $db->connect_errno == 0 && $db->connected && $db->ping() )
	{
		// everything fine
	}
	else 
	{
		$db = new safeMysqli($db_host, $db_user, $db_pass, $db_name);
		if($db->connect_errno > 0) {
			die('Connection failed [' . $db->connect_error . ']');
		}
		
	}
	return $db;
}

function buildDb($schema)
{	
	$db = safeOpen();
	for($i=0; $i<count($schema); $i++)
	{	
		$db->query($schema[$i]);
	}
	$db->commit();
	$db->safeClose();
}

function fixTime($column,$value)
{
	
	
	if(strtolower($column)=="timestamp")
	{
		$stub = 0;
		if(strpos($value,".") !== false)
		{
			
			$stub = (int) findCodes($value,".","+");

		}
		return strtotime($value)+($stub/1000);
	}
	if(strtolower($column)=="keyword")
	{
		return trim(strtolower($value));
	}
	return $value;
}


function insertCommit($rawdata,$table,$mappings,$interval = 500, $close = true)
{
	$db = safeOpen();
	$counter = 0;
	
	foreach($rawdata as $key => $values)
	{
		
		$beginning = "INSERT INTO `".$table."` (";
		$end = ") VALUES (";
		foreach($mappings as $column => $field)
		{
			
			$beginning.= "`".$column."`, ";
			if(array_key_exists($field,$values))
			{
				$end.= "\"".mysqli_real_escape_string($db,fixTime($column,$values[$field]))."\", ";
			}
			else
			{
				$end.= "\"".mysqli_real_escape_string($db,$field)."\", ";
			}
		}
		$query = substr($beginning, 0, -2).substr($end, 0, -2).")";
		
		$db->query($query);
		if(($counter % $interval == 0) && $counter > 0)
		{
			$db->commit();
		}
		$counter++;
	}
	$db->commit();
	if($close)
	{
		$db->safeClose();
	}
}







// Table definitions (ecommerce to follow)



//build DB Table.



?>
