<?php 

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$accessToken = 'P86IeQbZCJ36VY46VIV4gMEOGfHU/vdiHiS3VlzQ1f/G7PpFZ1dBPyMVW4TpLX3otwiGbhQBzu6WqCO3a9z04Qn297fU+1Af373yebILuF/aixVBSjt8xa4yuQa9LwBmAJEcsxdLYhERVurjFYWXFQdB04t89/1O/w1cDnyilFU=';

$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);

$jsonHeader = "Content-Type: application/json";
$nullFieldHeader = "Content-Length: 0";
$accessHeader = "Authorization: Bearer {$accessToken}";

$arrayHeader = array();
$arrayHeader[] = $jsonHeader;
$arrayHeader[] = $accessHeader;

$message = $arrayJson['events'][0]['message']['text'];
$id = $arrayJson['events'][0]['source']['userId'];
$replyToken = $arrayJson['events'][0]['replyToken'];


$richMenu = [
	'size' => [ 'width' => 2500,'height' => 1686 ],
	"selected" => false,
    "name" => "Controller",
	"chatBarText" => "Controller",
	"areas" => [
    	[
          	"bounds" => [
				"x" => 551,
				"y" => 325,
				"width" => 321,
				"height" => 321
		  ],
          	"action" => [
				"type" => "message",
				"text" => "up"
		  ]
		],
        [
			"bounds" => [
				"x" => 876,
				"y" => 651,
				"width" => 321,
				"height" => 321
			],
			"action" => [
				"type" => "message",
				"text" => "right"
			]
		],
		[
			"bounds" => [
				"x" => 551,
				"y" => 972,
				"width" => 321,
				"height" => 321
			],
			"action" => [
				"type" => "message",
				"text" => "down"
			]
		  ],
		[
			"bounds" => [
				"x" => 225,
				"y" => 651,
				"width" => 321,
				"height" => 321
			],
			"action" => [
				"type" => "message",
				"text" => "left"
			]
		  ],
		[
			"bounds" => [
				"x" => 1433,
				"y" => 657,
				"width" => 321,
				"height" => 321
			],
			"action" => [
				"type" => "message",
				"text" => "btn b"
			]
		  ],
		[
			"bounds" => [
				"x" => 1907,
				"y" => 657,
				"width" => 321,
				"height" => 321
			],
			"action" => [
				"type" => "message",
				"text" => "btn a"
			]
		  ]
    ]
];


if($message == "reply"){
	$arrayPostData['replyToken'] = $replyToken;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = "Test Reply Message";
	replyMsg($arrayHeader,$arrayPostData);
}


if($message == "showRichMenu"){

	
	$RichMenuId = getRichMenu($arrayHeader);
	$arrayPostData['replyToken'] = $replyToken;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = $RichMenuId;
	ReplyMsg($arrayHeader,$arrayPostData);
}

function getRichMenu($header){
	$strUrl = "https://api.line.me/v2/bot/richmenu/list";
	$ch = curl_init($strUrl);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	$result = curl_exec($ch);
	if ($result== null) $result = "Hello";
	

	return "result ::".$result."\nHeader ::".$header."\nHTTP Code :".curl_error($ch);
	curl_close ($ch);
	
}


if($message == "createRichMenu"){
	
	$newRichMenu = null;
	$newRichMenu = json_decode(createRichMenu($arrayHeader,$richMenu),true);

	if($newRichMenu != null){
		$arrayPostData['replyToken'] = $replyToken;
		$arrayPostData['messages'][0]['type'] = "text";
		$arrayPostData['messages'][0]['text'] = "Success!! RichMenuId: ".$newRichMenu['richMenuId'];
		ReplyMsg($arrayHeader,$arrayPostData);
	} 
	else{
		$arrayPostData['replyToken'] = $replyToken;
		$arrayPostData['messages'][0]['type'] = "text";
		$arrayPostData['messages'][0]['text'] = "Fail to create";
		ReplyMsg($arrayHeader,$arrayPostData);
	}
}

function createRichMenu($arrayHeader,$arrayPostData){
	$strUrl = "https://api.line.me/v2/bot/richmenu";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;
	//return json_decode($result,true)['richMenuId'];
	
}

/*
function setDefaultRichMenu($richMenuObject,$header){
	$richMenuId = json_decode($richMenuObject,true)['richMenuId'];
	$strUrl = "https://api.line.me/v2/bot/user/all/richmenu/{$richMenuId}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, $header);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, null);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec($ch);
	
	return 'result :'.$result.'/r'.' Header :'.$header;
	curl_close ($ch);
}
*/

function pushMsg($arrayHeader,$arrayPostData){
	$strUrl = "https://api.line.me/v2/bot/message/push";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);
}

function replyMsg($arrayHeader,$arrayPostData){
	$strUrl = "https://api.line.me/v2/bot/message/reply";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arrayPostData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);
}
echo "OK";
