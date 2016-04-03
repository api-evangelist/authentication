<?php
$route = '/authentication/';
$app->get($route, function ()  use ($app,$contentType,$githuborg,$githubrepo){

	$ReturnObject = array();
	//$ReturnObject["contentType"] = $contentType;

	if($contentType == 'application/apis+json')
		{
		$app->response()->header("Content-Type", "application/json");

		$apis_json_url = "http://" . $githuborg . ".github.io/" . $githubrepo . "/apis.json";
		$apis_json = file_get_contents($apis_json_url);
		echo stripslashes(format_json($apis_json));
		}
	else
		{

	 	$request = $app->request();
	 	$params = $request->params();

		if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
		if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
		if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 50;}
		if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
		if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'ASC';}

		// Pull from MySQL
		if($query!='')
			{
			$Query = "SELECT * FROM authentication WHERE title LIKE '%" . $query . "%' OR header LIKE '%" . $query . "%' OR footer LIKE '%" . $query . "%'";
			}
		else
			{
			$Query = "SELECT * FROM authentication";
			}
			$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
			//echo $Query . "<br />";
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
			}
	});
?>
