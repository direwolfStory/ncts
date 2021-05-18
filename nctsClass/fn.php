<?php
session_start();
$ckThis=1;
date_default_timezone_set("Asia/Bangkok");
include("conn.php");
class fn extends connect
{
	private $conn;
	private $sta;
 	private $token = 'OMb2UdOi3oE4jexM3pRhdslF5S+Ja1v89J2mkb089CnPSmNWIbzttLEvE7sBgrCkfJdZPVbDTgkmX5L0avaYLvdlNrcSqAs0DCqx5Ape7iuhjmBfBTwgfDWa/W334GzWxqv9C2k6QFo2mJTHqxewpFGUYhWQfeY8sLGRXgo3xvw=';
	
	public function __construct()
	{
		 $this->conn = $this->conn();
		//$this->sta=$_SESSION["sta"];
		 
	}

	public function sel($sql)
	{

		$q = $this->conn->query($sql);
		$num= mysqli_num_rows($q);
		$arr=array();		

		for($i=0;$i<$num;$i++)
		{
			$data=mysqli_fetch_object($q);
			$arr[$i]=$data;
		}

		return $arr;		
	}
	
	public function seljson($sql)
	{		
		$q=$this->conn->query($sql);
		$arr=array();
		$arrField=array();
		$num=mysqli_num_rows($q);
		while($f=mysqli_fetch_field($q)) //นำ field ในdatabase มาเก็บไว้ใน arrayField
		{
			array_push($arrField,$f->name); //push ชื่อfield มาใส่ใน arrayField
		}	
		
		$numField=count($arrField);
		
		while($data=mysqli_fetch_array($q)) // loop ข้อมูลทั้งหมดออกมา
		{
			$arrCol=array();
			for($i=0;$i<$numField;$i++)
			{
				$arrCol[$arrField[$i]]=$data[$i]; //นำข้อมูลแต่ละfiled มาใส่ใน filed ที่ถูกต้อง
			}
			array_push($arr,$arrCol);	
		}
				
		return json_encode($arr);// encode คืนค่าเป็นรูปแบบ json
	}
	
	
	public function query($sql)
	{
			$q=$this->conn->query($sql);
			if($q)
			{
				return $q;
			}else{
				echo " <script>alert('เกิดข้อผิดพลาดกรุณาลองอีกครั้ง หรือติดต่อผู้ดูแลระบบด้วยค่ะ');window.history.back();</script>";
				exit();	
			}		
	}
	
	
	public function queryAlert($sql,$success,$fail,$page)
	{
			if($this->conn->query($sql))
			{
				$this->msg($success,$page);
				exit();
			}else{
				$this->msg($fail,$page);
				exit();	
			}		
	}
	
	public function insertAll($tb)
	{
		$field;
		$values;
		$i=0;
		$num=count($_POST);
		foreach($_POST as $key=>$val)
		{
			if($key != "button")
			{
				if($i==0)
				{
					$field.=$key;
					$values.="'". $val . "'" ;
				}else{
					$field.="," . $key;
					$values.= "," ."'". $val . "'" ;
						
				}
				$i++;
			}
			
		}
		$sql="insert into $tb($field) values($values)";
		$q=$this->query($sql);
		if($q)
		{
			$this->msg("บันทึกข้อมุลเรียบร้อยแล้วค่ะ","index.php");	
		}else{
			$this->msg("เกิดข้อผิดพลาดไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบค่ะ","index.php");	
		}
	}
	
	public function insertSome($tb,$arr)
	{
		$field;
		$values;
		$i=0;
		$num=count($_POST);
		foreach($_POST as $key=>$val)
		{
			for($j=0;$j<count($arr);$j++)
			{
				$k=$arr[$j];
				if($key==$k)
				{
					
						if($i==0)
						{
							$field.=$key;
							$values.="'". $val . "'" ;
						}else{
							$field.="," . $key;
							$values.= "," ."'". $val . "'" ;
								
						}
					
					$i++;
				}
			}
			
			
		}
		$sql="insert into $tb($field) values($values)";
		
		$q=$this->query($sql);
		if($q)
		{
			$this->msg("บันทึกข้อมุลเรียบร้อยแล้วค่ะ","index.php");	
		}else{
			$this->msg("เกิดข้อผิดพลาดไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบค่ะ","index.php");	
		}
	}
	
