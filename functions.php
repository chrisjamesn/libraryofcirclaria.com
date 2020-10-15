<?php

//Page navigation off the action pages
function page_redirect($location){

	header("Location: " . $location);
	exit();

}

//HTML escape function
function h($string=""){

	return htmlspecialchars($string);

}

//URL escape function
function u($string=""){

	return urlencode($string);

}

//Database escape function
function d($mysqli, $string){

	return mysqli_real_escape_string($mysqli, $string);

}

//Database connection function
function db_open(){

	$servername="";
	$dbname="";
	$dbusername=""; 
	$dbpassword=""; 
    
	$mysqli= new mysqli($servername, $dbusername, $dbpassword, $dbname);

	if($mysqli->connect_errno){
		echo "Failed to connect to the database: (". $mysqli->connect_errno. ")" . $mysqli->connect_error;
		mysqli_close($mysqli);
		exit;
	}

}

//Database disconnect function
function db_close($res, $mysqli){

	if($res){
    		$res->close();
	}

	if($mysqli){
    		$mysqli->close();
	}

}

//Put the index function here
function libraryIndex(){

	//Database connection
	global $mysqli;
	
	global $continent;

	//Database data selection
	$sql_listContinents="SELECT * FROM continents ORDER BY continent_id;";
	$res_listContinents=mysqli_query($mysqli, $sql_listContinents);
	
	//Database blogs
	$sql_blogs="SELECT * FROM blogs ORDER BY edited_timestamp DESC;";
	$res_blogs=mysqli_query($mysqli, $sql_blogs);
	

	//Page generation, listing the continents pending the visiblility check
	if($res_listContinents){
	    
	    echo "<div class=\"navpane\">
	            <nav>";

		while($list=$res_listContinents->fetch_assoc()){

			if($list["ux"]=="Visible" || $list["ux"]=="visible"){

				$continent_title=ucfirst(d($mysqli, $list["continent_name"]));
				
				echo "<p style=\"margin-bottom:10px\"><a href=\"".$continent."?continent_id=".d($mysqli, $list["continent_id"])."\">".h($continent_title)."</a></p><br>";

			}

		}
		
		echo "</nav>
		        </div>";

	}else{
	   echo "Update in progress. Content coming soon";
	}
	
	//Blogspace
	echo "<div class=\"heading\">
	       <p><img style=\"margin-bottom: 100px\" src=\"remikra/Remikra Standard.jpg\" height=\"600\" width=\"800\" alt=\"Map of Remikra\"></p>"; 
	echo "</div>";       
	       
    if($res_blogs){
        
        while($blog=$res_blogs->fetch_assoc()){
            
            echo "<p><a style=\"font-size:24px\" href=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/blog.php?blog_id=".$blog["blog_id"]."\">".$blog["title"]."</a>
                <br>Posted: ".$blog["timestamp"]."
                <br>Edited: ".$blog["edited_timestamp"]."
                <br>Likes: ".$blog["like_count"]."
                <br>Dislikes: ".$blog["dislike_count"]."</p>";    
            
        }
        
    }
        
        
    
    
	        
	
	//Database disconnect
	$res_listContinents->close();
	$mysqli->close();

}

