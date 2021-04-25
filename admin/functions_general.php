<?php
require 'json_db_extended.php';
require 'classes.php';
$settings = new Settings();
$GLOBALS['urlBase'] = $settings->get_urlBase();


if ($_POST["function"]) {

	$functionName = $_POST["function"];

	if ($functionName == "addPage") {

		if($_POST["name"]) {
			$pageName = $_POST["name"];
			$pageType = $_POST["type"];
			$result = createPage($pageName,$pageType);
			if($result['status']){
				echo "Page has been added!";
			}
		} else {
			echo "Error: Page name cannot be blank";
		}

	}

	if ($functionName == "copyPage") {

		if ($_POST["pageName"]) {
			$pageName = $_POST["pageName"];
			$pageFile = $_POST["pageFile"];
			$result = copyPage($pageFile,$pageName);
			if ($result['status']){
				echo "Page has been copied!";
			} else {
				echo $result['data'];
			}
		} else {
			echo "Error: Page name cannot be blank";
		}

	}

	if ($functionName == "addModule") {

		if ($_POST["name"]) {
			$moduleName = $_POST["name"];
			$moduleType = $_POST["type"];
			$result = createModule($moduleName,$moduleType);
			if ($result['status']){
				echo "Module has been added!";
			}
		} else {
			echo "Error: Module name cannot be blank";
		}

	}

	if ($functionName == "addDatabase") {

		if ($_POST["name"]) {
			$databaseName = $_POST["name"];
			$result = createDatabase($databaseName);
			if ($result['status']){
				echo "Database has been added!";
			}
		} else {
			echo "Error: Database name cannot be blank";
		}

	}

	if ($functionName == "addCategory") {

		if ($_POST["name"]) {
			$url = $_POST["url"];
			$name = $_POST["name"];
			$urlCheck = true;
			$curUrlRowId = "";
			$result = getTableRows("taxonomy","category_array");
			if ($result['status']) {
				foreach ($result['data'] as $categoryKey => $category) {
					if ($category['url'] == $url ) {
						$urlCheck = false;
					}
				}
			}
			if ($urlCheck) {
				$dataArray = [];
				$dataArray['name'] = $name;
				$dataArray['url'] = $url;
				$dataArray['parentId'] = $_POST["parentId"];
				$result = setTableRow('taxonomy','category_array',$dataArray);
				if ($result['status']){
					echo "Category has been added!";
				}
			} else {
				echo "Error: Category URL is already in use";
			}
		} else {
			echo "Error: Category name cannot be blank";
		}

	}

	if ($functionName == "addTag") {

		if ($_POST["name"]) {
			$url = $_POST["url"];
			$name = $_POST["name"];
			$urlCheck = true;
			$curUrlRowId = "";
			$result = getTableRows("taxonomy","tag_array");
			if ($result['status']) {
				foreach ($result['data'] as $tagKey => $tag) {
					if ($tag['url'] == $url) {
						$urlCheck = false;
					}
				}
			}
			if ($urlCheck) {
				$dataArray = [];
				$dataArray['name'] = $name;
				$dataArray['url'] = $url;
				$result = setTableRow('taxonomy','tag_array',$dataArray);
				if ($result['status']){
					echo "Tag has been added!";
				}
			} else {
				echo "Error: Tag URL is already in use";
			}
		} else {
			echo "Error: Tag name cannot be blank";
		}

	}

	if ($functionName == "updateCategory") {

		if ($_POST["name"]) {
			$id = $_POST["id"];
			$url = $_POST["url"];
			$name = $_POST["name"];
			$urlCheck = true;
			$curUrlRowId = "";
			$result = getTableRows("taxonomy","category_array");
			if ($result['status']) {
				foreach ($result['data'] as $categoryKey => $category) {
					if ($category['url'] == $url && $categoryKey != $id) {
						$urlCheck = false;
					}
				}
			}
			if ($urlCheck) {
				$dataArray = [];
				$dataArray['name'] = $name;
				$dataArray['url'] = $url;
				$dataArray['parentId'] = $_POST["parentId"];
				$result = updateTableRow('taxonomy','category_array',$id,$dataArray);
				if ($result['status']){
					echo "Category has been updated!";
				}
			} else {
				echo "Error: Category URL is already in use";
			}
		} else {
			echo "Error: Category name cannot be blank";
		}

	}

	if ($functionName == "updateTag") {

		if ($_POST["name"]) {
			$id = $_POST["id"];
			$url = $_POST["url"];
			$name = $_POST["name"];
			$urlCheck = true;
			$curUrlRowId = "";
			$result = getTableRows("taxonomy","tag_array");
			if ($result['status']) {
				foreach ($result['data'] as $tagKey => $tag) {
					if ($tag['url'] == $url && $tagKey != $id) {
						$urlCheck = false;
					}
				}
			}
			if ($urlCheck) {
				$dataArray = [];
				$dataArray['name'] = $name;
				$dataArray['url'] = $url;
				$result = updateTableRow('taxonomy','tag_array',$id,$dataArray);
				if ($result['status']) {
					echo "Tag has been updated!";
				}
			} else {
				echo "Error: Tag URL is already in use";
			}
		} else {
			echo "Error: Tag name cannot be blank";
		}

	}

	if ($functionName == "updateUrlBase") {

		if ($_POST["urlBase"]) {
			$urlBase = $_POST["urlBase"];
			$result = setProperty('settings','urlBase',$urlBase);
			if ($result['status']) {
				echo "URL Base has been updated!";
			} else {
				echo "Error: URL Base failed to update";
			}
		} else {
			echo "Error: URL Base cannot be blank";
		}

	}

	if ($functionName == "updateGlobalCSS") {

		if ($_POST["globalCSS"]) {
			$globalCSS = $_POST["globalCSS"];
			$result = setProperty('settings','globalCSS',$globalCSS);
			if ($result['status']) {
				echo "CSS has been updated!";
			} else {
				echo "Error: CSS failed to update";
			}
		} else {
			echo "Error: CSS name cannot be blank";
		}

	}

	if ($functionName == "addGlobalInclude") {

		if ($_POST["url"]) {
			$dataArray = [];
			$dataArray['type'] = $_POST["type"];
			$dataArray['url'] = $_POST["url"];
			$dataArray['customElement'] = $_POST["customElement"];
			$result = setTableRow('settings',"globalIncludes",$dataArray);
			if ($result['status']) {
				echo "Include has been added!";
			}
		} else {
			echo "Error: URL name cannot be blank";
		}

	}


}


