<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_POST["imgUrl"] && $_POST["imgName"]) {

	$url = "https://qlpvexadf5.execute-api.us-east-1.amazonaws.com/testing/delete-items";
	$data = array('imgUrl' => $_POST["imgUrl"], 'imgName' => $_POST["imgName"]);

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/json",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	$newURL = "https://ec2-3-235-253-98.compute-1.amazonaws.com/";

	header('Location: ' . $newURL);
}
