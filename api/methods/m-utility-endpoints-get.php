<?php
		
$route = '/utility/endpoints/';	
$app->get($route, function ()  use ($app){
	
	$ReturnObject = array();
		
	$dir = "/var/www/html/api_evangelist/plans/api/methods/";

	if ($handle = opendir($dir)) {
	    while (false !== ($file = readdir($handle))) 
	    	{

	        if ('.' === $file) continue;
	        if ('..' === $file) continue;
  
			$filename = substr($file, 0, 2);	
			   
			if($filename=='m-')
				{
					
				//echo $file . "<br />";	
				$openpath = $dir . $file;
				//echo $openpath . "<br />";	  	
				$myfile = fopen($openpath, "r") or die("Unable to open file!");
				$content = fread($myfile,filesize($openpath));
				
				$Begin_Tag = chr(36) . "route = '";
				$End_Tag = "';";
				$path = return_between($content, $Begin_Tag, $End_Tag, EXCL);
				//echo $path . " - ";	  				
				
				array_push($ReturnObject, $path);
				
				fclose($myfile);
				}			
			  
	    	}
	    closedir($handle);
	}
	
	//$ReturnObject['done'] = 1;

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});	
?>