if ($_GET["function"]) {

	$functionName = $_GET["function"];


	if ($functionName == "deletePage") {

		if ($_GET["pageID"]) {
			$pageID = $_GET["pageID"];
			$result = deletePage($pageID);
			if ($result['status']) {
				echo "Page has been deleted";
			}
		} 

	}

	if ($functionName == "deleteModule") {

		if ($_GET["moduleID"]) {
			$moduleID = $_GET["moduleID"];
			$result = deleteModule($moduleID);
			if ($result['status']) {
				echo "Module has been deleted";
			}
		} 

	}

	if ($functionName == "deleteDatabase") {

		if ($_GET["databaseID"]) {
			$databaseID = $_GET["databaseID"];
			$result = deleteDatabase($databaseID);
			if ($result['status']) {
				echo "Database has been deleted";
			}
		} 

	}

	if ($functionName == "deleteCategory") {

		if ($_GET["categoryId"]) {
			$categoryId = $_GET["categoryId"];
			// delete children
			$result = getTableRows("taxonomy","category_array");
			if ($result['status']) {
				$allCategories = $result['data'];
				function deleteChildren($allCategories,$parentId)  {
					foreach ($allCategories as $categoryKey => $category) {
						if ($category['parentId'] == $parentId) {
							deleteLookups("taxonomy","category_lookup_array","categoryId",$categoryKey);
							deleteTableRow("taxonomy","category_array",$categoryKey);
							deleteChildren($allCategories,$categoryKey);
						}
					}
				}
				deleteChildren($allCategories,$categoryId);
			}
			echo "<br>Children deleted";
			deleteLookups("taxonomy","category_lookup_array","categoryId",$categoryId);
			$result = deleteTableRow("taxonomy","category_array",$categoryId);
			if ($result['status']) {
				echo "<br>Category has been deleted";
			}
		} 

	}

	if ($functionName == "deleteTag") {

		if ($_GET["tagId"]) {
			$tagId = $_GET["tagId"];
			deleteLookups("taxonomy","tag_lookup_array","tagId",$tagId);
			$result = deleteTableRow("taxonomy","tag_array",$tagId);
			if ($result['status']) {
				echo "Tag has been deleted";
			}
		} 

	}


	if ($functionName == "deleteGlobalInclude") {
		if ($_GET["id"]) {
			$id = $_GET["id"];
			$result = deleteTableRow('settings',"globalIncludes",$id);
			if ($result['status']) {
				echo "Include has been deleted";
			}
		} 

	}

	if ($functionName == "printPageList") {

		$result = getTableRows("pages","page_array");
		if ($result['status']) {

			$pageArray = $result['data'];
			$filterType = $_GET["filterType"];
			$sortBy = $_GET["sortBy"];			

			if ($filterType && $filterType !== "all") {
				$pageArray = filterMultiArray($pageArray,'type',$filterType);
			}

			if ($sortBy == "alphabetical") {
          		$pageArray = sortMultiArray($pageArray,'name');
			} elseif ($sortBy == "orderDesc") {
				// do nothing
			} else {
				$pageArray = array_reverse($pageArray);
			}

			echo '<ul class="itemList">';
			foreach ($pageArray as $key => $page) {
				echo '<li class="lightBorderBottom">';
			    echo '<span class="itemTitle">' . $page['name'] . '</span> ';
			    echo '<span class="itemType italic">(' . $page['type'] . ')</span> ';
			    echo '<span class="itemButtons">';
				echo ' <span class="status"></span>';
			    echo ' <a class="button" href="edit_page.php?id=' . $key . '">Edit</a>';
			    echo ' <a class="button publishPage" href="javascript:;" data-id="' . $key . '">Publish</a>';
			    echo ' <a class="button copyPage" href="javascript:;" data-file="' . $page['file'] . '">Copy</a>';
			    echo ' <a class="button bgRed deletePage" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$page['name']) . '">Delete</a>';
			    echo '</span>';
			    echo "</li>\n";
			}
			echo "</ul>\n";
		}

	}

	if ($functionName == "printModuleList") {

		$result = getTableRows("modules","module_array");
		if ($result['status']) {

			$moduleArray = $result['data'];
			$filterType = $_GET["filterType"];
			$sortBy = $_GET["sortBy"];			

			if ($filterType && $filterType !== "all") {
				$moduleArray = filterMultiArray($moduleArray,'type',$filterType);
			}

			if ($sortBy == "alphabetical") {
          		$moduleArray = sortMultiArray($moduleArray,'name');
			} elseif ($sortBy == "orderDesc") {
				// do nothing
			} else {
				$moduleArray = array_reverse($moduleArray);
			}

			echo '<ul class="itemList">';
			foreach ($moduleArray as $key => $module) {
				echo '<li class="lightBorderBottom">';
			    echo '<span class="itemTitle">' . $module['name'] . '</span> ';
			    echo '<span class="itemType italic">(' . $module['type'] . ')</span> ';
			    echo '<span class="itemButtons">';
			    echo ' <a class="button" href="edit_module.php?id=' . $key . '">Edit</a>';
			    echo ' <a class="button bgRed deleteModule" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$module['name']) . '">Delete</a>';
			    echo '</span>';
			    echo "</li>\n";
			}
			echo "</ul>\n";
		}

	}

	if ($functionName == "printDatabaseList") {

		$result = getTableRows("databases","database_array");
		if ($result['status']) {

			$databaseArray = $result['data'];
			$sortBy = $_GET["sortBy"];			

			if ($sortBy == "alphabetical") {
          		$databaseArray = sortMultiArray($databaseArray,'name');
			} elseif ($sortBy == "orderDesc") {
				// do nothing
			} else {
				$databaseArray = array_reverse($databaseArray);
			}

			echo '<ul class="itemList">';
			foreach ($databaseArray as $key => $row) {
				echo '<li class="lightBorderBottom">';
			    echo '<span class="itemTitle">' . $row['name'] . '</span>';
			    echo '<span class="itemButtons">';
			    echo ' <a class="button" href="edit_database.php?id=' . $key . '">Edit</a>';
			    echo ' <a class="button bgRed deleteDatabase" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$row['name']) . '">Delete</a>';
			    echo '</span>';
			    echo "</li>\n";
			}
			echo "</ul>\n";
		}

	}

	if ($functionName == "printCategoryList") {

		$result = getTableRows("taxonomy","category_array");
		if ($result['status']) {
			echo '<ul class="itemList">';
			function printCategory($parentId,$array,$parentName) {
				foreach ($array as $key => $row) {
					if ($row['parentId'] == $parentId) {
						if ( $parentId != "") {
							echo '<ul class="categoryChildren">';
						}
						echo "<li>";
					    echo '<span class="itemTitle">' . $row['name'] . ' ';
					    echo '(' . $row['url'] . ')</span> ';
					    echo ' <a class="button editCategory" href="javascript:;" data-id="' . $key . '">Edit</a>';
					    echo ' <a class="button bgRed deleteCategory" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$row['name']) . '">Delete</a>';
					    echo "</li>\n";
					    printCategory($key,$array,$row['name']);
						if ( $parentId != "") {
							echo "</ul>\n";
						}
					}
				}
			}
			$data = sortMultiArray($result['data'],'name');
			printCategory("",$data,"");
			echo "</ul>\n";
			echo '<script>var categoryList =' . json_encode($result['data']) . ';</script>';
		}

	}

	if ($functionName == "printTagList") {

		$result = getTableRows("taxonomy","tag_array");
		if ($result['status']) {
			$data = sortMultiArray($result['data'],'name');
			echo '<ul class="itemList">';
			foreach ($data as $key => $row) {
				echo "<li>";
			    echo '<span class="itemTitle">' . $row['name'] . ' ';
			    echo '(' . $row['url'] . ')</span> ';
			    echo ' <a class="button editTag" href="javascript:;" data-id="' . $key . '">Edit</a>';
			    echo ' <a class="button bgRed deleteTag" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$row['name']) . '">Delete</a>';
			    echo "</li>\n";
			}
			echo "</ul>\n";
			echo '<script>var tagList =' . json_encode($result['data']) . ';</script>';
		}

	}


	if ($functionName == "printImageList") {

		if ($_GET["inputId"]) {
			$inputId = $_GET["inputId"];
			$start = $_GET["start"];
			$end = $start + 10; // number of images per incrimate after +

			$folder = "/images/";
	        $dir = "../images/";
	        $imageSizeArray = ['thumbs','medium','large'];

	        $files = scandir($dir);
	        $fileArray = [];

	        $i = 0;
	        foreach ($files as $file) {
	            if ($file != "." && $file != ".." && !in_array($file,$imageSizeArray)) {
	                $fileDate = (filemtime($dir . $file) + $i);
	                $fileArray[$fileDate] = $file;
	                $i++;
	            }
	        }
	        krsort($fileArray);

	        $count = count($fileArray);
	        $i = 0;
	        foreach ($fileArray as $fileKey => $file) {
	        	if ($i >= $start && $i < $end) {
		            echo '<li>';
		            echo '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="lazy" data-src="' . $dir . "thumbs/" . $file . '" alt="image ' . $i . '"/><p>' . $file . '</p>';
		            echo '<select class="imageSize">';
		            echo '<option value="' . $GLOBALS['urlBase'] . $folder . $file . '">Original</option>';
		            foreach ($imageSizeArray as $imageSize) {
		            	echo '<option value="' . $GLOBALS['urlBase'] . $folder . $imageSize .'/' . $file . '">' . $imageSize . '</option>';
		        	}
		            echo '</select>';
		            echo '<a class="button" href="javascript:;" onclick="chooseImage(this)" data-input-destination="' . $inputId . '">Add</a>';
		            echo '</li>';
		        }
		        $i++;
	        }
	        if ($count > $end) {
	        	echo "<li>";
	        	echo '<p class="center"><a class="button" href="javascript:;" onclick="loadMoreImages(this,' . $end . ')" data-input-id="' . $inputId . '">Load <br>More</a></p>';
	        	echo "</li>";
	        }

	    } else {
	    	echo "Input ID cannot be empty.";
	    }
    }

}

?>