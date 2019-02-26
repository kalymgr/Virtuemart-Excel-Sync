<?php
class mysql_DbConnection
{
	
	private $link;
	private $db;
	
	public function set_mySQL_Connection($dbs, $un, $pwd, $db)
	{
		//orizei mia nea microsoft sql server syndesi
		$this->link=mysql_connect($dbs,$un,$pwd) or die("Could not connect to database");
		//echo "Connection established";
		$this->db = mysql_select_db($db);
		return $this->link;	
	}
	
	public function closeConnection($link)
	{//kleinei mia sql server syndesi
		mysql_close($link);
	}
	
	public function getLink(){
		//epistrefei to link mias syndesis
		return $this->link;
	} 
}
?>