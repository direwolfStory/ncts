<?php 
if (!function_exists('hash_equals')) 
{
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));
    function hash_equals($knownString, $userString)
    {
        $strlen = function ($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }
            return strlen($string);
        };
        if (($length = $strlen($knownString)) !== $strlen($userString)) {
            return false;
        }
        $diff = 0;
        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($knownString[$i]) ^ ord($userString[$i]);
        }
        return $diff === 0;
    }
}
class LINEBotTiny
{
	private $hn="45.130.228.52";
	private $un="u381699329_ncts";
	private $pn="nctsComputer18";
	private $dn="u381699329_ncts";
	
	
    public function __construct($channelAccessToken, $channelSecret)
    {
        $this->channelAccessToken = $channelAccessToken;
        $this->channelSecret = $channelSecret;
    }
  
  	private function conn()
	{
		$con = new mysqli($this->hn,$this->un,$this->pn,$this->dn);
		return $con;		
	}
	
	public function query($sql)
	{
			$q=mysqli_query($this->conn(),$sql);
			if($q)
			{
				return $q;
			}	
	}
	
	public function sel($sql)
	{

		$q = $this->query($sql);
		$num= mysqli_num_rows($q);
		$arr=array();		

		for($i=0;$i<$num;$i++)
		{
			$data=mysqli_fetch_object($q);
			$arr[$i]=$data;
		}

		return $arr;		
	}
	
	public function setMsgType($msgType,$userId,$adminId,$picurl)
	{
		$to_user_id = $adminId;
		$memID = $userId;
		$chat_message = $picurl;
		$status = 1;
		$msgType = $msgType;
		date_default_timezone_set('Asia/Bangkok');
		$dt = date('Y-m-d H:i:s');
		
		$sql = "select * from chat_message where from_user_id = '$userId' ORDER BY chat_message_id DESC";
		$q = $this->sel($sql);
		
		$lTime = $q[0]->timestamp;
		
		
		$sql = "
		INSERT INTO chat_message 
		(to_user_id, from_user_id, chat_message,timestamp, status,chatBy,msgType) 
		VALUES ('$to_user_id','$memID','$chat_message','$dt','$status','user','$msgType')
		";
		$q = $this->query($sql);
		
		$times   = strtotime($lTime);
		$times   = $times + (60*15); 
		$now15 = date("Y-m-d H:i:s", $times);
		
		$text = '???????????????????????????????????????Admin???????????????????????? ????????????????????????????????????????????????????????????????????? ^^';
		if($now15 < $dt)
		{
			return $mreply = array(
				'replyToken' => $replyToken,
				'messages' => array(
					array(
						'type' => 'text',
						'text' => $text
					)
				)
			);
		}else{
			return 0;	
		}
	}
  
    public function parseEvents()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            error_log("Method not allowed");
            exit();
        }
        $entityBody = file_get_contents('php://input');
        if (strlen($entityBody) === 0) {
            http_response_code(400);
            error_log("Missing request body");
            exit();
        }
        if (!hash_equals($this->sign($entityBody), $_SERVER['HTTP_X_LINE_SIGNATURE'])) {
            http_response_code(400);
            error_log("Invalid signature value");
            exit();
        }
        $data = json_decode($entityBody, true);
        if (!isset($data['events'])) {
            http_response_code(400);
            error_log("Invalid request body: missing events property");
            exit();
        }
        return $data['events'];
    }
    public function replyMessage($message)
    {
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        $context = stream_context_create(array(
            "http" => array(
                "method" => "POST",
                "header" => implode("\r\n", $header),
                "content" => json_encode($message),
            ),
        ));
    $response = exec_url('https://api.line.me/v2/bot/message/reply',$this->channelAccessToken,json_encode($message));
    }
  
  
    public function pushMessage($message) 
    {
        
    $response = exec_url('https://api.line.me/v2/bot/message/push',$this->channelAccessToken,json_encode($message));
       
    }
  
    public function profil($userId)
    {
      
    return json_decode(exec_get('https://api.line.me/v2/bot/profile/'.$userId,$this->channelAccessToken));
       
    }

    public function cont($messageid)
    {
      
    return json_decode(exec_get('https://api.line.me/v2/message/'.$messageid.'/content',$this->channelAccessToken));
       
    }

    private function sign($body)
    {
        $hash = hash_hmac('sha256', $body, $this->channelSecret, true);
        $signature = base64_encode($hash);
        return $signature;
    }
}
function exec_get($fullurl,$channelAccessToken)
{
    
    $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer '.$channelAccessToken,
        );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $fullurl);
    
    $returned =  curl_exec($ch);
  
    return($returned);
}
function exec_url($fullurl,$channelAccessToken,$message)
{
    
    $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer '.$channelAccessToken,
        );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST,           1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $message); 
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $fullurl);
    
    $returned =  curl_exec($ch);
  
    return($returned);
}
function exec_url_aja($fullurl)
  {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_FAILONERROR, 0);
      curl_setopt($ch, CURLOPT_URL, $fullurl);
      
      $returned =  curl_exec($ch);
    
      return($returned);
  }
  

  ?>