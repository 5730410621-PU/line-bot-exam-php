<?php 

$accessToken = 'P86IeQbZCJ36VY46VIV4gMEOGfHU/vdiHi
S3VlzQ1f/G7PpFZ1dBPyMVW4TpLX3otwiGbhQBzu6WqCO3a9z0
4Qn297fU+1Af373yebILuF/aixVBSjt8xa4yuQa9LwBmAJEcsx
dLYhERVurjFYWXFQdB04t89/1O/w1cDnyilFU=';

$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);

$jsonHeader = "Content-Type: application/json";
$zeroContentHeader = "Content-Length: 0";
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


if($message == "push"){
	$arrayPostData['to'] = $id;
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = "Test Push Message";
	pushMsg($arrayHeader,$arrayPostData);
}

if($message == "createRichMenu"){
	
	$newRichMenu = null;
	$newRichMenu = createRichMenu($arrayHeader,$arrayPostData);

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

if($message == "showRichMenu"){
	$newRichMenu = null;
	$newRichMenu = createRichMenu($arrayHeader,$arrayPostData);
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

function getRichMenu($header){
	$strUrl = "https://api.line.me/v2/bot/richmenu/list";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$strUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, null);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close ($ch);

	$richMenuList = (array) json_decode($result,true);
	$richMenu = $richMenuList['richmenus'][0]['richMenuId'];
	return $result;
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