	public function update($tb,$arr,$pk)
	{
		
		$field;
		$values;
		$wh;
		$num=count($_POST);
		$i=0;
		foreach($_POST as $key=>$val)
		{
			for($j=0;$j<count($arr);$j++)
			{
				$k=$arr[$j];
				if($key==$k)
				{
					
						if($i==($num-1))
						{	
							$field.= $key . "='" . $val . "'";
						}else{
							$field.= $key . "='" . $val . "',";	
						}
					$i++;
				}
				if($key==$pk){
					$wh= $key . "='" . $val ."'";
				}
			}
			$i++;
		}
		$sql="update $tb set $field where $wh";
		//exit();
		$q=$this->query($sql);
		if($q)
		{
			$this->msg("แก้ไขข้อมุลเรียบร้อยแล้วค่ะ","index.php");	
		}else{
			$this->msg("เกิดข้อผิดพลาดไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบค่ะ","index.php");	
		}
	}
	
	public function delete($tb,$pk)
	{
		$num=count($_POST);
		$i=0;
		$wh;
		foreach($_POST as $key=>$val)
		{
			
			if($key==$pk){
				$wh= $key . "='" . $val ."'";
			}
			
		}
		$sql="delete from $tb where $wh";
		$q=$this->query($sql);
		if($q)
		{
			$this->msg("ลบข้อมูลเรียบร้อยแล้วค่ะ","index.php");	
		}else{
			$this->msg("เกิดข้อผิดพลาดไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบค่ะ","index.php");	
		}
	}
	
	public function msg($msg,$page)
	{
		echo " <script>alert('$msg');window.location='$page';</script>";
		exit();
	}
	

	public function filter($str)
	{
		return preg_replace('#[^A-Za-z0-9]#i','',$str);
	}
	
	
	
/*	public function ckDataPage($case,$sta) //ตรวจสอบหน้าเพ็จว่ามีหรือไม่
	{
		$sqlMenu="select * from menu,imenu where menu.menuPo='$case' and imenu.status='$sta'";
				$qMenu=$this->query($sqlMenu);
				$rMenu=mysqli_num_rows($qMenu);
				
				if($rMenu==0)
				{
					$arrMenu=array("regis","forget","banner","menuDetailUser0","activityEdit1","newsEdit1","defenceEdit1","spa","spaShow","boardShow","boardShowAns","spaMenu","complain","menuManageUser","menuManageMenu");
					
					$thisPage=0;
					for($i=0;$i<count($arrMenu);$i++)
					{
						if($arrMenu[$i]==$case)
						{
							$thisPage=$case . ".php";	
						}
					}
					
					if($thisPage=="0")
					{
						$this->msg("ไม่มีหน้านี้ค่ะ","index.php");
						
					}else{
						return $thisPage;
					}
					
				}else{
					$p=$case . ".php";
					return $p;
				}
	}*/
	
	
/*	public function claim($menuLink) //ตรวจสอบสิทธิการใช้งานหน้านั้น ๆ
	{
		$menuLink=$menuLink . ".php";
		$sta=$this->sta;
		$sql="SELECT * FROM menu inner join imenu on menu.menuID=imenu.menuID where imenu.status='$sta' and menu.menuLink='$menuLink' ";
		$q=$this->conn->query($sql);
		$r=mysqli_num_rows($q);
		
		if($r==0)
		{
			$this->msg("คุณไม่มีสิทธิใช้งานหน้านี้ค่ะ","index.php");
			exit();	
		}else{
			return $menuLink;	
		}
	}*/
	
	