//The front-end webpage 
function viewContinent(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $viewAttribute;

	//Selected continent
	$continent_id=$_GET["continent_id"];
	
	//Hrefs
	global $continent;

	//Database data selection
	$sql_viewContinent="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_viewContinent=mysqli_query($mysqli, $sql_viewContinent);
	$sql_viewContinentBody="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_viewContinentBody=mysqli_query($mysqli, $sql_viewContinentBody);
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	if($res_viewContinent){

		//The continent title pending the visibility check
		while($title=$res_viewContinent->fetch_assoc()){

			if($title["ux"]=="Visible" || $title["ux"]=="visible"){

				$continent_title=ucfirst(d($mysqli, $title["continent_name"]));

				echo "<div class=\"heading\">
					<h2><a href=\"".$continent."?continent_id=".$continent_id."\">".h($continent_title)."</a></h2>
					</div>";

			}

		}

		echo "<br>";		

		//The navigation toolbar
		if($res_listAttributes){


			echo "<div class=\"navpane\">
				<nav>";

			//The associated attributes
			while($row=$res_listAttributes->fetch_assoc()){

				if($row["ux"]=="Visible" || $row["ux"]=="visible"){

					$attribute=ucfirst($row["attribute_name"]);

					echo "<p><a href=\"".$viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h($attribute)."</a></p><br>";

				}

			}

			echo "</nav>
				</div>";

		}

		//Continent profile body
		while($page=$res_viewContinentBody->fetch_assoc()){
		    
		    if($page["ux"]=="Visible" || $page["ux"]=="visible"){

				//Title, map, and description file paths
				$map_path=$page["map_path"];
				$continent_title=ucfirst($page["continent_name"]);
				$description_path=$page["description_path"];

				//Image and description
				echo "<div class=\"main\">
					<p><img src=\"".$map_path."\" height=\"600\" width=\"800\" alt=\"Map of ".h($continent_title)."\"></p><br>
            				<p>".h(file_get_contents($description_path))."</p><br>";

			}


		}
		
	}	

    //Database disconnect
	$res_viewContinent->close();
	$res_viewContinentBody->close();
	$res_listAttributes->close();
	$mysqli->close();

}

//Front end display of attribute
function viewAttribute(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $viewItem;
	global $viewAttribute;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];

	//Database attribute selection for the page
	$sql_viewAttribute="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_viewAttribute=mysqli_query($mysqli, $sql_viewAttribute);
	$sql_viewAttributeBody="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_viewAttributeBody=mysqli_query($mysqli, $sql_viewAttributeBody);

	//Database attribute selection for the nav pane
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	//Database item selection for the nav pane
	$sql_listItems="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." ORDER BY pg;";

	//The page display
	if($res_viewAttribute){

		//The attribute page header
		while($title=$res_viewAttribute->fetch_assoc()){

			if($title["ux"]=="Visible" || $title["ux"]=="visible"){

				$continent_title=ucfirst($title["continent_name"]);
				$attribute_title=ucfirst($title["attribute_name"]);

				echo "<div class=\"heading\">
					<h2><a href=\"".$viewAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."\">".h($continent_title)." ".h($attribute_title)."</a></h2>
					</div>";

			}

		}

		//The nav pane
		if($res_listAttributes){

			echo "<div class=\"navpane\">
				<nav>";

			//Listed attributes
			while($row=$res_listAttributes->fetch_assoc()){

				if($row["ux"]=="Visible" || $row["ux"]=="visible"){
				    
				    //Style indicator for the selected attribute
				    //if($row["attribute_id"]==$attribute_id){
				        
				    //    echo "</div><div class=\"navpaneSelect\">";    
				        
				   // }

					echo "<p";
					
					//Style indicator for the selected attribute
					if($row["attribute_id"]==$attribute_id){
				        
				        echo " style=\"background-color:#734500;color:#f5f5dc\" ";    
				        
				    }
					
					echo "><a";
					
					//Style indicator for the selected attribute
					if($row["attribute_id"]==$attribute_id){
				        
				        echo " style=\"background-color:#734500;color:#f5f5dc\" ";    
				        
				    }
					
					echo " href=\"".$viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h(ucfirst($row["attribute_name"]))."</a></p><br>";
					
					//Style indicator for the selected attribute
					//Do this in the HTML itself, using a style parameter in the element
					//if($row["attribute_id"]==$attribute_id){
				        
				     //   echo "</div><div class=\"navpane\">";    
				        
				   // }
					
					//Listed items under the selected attribute
					
					if($row["attribute_id"]==$attribute_id){
					    
					    $res_listItems=mysqli_query($mysqli, $sql_listItems);
					
					    if($res_listItems){
					        
					        echo "</div><div class=\"navpanesub\">";

					        while($item=$res_listItems->fetch_assoc()){

					    	    if($item["ux"]=="Visible" || $item["ux"]=="visible"){

					    		    if($item["attribute_id"]==$attribute_id){

					    			    echo"<p><a href=\"".$viewItem."?continent_id=".u($item["continent_id"])."&attribute_id=".u($item["attribute_id"])."&pg=".u($item["pg"])."\">".h(ucfirst($item["title"]))."</a></p>";

						    	    }

						        }

				    	    }
                            
                            echo "</div><div class=\"navpane\">";
				        }

					}

				}
				    

			}

			echo "</nav>
			        </div>";

		}

		//The image and body of the attribute page
		while($page=$res_viewAttributeBody->fetch_assoc()){

			if($page["ux"]=="Visible" || $page["ux"]=="visible"){

				$continent_title=ucfirst($page["continent_name"]);
				$description_path=$page["description_path"];

				echo "<div class=\"main\">
					<img src=\"".$page["image_path"]."\" alt=\"Map of ".h($continent_title)."\" height=\"600\" width=\"800\"><br>
					<p>".h(file_get_contents($description_path))."</p>";

			}
			

		}
		
	}

    //Database disconnect
    if($res_viewAttribute){
        
        $res_viewAttribute->close();    
        
    }
    
    if($res_viewAttributeBody){
        
        $res_viewAttributeBody->close();    
        
    }
    
    if($res_listAttributes){
        
        $res_listAttributes->close();    
        
    }
    
    if($res_listItems){
        
        $res_listItems->close();    
        
    }
    
    $mysqli->close();

}

