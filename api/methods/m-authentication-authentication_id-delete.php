<?php
$route = '/authentication/:authentication_id/';
$app->delete($route, function ($authentication_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$authentication_id = prepareIdIn($authentication_id,$host);
	$authentication_id = mysql_real_escape_string($authentication_id);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM authentication WHERE authentication_id = " . $authentication_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