	public function get_client_ip()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	public function page(){
	 	return basename($_SERVER['PHP_SELF']);	
	}

public function session($ses)
{
		$iSub=$ses;
		$_SESSION["iSub"]=$iSub;
}

public function ckAdmin($status)
{
	if($status==1 || $status==9)
	{
		return true;
	}else{
		$this->msg("กรุณาเข้าหน้าต่างให้ถูกต้องด้วยค่ะ","index.php");
	}
	
}

public function ckDuty($sta,$page){
	
	$sqliMenu="select * from menu,imenu where menu.menuID=imenu.menuID and  imenu.status='$sta' and imenu.ok='1' order by menu.rank ASC";
	$qiMenu=$this->query($sqliMenu);
	$riMenu=mysqli_num_rows($qiMenu);
	$ck=0;
	for($i=0;$i<$riMenu;$i++)
	{
		$diMenu=mysqli_fetch_array($qiMenu);
		$menuPo=$diMenu["menuPo"];
		if($menuPo==$page)
		{
			$ck++;
		}
	}
	if($ck==0)
	{
		$this->msg("คุณไม่มีสิทธิใช้งานหน้าต่างนี้ค่ะ กรุณาติดต่อผู้ดูแลระบบ","index.php");
		
	}
	
}

public function ckPage($var,$page)
{
	if($var=="")
	{
		$this->msg("กรุณาเข้าหน้าต่างให้ถูกต้องด้วยค่ะ",$page);
		exit();
	}
}

public function rePage($a)
{
	if($var=="")
	{
		$this->msg("กรุณาเข้าหน้าต่างให้ถูกต้องด้วยค่ะ",$page);
		exit();
	}
}

public function ckCookie($iSub)
{
		if (isset($_COOKIE["my"])) 
		{
	
			$username = $_COOKIE["my"]; // ดึงค่าในคุกกี้ที่เคยเขียนไว ้ออกมา
			$sql="SELECT * FROM member WHERE uName = '$username' and iSub='$iSub'";
			$q=$this->query($sql);
			$data=mysqli_fetch_array($q);
			$_SESSION["sta"] = $data["status"]; // สร้าง Session ใหม่อัตโนมัติ
			
		}	
}

public function ckPic($p,$page)
{
	$ext=0;
	for($i=0;$i<count($p);$i++)
		{
			$imgtype=$p['type'][$i];
			if(!$imgtype=="")
			{
				
				if ($imgtype == "image/gif")
				{
							$ext = ".gif";
				}elseif ($imgtype == "image/png"){
							$ext = ".png";
				}elseif ($imgtype == "image/jpg" || $imgtype == "image/jpeg" || $imgtype == "image/pjpeg" || $imgtype == "image/JPEG"){
							$ext = ".jpg";
				}else{
						$ext= "1";
						break;
				}
			}
			
		}
		if($ext==1)
		{
			$this->msg("มีบางไฟล์ไม่ใช่รูปภาพกรุณาแก้ไขด้วยค่ะ",$page);
		}	
	}
	
	public function fetch_user_last_activity($memID)
	{
		//$userID = $this->get_user_id($memID);
	 $sql = "SELECT * FROM login_details WHERE memID = '$memID'  ORDER BY last_activity DESC LIMIT 1";
	 $q = $this->sel($sql);
	 $r = count($q);
	 if($r>0)
	 {
		
	  	return $q[0]->last_activity;
	 }
	 
	}
	
