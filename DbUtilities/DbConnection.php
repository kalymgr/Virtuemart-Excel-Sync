<?php
class DbConnection
{
	
	private $link;
	private $db;
	
	public function set_MSSQL_Connection($dbs, $un, $pwd, $db)
	{
		//orizei mia nea microsoft sql server syndesi
		$this->link=mssql_connect($dbs,$un,$pwd) or die("Could not connect to database");
		//echo "Connection established";
		$this->db = mssql_select_db($db);
		return $this->link;	
	}
	
	public function closeConnection($link)
	{//kleinei mia sql server syndesi
		mssql_close($link);
	}
	
	public function getLink(){
		//epistrefei to link mias syndesis
		return $this->link;
	} 
}
?>