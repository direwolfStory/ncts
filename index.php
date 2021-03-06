<?php
#-------------------------[Include]-------------------------#
require_once('include/line_class.php');
require_once('unirest-php-master/src/Unirest.php');
#-------------------------[Token]-------------------------#
$channelAccessToken = 'OMb2UdOi3oE4jexM3pRhdslF5S+Ja1v89J2mkb089CnPSmNWIbzttLEvE7sBgrCkfJdZPVbDTgkmX5L0avaYLvdlNrcSqAs0DCqx5Ape7iuhjmBfBTwgfDWa/W334GzWxqv9C2k6QFo2mJTHqxewpFGUYhWQfeY8sLGRXgo3xvw='; 
$channelSecret = '2193018a4071996c9cd5d066e1855a75';
$fol = 'https://www.nctsc.com/nctsLineBot/';
$hook = file_get_contents('php://input');
#-------------------------[Events]-------------------------#



$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId     = $client->parseEvents()[0]['source']['userId'];
$groupId    = $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp  = $client->parseEvents()[0]['timestamp'];
$type       = $client->parseEvents()[0]['type'];
$message    = $client->parseEvents()[0]['message'];
$profile    = $client->profil($userId);
$repro = json_encode($profile);
$messageid  = $client->parseEvents()[0]['message']['id'];
$msg_type      = $client->parseEvents()[0]['message']['type'];

$post_data      = $client->parseEvents()[0]['postback']['data'];
$post_param 	= $client->parseEvents()[0]['postback']['params']['datetime'];


$msg_file      = $client->parseEvents()[0]['message']['fileName'];
$msg_message   = $client->parseEvents()[0]['message']['text'];
$msg_title     = $client->parseEvents()[0]['message']['title'];
$msg_address   = $client->parseEvents()[0]['message']['address'];
$msg_latitude  = $client->parseEvents()[0]['message']['latitude'];
$msg_longitude = $client->parseEvents()[0]['message']['longitude'];




#----Check title empty----#
if (empty($msg_title)) {
    $msg_title = 'Welcome to NCTSCHOOL';
}
#----command option----#
$usertext = explode(" ", $message['text']);
$command = $usertext[0];
$options = $usertext[1];
if (count($usertext) > 2) {
    for ($i = 2; $i < count($usertext); $i++) {
        $options .= '+';
        $options .= $explode[$i];
    }
}
#----command option----#
$remsg = json_encode($message, true);
$remsg1 = json_decode($remsg, true);
$remsg2 = $remsg1['text'];
$stickerId = $remsg1['stickerId'];
$reline = json_encode($profile, true);
$reline1 = json_decode($reline, true);
$reline2 = $reline1['displayName'];
$displayName = $reline1['displayName'];
$pictureUrl =  $reline1['pictureUrl'];
$statusMessage = $reline1['statusMessage'];


