<?php
$str = "{'richMenuId': 'richmenu-88c05ef6921ae53f8b58a25f3a65faf7'}";
$strJson =json_decode($str);
echo $strJson['richMenuId'];
//echo json_encode($str);
