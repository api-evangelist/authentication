<?php

$route = '/utility/api/client-php/rebuild/';	
$app->get($route, function ()  use ($app,$githuborg,$githubrepo,$gclient){
	
	$ref = "gh-pages";
	$APIsJSONURL = "https://raw.github.com/" . $githuborg . "/" . $githubrepo . "/gh-pages/apis.json";
	
	$Resource_Store_File = "apis.json";
	
	echo $Resource_Store_File . "<br />";
	
	$CheckFile = $gclient->repos->contents->getContents($githuborg, $githubrepo, $ref, $Resource_Store_File);
	$APIsJSONContent = base64_decode($CheckFile->getcontent());	

	$APIsJSON = json_decode($APIsJSONContent,true);

	foreach($APIsJSON['apis'] as $APIsJSON)
		{
		$properties = $APIsJSON['properties'];	
		foreach($properties as $property)
			{
			$property_type = $property['type'];
			if(strtolower($property_type)=="swagger")
				{
				$swagger_url = $property['url'];		
				echo $property_type . " - " . $swagger_url . "<br />";
				
				$cleanbase = "https://github.com/" . $githuborg . "/" . $githubrepo . "/blame/" . $ref . "/";
				$swagger_path = str_replace($cleanbase,"",$swagger_url); 

				$PullSwagger = $gclient->repos->contents->getContents($githuborg, $githubrepo, $ref, $swagger_path);
				$SwaggerJSON = base64_decode($PullSwagger->getcontent());						
				
				$Swagger = json_decode($SwaggerJSON,true);	
				
				$Swagger_Title = $Swagger['info']['title'];
				$Swagger_Description = $Swagger['info']['description'];
				$Swagger_TOS = $Swagger['info']['termsOfService'];
				$Swagger_Version = $Swagger['info']['version'];
				
				$Swagger_Host = $Swagger['host'];
				$Swagger_BasePath = $Swagger['basePath'];
				
				$Swagger_Scheme = $Swagger['schemes'][0];
				$Swagger_Produces = $Swagger['produces'][0];
				
				echo $Swagger_Title . "<br />";

				$Method = "";
				$Method .= "<?php" . chr(13);			
					
				$Swagger_Definitions = $Swagger['definitions'];	
					
				$Swagger_Paths = $Swagger['paths'];				
				foreach($Swagger_Paths as $key => $value)
					{
						
					$Path_Route = $key;
					echo $Path_Route . "<br />";
					
					// Each Path Variable
					$id = 0;
					$Path_Variable_Count = 1;
					$Path_Variables = "";
					$Begin_Tag = "{";
					$End_Tag = "}";
					$path_variables_array = return_between($Path_Route, $Begin_Tag, $End_Tag, EXCL);

					$Path_Route = str_replace("{",":",$Path_Route);
					$Path_Route = str_replace("}","",$Path_Route);				
						
					// Each Path
					foreach($value as $key2 => $value2)
						{
							
						$Definition = "";
						$Path = "";
						$Path_Verb = $key2;
								
						$Path_Summary = $value2['summary'];
						$Path_Desc = $value2['description'];
						$Path_OperationID = $value2['operationId'];
						$Path_Parameters = $value2['parameters'];		
						
						echo $Path_Verb . "<br />";
						echo $Path_Summary . "<br />";																								
						
						// Each Verb
						if($Path_Verb=="get")
							{
							

							} 
						elseif($Path_Verb=="post")
							{



							}
						elseif($Path_Verb=="put")
							{
						


							}							
						elseif($Path_Verb=="delete")
							{
							
						  	
														
							}																											
																				
						
						
						
						$Method .= $Path;
						}															
					}
					
				echo "<hr />";
				
				echo "--definitions--<br />";
				foreach($Swagger_Definitions as $key => $value)
					{											
					echo $key . "<br />";	
					$Definition_Properties = $value['properties'];	
					foreach($Definition_Properties as $key4 => $value4)
						{
						$Definition_Property_Name = $key4;
						echo $Definition_Property_Name . "<br />";
						
						if(isset($value4['description'])){ $Definition_Property_Desc = $value4['description']; } else { $Definition_Property_Desc = ""; }
						if(isset($value4['type'])){ $Definition_Property_Type = $value4['type']; } else { $Definition_Property_Type = ""; }
						if(isset($value4['format'])){ $Definition_Property_Format = $value4['format']; } else { $Definition_Property_Format = ""; }
						
						echo $Definition_Property_Type . "<br />";
						echo $Definition_Property_Desc . "<br />";		
						}						
					//var_dump($value);
					echo "<hr />";											
					}				
					
				}
											
	
			}	
		}	


	});	
?>