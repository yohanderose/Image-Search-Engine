<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$query_string = $_POST['query'];
$url = "https://qlpvexadf5.execute-api.us-east-1.amazonaws.com/testing/get-items";
$data = array('query' => htmlspecialchars($query_string));

$options = array(
	'http' => array(
		'header'  => "Content-type: application/json",
		'method'  => 'POST',
		'content' => http_build_query($data)
	)
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$images = json_decode($result);


echo $result;
