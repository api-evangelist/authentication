<?php
$route = '/authentication/';
$app->post($route, function () use ($app){

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM authentication WHERE title = '" . $title . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$Thisauthentication = mysql_fetch_assoc($Database);
		$authentication_id = $Thisauthentication['ID'];
		}
	else
		{
		$Query = "INSERT INTO authentication(title,image,header,footer)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($image) . "',";
		$Query .= "'" . mysql_real_escape_string($header) . "',";
		$Query .= "'" . mysql_real_escape_string($footer) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$authentication_id = mysql_insert_id();
		}

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