//Front end of the item page
function viewItem(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $viewAttribute;
	global $viewItem;

	//Selected continent, attribute, and page number
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	$pg=$_GET["pg"];

	//Selected continent attribute items for the page
	$sql_viewItem="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND pg='".d($mysqli, $pg)."';";
	$res_viewItem=mysqli_query($mysqli, $sql_viewItem);
	$sql_viewItemBody="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND pg='".d($mysqli, $pg)."';";
	$res_viewItemBody=mysqli_query($mysqli, $sql_viewItemBody);

	//Selected continent attributes for the nav pane
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	//Selected continent attribute items for the nav pane
	$sql_listItems="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." ORDER BY pg;";
	
	//Obtain the page number array
	$sql_pages="SELECT * FROM items WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id.";";
	$res_pages=mysqli_query($mysqli, $sql_pages);
    $maxPg=mysqli_num_rows($res_pages);

	if($res_viewItem){

		//Page heading
		while($heading=$res_viewItem->fetch_assoc()){

			if($heading["ux"]=="Visible" || $heading["ux"]=="visible"){
                
                $continent_title=ucfirst($heading["continent_name"]);
                $attribute_title=ucfirst($heading["attribute_name"]);
				$item_title=ucfirst($heading["title"]);

				echo "<div class=\"heading\">
					<h2><a href=\"".$viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&pg=".$pg."\">".$continent_title." ".$attribute_title.": ".$item_title."</a></h2>
					</div>";

			}

		}

		//The nav pane
		if($res_listAttributes){

			echo "<div class=\"navpane\">
				<nav>";

			//Listed attributes
			while($row=$res_listAttributes->fetch_assoc()){

				if($row["ux"]=="Visible" || $row["ux"]=="visible"){
				    
				    //Style indicator for the selected attribute
				    //if($row["attribute_id"]==$attribute_id){
				        
				        //echo "</div><div class=\"navpaneSelect\">";     
				        
				    //}

					echo "<p";
					
					//Style indicator for the selected attribute
				    if($row["attribute_id"]==$attribute_id){
				        
				        echo " style=\"background-color:#734500;color:#f5f5dc\" ";     
				        
				    }
					
					echo "><a ";
					
					//Style indicator for the selected attribute
				    if($row["attribute_id"]==$attribute_id){
				        
				        echo " style=\"background-color:#734500;color:#f5f5dc\" ";     
				        
				    }
					
					echo "href=\"".$viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h(ucfirst($row["attribute_name"]))."</a></p><br>";
					
					//Style indicator for the selected attribute
				    //if($row["attribute_id"]==$attribute_id){
				        
				        //echo "</div><div class=\"navpane\">";     
				        
				    //}
					
					//Listed items under the selected attribute
					
					if($row["attribute_id"]==$attribute_id){
					    
					    $res_listItems=mysqli_query($mysqli, $sql_listItems);
					
					    if($res_listItems){
					        
					        echo "</div><div class=\"navpanesub\">";

					        while($item=$res_listItems->fetch_assoc()){

					    	    if($item["ux"]=="Visible" || $item["ux"]=="visible"){

					    		    if($item["attribute_id"]==$attribute_id){
					    		        
					    		        //Style indicator for the selected item
					    		        //if($item["pg"]==$pg){
					    		            
					    		       //     echo "</div><div class=\"navpanesubSelect\">";    
					    		            
					    		       // }

					    			    echo"<p";
					    			    
					    			    //Style indicator for the selected item
					    		        if($item["pg"]==$pg){
					    		            
					    		            echo " style=\"background-color:#734500;color:#f5f5dc\" ";    
					    		            
					    		        }
					    			    
					    			    echo "><a";
					    			    
					    			    //Style indicator for the selected item
					    		        if($item["pg"]==$pg){
					    		            
					    		            echo " style=\"background-color:#734500;color:#f5f5dc\" ";    
					    		            
					    		        }
					    		        
					    		        echo " href=\"".$viewItem."?continent_id=".u($item["continent_id"])."&attribute_id=".u($item["attribute_id"])."&pg=".u($item["pg"])."\">".h(ucfirst($item["title"]))."</a></p>";
					    			    
					    			    //Style indicator for the selected item
					    		        //if($item["pg"]==$pg){
					    		            
					    		        //    echo "</div><div class=\"navpanesub\">";    
					    		            
					    		       // }

						    	    }

						        }

				    	    }
				    	    
				    	    echo "</div><div class=\"navpane\">";

				        }

					}

				}
				    

			}

			echo "</nav>
			        </div>";

		}

		//Item page body
		while($page=$res_viewItemBody->fetch_assoc()){
		    
		    //Title, map, and description file paths
			$image_path=$page["image_path"];
			$item=ucfirst($page["title"]);
			$description_path=$page["description_path"];

			if($page["ux"]=="Visible" || $page["ux"]=="visible"){

				//Pages
			    $current=$page["pg"];
			    $previous=$current-1;
			    $next=$current+1;

			    //Image and description
			    echo "</div><div class=\"main\">
				    <p><img src=\"".$image_path."\" ";
			//	echo "height=\"600\" width=\"800\" ";
				echo "alt=\"".$item."\"></p><br>
				    <p>";

				if($previous>=1){
				    
				    echo "<a href=\"".$viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&pg=".$previous."\">&#8592;</a>";   
				    
				}
				
				if($next<=$maxPg){
				    
				    echo "<a href=\"".$viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&pg=".$next."\">&#8594;</a>";    
				    
				}
				
				echo "</p>";
				
				
            	echo "<p>".str_replace("\n", " ", nl2br(h(file_get_contents("".$description_path.""))))."</p><br>";

			}			

		}
		
	}

    //Database disconnect
    if($res_viewItem){
        
        $res_viewItem->close();    
        
    }
    
    if($res_viewItemBody){
        
        $res_viewItemBody->close();    
        
    }
    
    if($res_listAttributes){
        
        $res_listAttributes->close();    
        
    }
    
    if($res_listItems){
        
        $res_listItems->close();    
        
    }
    
    $mysqli->close();

}

?>