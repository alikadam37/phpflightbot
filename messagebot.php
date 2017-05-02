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
	
	$sehirler=explode("->", $bilgiler[1]);
	echo $sehirler[0]; // dilim1
	echo $sehirler[1];
	
	$params = array ('apikey'=>'it393887540691813618334846166341');
	
	// Build Http query using params
	$query = http_build_query ($params);
	
	// Create Http context details
	$contextData = array (
			'method' => 'GET',
			'header' => "Connection: close\r\n".
			"Content-Length: ".strlen($query)."\r\n",
			'content'=> $query );
	
	// Create context resource for our request
	$context = stream_context_create (array ( 'http' => $contextData ));
	
	// Read page rendered as result of your POST request
	$result =  file_get_contents (
			'http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/UK/GBP/en-GB/'.$sehirler[0].'-sky/'.$sehirler[1].'-sky/'.$bilgiler[0].'/',  // page url
			false,
			$context);
	
	$url = 'https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN';
	
	/*initialize curl*/
	$ch = curl_init($url);
	/*prepare response*/
	$jsonData = '{
    "recipient":{
        "id":"' . $sender . '"
        },
        "message":{
            "text":"You said, ' . $message . '"
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