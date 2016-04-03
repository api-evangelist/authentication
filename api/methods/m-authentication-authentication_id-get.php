<?php
$route = '/authentication/:authentication_id/';
$app->get($route, function ($authentication_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$authentication_id = prepareIdIn($authentication_id,$host);
	$authentication_id = mysql_real_escape_string($authentication_id);

	$ReturnObject = array();
	$Query = "SELECT * FROM authentication WHERE authentication_id = " . $authentication_id;
	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$authentication_id = $Database['authentication_id'];
		$title = $Database['title'];
		$image = $Database['image'];
		$header = $Database['header'];
		$footer = $Database['footer'];

		$KeysQuery = "SELECT * from keys k";
		$KeysQuery .= " WHERE authentication_id = " . $authentication_id;
		$KeysQuery .= " ORDER BY name ASC";
		$KeysResults = mysql_query($KeysQuery) or die('Query failed: ' . mysql_error());

		$authentication_id = prepareIdOut($authentication_id,$host);

		$F = array();
		$F['authentication_id'] = $authentication_id;
		$F['title'] = $title;
		$F['image'] = $image;
		$F['header'] = $header;
		$F['footer'] = $footer;

		// Keys
		$F['keys'] = array();
		while ($Keys = mysql_fetch_assoc($KeysResults))
			{
			$name = $Keys['name'];
			$description = $Keys['description'];
			$K = array();
			$K['name'] = $name;
			$K['description'] = $description;
			array_push($F['keys'], $K);
			}

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