if ($type == 'memberJoined') {
    $text = "WELCOME TO NCTS GROUP";
        $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($type == 'memberLeft') {
    $text = "MEMBER LEFT THE GROUP";
        $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($type == 'join') {
      $text = "BOT JOIN THE GROUP";
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($type == 'leave') {
    $text = "BOT LEAVE THE GROUP";
        $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($type == 'follow') {
    $text = "THANK YOU FOR FOLLOW OUR GROUP";
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($type == 'unfollow') {
    $text = "BOT BLOCK THE GROUP";
        $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}else if ($msg_type == 'file') { 
	$url = 'https://api.line.me/v2/bot/message/' . $messageid . '/content';
	$headers = array('Authorization: Bearer ' . $channelAccessToken);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$ran = date("YmdHis");
	$botDataUserFolder = './user/file/file/' . $userId;
		if(!file_exists($botDataUserFolder)) {
			mkdir($botDataUserFolder, 0777, true);
		} 
	$fileFullSavePath = $botDataUserFolder . '/' . $ran . $msg_file;
	$fileurl = $fol . $fileFullSavePath;
	file_put_contents($fileFullSavePath,$result);
  	$text = "SAVED";
		  $mreply = array(
			'replyToken' => $replyToken,
			'messages' => array(
				array(
					'type' => 'text',
					'text' => $text
				),
				array(
					'type' => 'text',
					'text' => $fileurl
				)
			)
		);
}else if ($msg_type == 'image') {
	$url = 'https://api.line.me/v2/bot/message/' . $messageid . '/content';
	$headers = array('Authorization: Bearer ' . $channelAccessToken);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$ran = date("YmdHis");
	$botDataUserFolder = './user/file/image/' . $userId;
	if(!file_exists($botDataUserFolder)) {
		mkdir($botDataUserFolder, 0777, true);
	} 
	$fileFullSavePath = $botDataUserFolder . '/' . $ran . '.jpg';
	$picurl = $fol . $fileFullSavePath;
	file_put_contents($fileFullSavePath,$result);
  	$text = "SAVE IMAGE ALREADY";
      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            ),
            array(
                'type' => 'text',
                'text' => $picurl
            )
        )
    );
}else if ($msg_type == 'video') {
	$url = 'https://api.line.me/v2/bot/message/' . $messageid . '/content';
	$headers = array('Authorization: Bearer ' . $channelAccessToken);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$ran = date("YmdHis");
	$botDataUserFolder = './user/file/video/' . $userId;
	if(!file_exists($botDataUserFolder)) {
		 mkdir($botDataUserFolder, 0777, true);
	} 
	$fileFullSavePath = $botDataUserFolder . '/' . $ran . '.mp4';
	$vidurl = $fol . $fileFullSavePath;
	file_put_contents($fileFullSavePath,$result);
  	$text = "SAVE VIDEO ALREADY";
      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            ),
            array(
                'type' => 'text',
                'text' => $vidurl
            )
        )
    );
}else if ($msg_type == 'audio') {
	$url = 'https://api.line.me/v2/bot/message/' . $messageid . '/content';
	$headers = array('Authorization: Bearer ' . $channelAccessToken);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$ran = date("YmdHis");
	$botDataUserFolder = './user/file/audio/' . $userId;
	if(!file_exists($botDataUserFolder)) {
		mkdir($botDataUserFolder, 0777, true);
	} 
	$fileFullSavePath = $botDataUserFolder . '/' . $ran . '.m4a';
	$audurl = $fol . $fileFullSavePath;
	file_put_contents($fileFullSavePath,$result);
  	$text = "SAVE AUDIO ALREADY";
      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            ),
            array(
                'type' => 'text',
                'text' => $audurl
            )
        )
    );
}else if ($msg_type == 'sticker') {
  $stickerurl = "https://stickershop.line-scdn.net/stickershop/v1/sticker/" . $stickerId . "/android/sticker.png";
      $mreply = array(
        'replyToken' => $replyToken,
        	'messages' => array(
         		array(
					'type' => 'flex',
					'altText' => 'Sticker!!',
					'contents' => array(
        				'type' => 'bubble',
        					'body' => array(
							  'type' => 'box',
							  'layout' => 'vertical',
							  'spacing' => 'md',
							  'contents' => array(
            					array(
									'type' => 'text',
								  	'align' => 'center',
								  	'color' => '#049b1b',
								  	'text' => 'USER : ' . $reline2
							  	),
            					array(
								  'type' => 'image',
								  'size' => '5xl',
								  'align' => 'center',
								  'url' => $stickerurl
      							)
       						 )
        					)
        				)
        			)
    			)
    		);
}else if($msg_type == 'location') {
    $uri = "https://api.openweathermap.org/data/2.5/weather?lat=" . $msg_latitude . "&lon=" . $msg_longitude . "&lang=th&units=metric&appid=bb32ab343bb6e3326f9e1bbd4e4f5d31";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $resulta = $json['name'];
    $resultb = $json['weather'][0]['main'];
    $resultc = $json['weather'][0]['description'];
    $resultd = $json['main']['temp'];
    $resulte = $json['coord']['lon'];

    $text .= " ????????????????????? : " . $resulta . "\n";
    $text .= " ??????????????????????????? : " . $resultb . "\n";
    $text .= " ?????????????????????????????? : " . $resultc . "\n";
    $text .= " ???????????????????????? : " . $resultd;

      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'location',
                'title' => $msg_title,
                'address' => $msg_address,
                'latitude' => $msg_latitude,
                'longitude' => $msg_longitude
            ),            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );

}else{ 

	if ($command== 'myid'){ 
			$mreply = array(
        	'replyToken' => $replyToken,
        	'messages' => array(
            	array(
                'type' => 'text',
                'text' => 'YOUR userId '.$userId,
				'quickReply' => array(
    				'items' => array(
     					array(
      						  'type' => 'action',
							  'action' => array(
							   'type' => 'postback',
							   'label' => 'Postback',
							   'data' => 'happy'
							  )
     					)
   				 )
   			)
		)
        )
    	);
	}else if ($command== 'qr' || $command== 'Qr' || $command== 'QR' || $command== 'Qrcode' || $command== 'QRcode' || $command== 'qrcode') { 
      	$url = 'https://chart.googleapis.com/chart?cht=qr&choe=UTF-8&chs=300x300&chl='.$options;
		  $mreply = array(
			'replyToken' => $replyToken,
			'messages' => array(
				array(
					'type' => 'image',
					'originalContentUrl' => $url,
					'previewImageUrl' => $url
				)
			)
		);
	}else if($post_data=='appointment'){
		$dd = explode("T",$post_param);
		$d = $dd[0];
		$t = $dd[1];
		$txt = "??????????????????????????????????????????????????? : " . $d ."\n";
		$txt.= "???????????? : " . $t . "\n";
		
		    $mreply = array(
        			'replyToken' => $replyToken,
        			'messages' => array(
									array(
 							        	'type' => 'flex',
										'altText' => 'Appointment',
										'contents' => array(
											'type' => 'bubble',
												'body' => array(
									  			'type' => 'box',
									  			'layout' => 'vertical',
									  			'spacing' => 'md',
									  				'contents' => array(
														array(
										  					'type' => 'text',
									  						'align' => 'start',
									  						'color' => '#049b1b',
															'wrap' => true,
															'text' => $txt
								  						),
														array(
															'type' => 'button',
															'action' => array(
															'type' => 'message',
															'label' => '??????????????????????????????????????????????????????????????????',
									  						'text' => 'appointment date@' . $post_param
															)
														),array(
															'type' => 'button',
															'action' => array(
																'type' => 'message',
																'label' => '????????????????????????????????????????????????',
									  							'text' => 'cancel'
															)
														)
													) //end body content
												)// end body
											)   //end content      				
					  	)//end array
    				)//end message
    			);//end mreply
			
	}else{
                    $url = "https://bots.dialogflow.com/line/01af57d2-dabe-4472-b016-d2b94f766a51/webhook";		
                    //$url = "https://dialogflow.cloud.google.com/v1/integrations/line/webhook/01af57d2-dabe-4472-b016-d2b94f766a51";
					$headers = getallheaders();
                    file_put_contents('headers.txt',json_encode($headers, JSON_PRETTY_PRINT));          
                    file_put_contents('body.txt',$hook);
                    $headers['Host'] = "bots.dialogflow.com";
                    $json_headers = array();
                    foreach($headers as $k=>$v){
                        $json_headers[]=$k.":".$v;
                    }
                    $inputJSON = $hook;
                    $ch = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $url);
                    curl_setopt( $ch, CURLOPT_POST, 1);
                    curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $inputJSON);
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, $json_headers);
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1); 
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    $result = curl_exec( $ch );
                    curl_close( $ch );	
	}
}

if (isset($mreply)) {
    //$result = json_encode($mreply);
    $client->replyMessage($mreply);
}  
    file_put_contents('log.txt',$hook);
	


?>

    

