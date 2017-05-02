<?php

/* validate verify token needed for setting up web hook */
if (isset($_GET['hub_verify_token'])) {
	if ($_GET['hub_verify_token'] === 'access_token_deneme') {
		echo $_GET['hub_challenge'];
		return;
	} else {
		echo 'Invalid Verify Token';
		return;
	}
}

/* receive and send messages */
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {
	
	$sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
	$message = $input['entry'][0]['messaging'][0]['message']['text']; //text that user sent
	
	$bilgiler = explode(",", $message);
	echo $bilgiler[0]; // dilim1
	echo $bilgiler[1];
	echo $bilgiler[2];
	
	$url = 'https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN';
	
	/*initialize curl*/
	$ch = curl_init($url);
	/*prepare response*/
	$jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
        },
        "message":{
            "text": "'. $bilgiler[0] .'  Tarihinde ' . $bilgiler[1]. ' istikametinde ' . $bilgiler[2]. ' kisilik yer"
        }
    }';
	/* curl setting to send a json post data */
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	if (!empty($message)) {
		$result = curl_exec($ch); // user will get the message
	}
}

?>