	public function count_unseen_message($frm_user_id,$t_user_id)
	{
	     $from_user_id = $frm_user_id;
		 $to_user_id = $this->get_user_id($t_user_id);
		 $sql = "SELECT * FROM chat_message WHERE from_user_id = '$from_user_id' AND to_user_id = '$to_user_id' AND status = '1'";
		 $q = $this->sel($sql);
		 $r = count($q);
		 $output = '';
		 if($r > 0)
		 {
		  $output = '<span class="label label-success">'.$r.'</span>';
		 }
		 return $output;
	}
		
	
	public function fetch_user_chat_history($from_user_id, $to_user_id)
	{
		
	     $sql = "
		 SELECT * FROM chat_message 
		 WHERE (from_user_id = '".$from_user_id."' 
		 AND to_user_id = '".$to_user_id."') 
		 OR (from_user_id = '".$to_user_id."' 
		 AND to_user_id = '".$from_user_id."') 
		 ORDER BY timestamp ASC
		 ";
		 
		 $q = $this->sel($sql);
		 $r = count($q);
		 $output;
		 $gp = $this->getUserProfiles($to_user_id);
		 $displayName = $gp[0];
		 $pictureUrl = $gp[1];
		 
		 for($i=0;$i<$r;$i++)
		 {
			
		  	$img = '';
			$d = $q[$i]->from_user_id;
			$chat_message = $q[$i]->chat_message;
			$time = $this->chDate($q[$i]->timestamp);
			$len = strlen($chat_message);
			$z = 23;
			if($len<=$z)
			{
				$x = $z-$len;
				$sp = '';
				for($j=0;$j<$x;$j++)
				{
					$sp .= "&nbsp;";
				}
				$chat_message = $sp . $chat_message;
			}
		  	if($d == $from_user_id)
		  	{
		   		$output .= '<div class="d-flex justify-content-end mb-4">
				<div class="msg_cotainer_send">' . $chat_message . '
				<span class="msg_time_send">' . $time . '</span></div>
				<div class="img_cont_msg"></div></div>';
		  	}else{
				 $output .= '<div class="d-flex justify-content-start mb-4">
				<div class="img_cont_msg"><img src="' . $pictureUrl . '" class="rounded-circle user_img_msg"></div>
				<div class="msg_cotainer">' . $chat_message . '<span class="msg_time">' . $time . '</span></div></div>';
		  	}
			 
		  }
		
		 $sql = "
		 UPDATE chat_message 
		 SET status = '0' 
		 WHERE from_user_id = '".$to_user_id."' 
		 AND to_user_id = '".$from_user_id."' 
		 AND status = '1'
		 ";
		 $q=$this->query($sql);
		 
		 return $output;
	}
	
	public function get_user_name($memID)
	{
		 $sql = "SELECT username FROM member WHERE memID = '$memID'";
		 $q = $this->query($sql);
		 $data = mysqli_fetch_array($q);
		 return $data['username'];
		 //return $m;
	}
	
	public function get_user_id($memID)
	{
		$sql = "SELECT userID FROM member WHERE memID = '$memID'";
		 $q = $this->query($sql);
		 $data = mysqli_fetch_array($q);
		 return $data['userID'];
	}

	public function get_user_name_from_id($memID)
	{
		$sql = "SELECT username FROM member WHERE userID = '$memID'";
		 $q = $this->query($sql);
		 $data = mysqli_fetch_array($q);
		 return $data['username'];
	}
	
	public function fetch_group_chat_history($connect)
	{
		 $sql = "
		 SELECT * FROM chat_message 
		 WHERE to_user_id = '0'  
		 ORDER BY timestamp DESC
		 ";
		 $q = $this->query($sql);
		 $r = mysqli_num_rows($q);
		 $output = '<ul class="list-unstyled">';
		 for($i=0;$i<$r;$i++)
		 {
			 $d = mysqli_fetch_array($q);
			  $user_name = '';
			  $chat_message = '';
			  $dynamic_background = '';
		
		  if($d['from_user_id'] == $_SESSION['user_id'])
		  {
		   if($d["status"] == '2')
		   {
			$chat_message = '<em>This message has been removed</em>';
			$user_name = '<b class="text-success">You</b>';
		   }
		   else
		   {
			$chat_message = $d['chat_message'];
			$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
		   }
		   $dynamic_background = 'background-color:#ffe6e6;';
		  }
		  else
		  {
		   if($d["status"] == '2')
		   {
			$chat_message = '<em>This message has been removed</em>';
		   }
		   else
		   {
			$chat_message = $d['chat_message'];
		   }
		   $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id']).'</b>';
		   $dynamic_background = 'background-color:#ffffe6;';
		  }
		  $output .= '
		  <li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
		   <p>'.$user_name.' - '.$chat_message.' 
			<div align="right">
			 - <small><em>'.$d['timestamp'].'</em></small>
			</div>
		   </p>
		   
		  </li>
		  ';
		 }
		 $output .= '</ul>';
		 return $output;
	}
	
