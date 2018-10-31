<?php

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