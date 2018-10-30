<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = '0jS0Ruxi7W+hKeiP5oCADFKdmopTgkTaPHf4zZ8dai7HNISkyPk717TU9Gkvsyo3hxdHozxijrpT5Zx0O2jKhlIHOii1HyCwAhRR386+6c2v1soOOFPtZpQmacQMlLZR4OfUDkvLXhpLT2PjiZmvoAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text' && $event[0]['message']['text'] == 'ดูดวงประจำวัน') {
			// Get text sent
			$text = 'เลือกวันเกิดมาเลย';
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			// $messages = [
			// 	'type' => 'text',
			// 	'text' => $text
			// ];

			$messages = [
				'type' => 'text',
				'text' => $text
			];
					

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			replyMsg($headers,$post);
		}

		else {
			$messages = [
				'type' => 'text',
				'text' => 'error'
			];

			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			replyMsg($headers,$post);
		}
	}
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
