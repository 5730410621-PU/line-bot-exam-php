<?php 

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$accessToken = 'P86IeQbZCJ36VY46VIV4gMEOGfHU/vdiHiS3VlzQ1f/G7PpFZ1dBPyMVW4TpLX3otwiGbhQBzu6WqCO3a9z04Qn297fU+1Af373yebILuF/aixVBSjt8xa4yuQa9LwBmAJEcsxdLYhERVurjFYWXFQdB04t89/1O/w1cDnyilFU=';

$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);

$jsonHeader = "Content-Type: application/json";
$imageHeader = "Content-Type: application/json";
$accessHeader = "Authorization: Bearer {$accessToken}";

$arrayHeader = array();
$arrayHeader[] = $jsonHeader;
$arrayHeader[] = $accessHeader;

$message = $arrayJson['events'][0]['message']['text'];
$id = $arrayJson['events'][0]['source']['userId'];
$replyToken = $arrayJson['events'][0]['replyToken'];

$imageArrayHeader = array();
$imageArrayHeader[] = "Content-Type: image/jpeg";


$richMenu = [
	'size' => [ 'width' => 2500,'height' => 1686 ],
	"selected" => false,
    "name" => "New Test Menus",
	"chatBarText" => "Tap to open",
	"areas" => [
    	[
          	"bounds" => [
				"x" => 0,
				"y" => 0,
				"width" => 833,
				"height" => 843
		  ],
          	"action" => [
				"type" => "message",
				"text" => "Horo"
		  ]
		],
        [
			"bounds" => [
				"x" => 833,
				"y" => 0,
				"width" => 833,
				"height" => 843
			],
			"action" => [
				"type" => "message",
				"text" => "Poll"
			]
		],
		[
			"bounds" => [
				"x" => 1666,
				"y" => 0,
				"width" => 833,
				"height" => 843
			],
			"action" => [
				"type" => "message",
				"text" => "Quiz"
			]
		  ],
		[
			"bounds" => [
				"x" => 0,
				"y" => 843,
				"width" => 833,
				"height" => 843
			],
			"action" => [
				"type" => "message",
				"text" => "News"
			]
		  ],
		[
			"bounds" => [
				"x" => 833,
				"y" => 843,
				"width" => 833,
				"height" => 843
			],
			"action" => [
				"type" => "message",
				"text" => "Vr/Ar"
			]
		  ],
		[
			"bounds" => [
				"x" => 1666,
				"y" => 843,
				"width" => 833,
				"height" => 843
			],
			"action" => [
				"type" => "message",
				"text" => "Report"
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

////////////////// Get Rich Menu ////////////////////////

if($message == "showRichMenu"){

	$RichMenuId = getRichMenu($arrayHeader);
	$arrayPostData['replyToken'] = $replyToken;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = "RichId :".$RichMenuId;
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

	$result = json_decode(curl_exec($ch),true);
	$richId = $result['richmenus'][0]['richMenuId'];
	if ($result== null) $result = "Hello";
	return $richId;
	curl_close ($ch);
	
}

///////////// Create Rich Menu ////////////////////////

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

/////////////////////Set Rich Menu /////////////////////////////

if($message == "setMenu"){

	$richMenuId = getRichMenu($arrayHeader);
	$setRichMenu = setRichMenu($arrayHeader,$richMenuId);
	$arrayPostData['replyToken'] = $replyToken;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = "Set Complete ::".$setRichMenu;
	ReplyMsg($arrayHeader,$arrayPostData);
}

function setRichMenu($arrayHeader,$richMenuId){
	$strUrl = "https://api.line.me/v2/bot/user/all/richmenu/".$richMenuId;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
	curl_setopt($ch, CURLOPT_POSTFIELDS, " ");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;

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
