<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$accessToken = '0jS0Ruxi7W+hKeiP5oCADFKdmopTgkTaPHf4zZ8dai7HNISkyPk717TU9Gkvsyo3hxdHozxijrpT5Zx0O2jKhlIHOii1HyCwAhRR386+6c2v1soOOFPtZpQmacQMlLZR4OfUDkvLXhpLT2PjiZmvoAdB04t89/1O/w1cDnyilFU=';

$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);

$jsonHeader = "Content-Type: application/json";
$accessHeader = "Authorization: Bearer {$accessToken}";
$arrayHeader = array();
$arrayHeader[] = $jsonHeader;
$arrayHeader[] = $accessHeader;
//รับข้อความจากผู้ใช้
$type = $arrayJson['events'][0]['type'];
$message = $arrayJson['events'][0]['message']['text'];
//รับ id ของผู้ใช้
$id = $arrayJson['events'][0]['source']['userId'];
$replyToken = $arrayJson['events'][0]['replyToken'];
#ตัวอย่าง Message Type "Text + Sticker"

$richmenu = [
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

$arrayPostData['replyToken'] = $replyToken;	
$arrayPostData['messages'][0]['type'] = "text";
$arrayPostData['messages'][0]['text'] = createRichMenu($arrayHeader,$richmenu);
ReplyMsg($arrayHeader,$arrayPostData);

if($message == "push"){
	$arrayPostData['to'] = $id;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = "Push Message";
	$arrayPostData['messages'][1]['type'] = "sticker";
	$arrayPostData['messages'][1]['packageId'] = "2";
	$arrayPostData['messages'][1]['stickerId'] = "34";
	pushMsg($arrayHeader,$arrayPostData);
}

/*
	$arrayPostData['replyToken'] = $replyToken;	
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = $type .' with :'.$message;
	$arrayPostData['messages'][1]['type'] = "sticker";
	$arrayPostData['messages'][1]['packageId'] = "2";
	$arrayPostData['messages'][1]['stickerId'] = "34";	
	ReplyMsg($arrayHeader,$arrayPostData);
*/


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

	//$defRes = setDefaultRichMenu($result);

	return $result['RichMenuId'];
	curl_close ($ch);
}

function setDefaultRichMenu($richMenuObject){
	$richMenuId = $richMenuObject['richMenuId'];
	$strUrl = "https://api.line.me/v2/bot/user/all/richmenu/$richMenuId";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $accessHeader);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	return $result;
	curl_close ($ch);
}



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