	public function exec_url($url,$message)
	{
		$token = $this->token;
		   $header = array(
            	"Content-Type: application/json",
            	'Authorization: Bearer ' . $token,
        	);
	   
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$message); 
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		$returned =  curl_exec($ch);
	  
		return($returned);
		//return var_dump(json_decode($message));
	}	
	
	public function  getUserProfiles($userId) {
		
	  $url = "https://api.line.me/v2/bot/profile/" . $userId;
	  $token = $this->token;
	  $header = array(
            	"Content-Type: application/json",
            	'Authorization: Bearer ' . $token,
        	);
	  	$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		
		try {
			$returned =  curl_exec($ch);
			//return var_dump(json_decode($returned));
			$re = json_decode($returned);
			$displayName = $re->displayName;
			$pictureUrl = $re->pictureUrl;
			$statusMessage = $re->statusMessage;
			if($displayName=="")
			{
				$displayName = 'User Leave Chat Room';
				$pictureUrl = 'https://www.nctsc.com/nctsLineChatBot/img/userBlock.png';
				$statusMessage = '';
			}
		} catch (Exception $e) {
			$displayName = 'User Leave Chat Room';
			$pictureUrl = 'https://www.nctsc.com/nctsLineChatBot/img/userBlock.png';
			$statusMessage = '';
		}
		
	  	return array($displayName,$pictureUrl,$statusMessage);
	}
	
	
	public function getCountMember($token)
	{
		 $url = "https://api.line.me/v2/bot/room/{roomId}/members/count";
	  $header = array(
            	"Content-Type: application/json",
            	'Authorization: Bearer ' . $token,
        	);
	  	$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		$returned =  curl_exec($ch);
		
		return $returned;
	}
	
	public function getMember($token,$roomID) //สำหรับโล่น้ำเงินแลเขียว
	{
		$url ="https://api.line.me/v2/bot/room/{" . $roomID . "}/members/ids";	
		$header = array(
            	"Content-Type: application/json",
            	'Authorization: Bearer ' . $token,
        	);
	  	$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		$returned =  curl_exec($ch);
		$returned = json_decode($returned);
		return $returned;
	}
	
	public function pushMsg($message,$userId)
	{
		//echo $message . ":" . $userId;
		
			$m = array(
			'to' => $userId,
			'messages' => array(
				array(
					'type' => 'text',
					'text' => $message
				)
				)
			);
		
		$url = 'https://api.line.me/v2/bot/message/push';
		$response = $this->exec_url($url,json_encode($m));
		//return 1;
		return $response;
	}
	
	public function chDate($tp){
		$tp = explode(" ",$tp);
		$date = explode("-",$tp[0]);
		$t = $tp[1];
		$m;
		switch($date[1]){
			case "01" : $m="JAN";
			break;
			case "02" : $m="FEB";
			break;
			case "03" : $m="MAR";
			break;
			case "04" : $m="APR";
			break;
			case "05" : $m="MAY";
			break;
			case "06" : $m="JUN";
			break;
			case "07" : $m="JLY";
			break;
			case "08" : $m="AUG";
			break;
			case "09" : $m="SEP";
			break;
			case "10" : $m="OCT";
			break;
			case "11" : $m="NOV";
			break;
			case "12" : $m="DEC";
			break;
			default : $m="JAN";
			break;
		}
		$today = date("Y-m-d");
		if($today == $tp[0])
		{
			return "today " . $t;
		}else{
			return $d = $date[2] . " " . $m . " " . $date[0] . " " . $t;
		}
		
		//return $tp;
	}
	
	public function ckBrowser(){
		 
		 $useragent = $_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{
			echo "<script>window.location='lineChatMobile.php?ck=1'</script>";
		
		}else{
		
			echo "<script>window.location='lineChat.php?ck=1'</script>";
		
		}
	}
}
?>

