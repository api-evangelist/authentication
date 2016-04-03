<?php
$route = '/authentication/:authentication_id/';
$app->put($route, function ($authentication_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$authentication_id = prepareIdIn($authentication_id,$host);
	$authentication_id = mysql_real_escape_string($authentication_id);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM authentication WHERE ID = " . $authentication_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE authentication SET ";
		$query .= "title = '" . mysql_real_escape_string($title) . "'";
		$query .= ", image = '" . mysql_real_escape_string($image) . "'";
		$query .= ", header = '" . mysql_real_escape_string($header) . "'";
		$query .= ", footer = '" . mysql_real_escape_string($footer) . "'";
		$query .= " WHERE authentication_id = " . $authentication_id;
		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

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
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
