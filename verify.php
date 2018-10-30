<?php
$access_token = '0jS0Ruxi7W+hKeiP5oCADFKdmopTgkTaPHf4zZ8dai7HNISkyPk717TU9Gkvsyo3hxdHozxijrpT5Zx0O2jKhlIHOii1HyCwAhRR386+6c2v1soOOFPtZpQmacQMlLZR4OfUDkvLXhpLT2PjiZmvoAdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;