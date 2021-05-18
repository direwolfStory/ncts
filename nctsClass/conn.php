<?php
class connect
{
	/*private $host="localhost";
	private $user="root";
	private $pass="root";
	private $db="chat";*/
	private $host="45.130.228.52";
	private $user="u381699329_ncts";
	private $pass="nctsComputer18";
	private $db="u381699329_ncts";
	
	protected function conn()
	{
		$con = new mysqli($this->host,$this->user,$this->pass,$this->db) or die ('not connect database');
		//$con->set_charset("utf8");
		return $con;		
	}
	
	public function query($sql)
	{
		return mysqli_query($this->conn(),$sql);
	}

}



?>