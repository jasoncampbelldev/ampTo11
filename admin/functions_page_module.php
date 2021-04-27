<?php
require 'json_db_extended.php';
require 'classes.php';


if($_POST["function"]) {

	$functionName = $_POST["function"];

	if($functionName == "updatePageName") {

		if($_POST["newName"]) {
			$fileName = $_POST["fileName"];
			$pageId = $_POST["pageId"];
			$newName = $_POST["newName"];
			$result = setProperty($fileName,"name",$newName);
			if ($result['status']) {
				echo "File name updated!";
				$result = setTableRowProperty("pages","page_array",$pageId,"name",$newName);
				if ($result['status']) {
					echo "Page array name updated!";
				} else {
					echo "Error: Page array name was not updated";
				}
			} else {
				echo "Error: File name was not updated";
			}
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "updatePageType") {

		if($_POST["type"]) {
			$fileName = $_POST["fileName"];
			$pageId = $_POST["pageId"];
			$type = $_POST["type"];
			$result = setProperty($fileName,"type",$type);
			if ($result['status']) {
				echo "File type updated!";
				$result = setTableRowProperty("pages","page_array",$pageId,"type",$type);
				if ($result['status']) {
					echo "Page array type updated!";
				} else {
					echo "Error: Page array type was not updated";
				}
			} else {
				echo "Error: File type was not updated";
			}
		} else {
			echo "Error: Type cannot be blank ";
		}

	}

	if($functionName == "updatePageDatabase") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$databaseId = $_POST["databaseId"];
			$result = setProperty($fileName,"database",$databaseId);
			if ($result['status']) {
				echo "Database updated! ";
			} else {
				echo "Error: Database not update title (" . $result['data'] . ") ";
			}
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "updatePageUrl") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$url = $_POST["url"];
			$id = $_POST["pageId"];
			$result = submitPageUrl($url,$id);
			if ($result['status']) {
				echo "URL has been added! ";
			} else {
				echo "Error: " . $result['data'];
			}
		} else {
			echo "Error: Page ID cannot be blank ";
		}

	}

	if($functionName == "updatePageSeo") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$seoTitle = $_POST["seoTitle"];
			$result = setProperty($fileName,"seoTitle",$seoTitle);
			if ($result['status']) {
				echo "SEO Title updated! ";
			} else {
				echo "Error: Could not update SEO Title ";
			}

			$seoDescription = $_POST["seoDescription"];
			$result = setProperty($fileName,"seoDescription",$seoDescription);
			if ($result['status']) {
				echo "SEO Description updated! ";
			} else {
				echo "Error: Could not update SEO Description ";
			}

			$pubDate = $_POST["pubDate"];
			$result = setProperty($fileName,"pubDate",$pubDate);
			if ($result['status']) {
				echo "Publish Date updated! ";
			} else {
				echo "Error: Could not update Publish Date ";
			}

		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "updatePageJsonLd") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$collectArray = [];
			foreach ($_POST as $name => $value) {
				if ($name != "fileName" && $name != "function") {
					$collectArray[$name] = $value;
				}
			}
			$result = setProperty($fileName,"jsonLd",$collectArray);
			if ($result['status']) {
				echo "JSON-LD updated! ";
			} else {
				echo "Error: Could not update JSON-LD ";
			}			
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "addMetaTag") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$dataArray = [];
			$dataArray['name'] = $_POST["name"];
			$dataArray['type'] = $_POST["type"];
			$dataArray['value'] = $_POST["value"];
			$dataArray['editor'] = $_POST["editor"];
			$result = setTableRow($fileName,"metaTags",$dataArray);
			if ($result['status']) {
				echo "Meta Tag has been added!";
			}
		} else {
			echo "Error: File name cannot be blank";
		}

	}

	if($functionName == "updateMetaTags") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			foreach ($_POST as $name => $value) {
				if ($name != "fileName" && $name != "function") {
					$dataArray = [];
					$dataArray['value'] = $value;
				    $result = updateTableRow($fileName,"metaTags",$name,$dataArray);
				    if ($result['status']) {
						echo "Meta Tags has been saved!";
					}
				}
			}
		} else {
			echo "Error: File name cannot be blank";
		}

	}

	if($functionName == "addInclude") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$dataArray = [];
			$dataArray['type'] = $_POST["type"];
			$dataArray['url'] = $_POST["url"];
			$dataArray['customElement'] = $_POST["customElement"];
			$result = setTableRow($fileName,"includes",$dataArray);
			if ($result['status']) {
				echo "Include has been added!";
			}
		} else {
			echo "Error: File name cannot be blank";
		}

	}

	if($functionName == "updatePageCSS") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$css = $_POST["css"];
			$result = setProperty($fileName,"css",$css);
			if ($result['status']) {
				echo "CSS updated! ";
			} else {
				echo "Error: Could not update CSS (" . $result['data'] . ") ";
			}
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "updatePageJS") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$js = $_POST["js"];
			$result = setProperty($fileName,"js",$js);
			if ($result['status']) {
				echo "JavaScript updated! ";
			} else {
				echo "Error: Could not update JavaScript (" . $result['data'] . ") ";
			}
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "addPageCategory") {

		if($_POST["pageId"]) {
			$dataArray = [];
			$dataArray['categoryId'] = $_POST["categoryId"];
			$dataArray['pageId'] = $_POST["pageId"];
			$result = setTableRow("taxonomy","category_lookup_array",$dataArray);
			if ($result['status']) {
				echo "Category has been added!";
			}
		} else {
			echo "Error: Page ID cannot be blank";
		}

	}

	if($functionName == "addPageTag") {

		if($_POST["pageId"]) {
			$dataArray = [];
			$dataArray['tagId'] = $_POST["tagId"];
			$dataArray['pageId'] = $_POST["pageId"];
			$result = setTableRow("taxonomy","tag_lookup_array",$dataArray);
			if ($result['status']) {
				echo "Tag has been added!";
			}
		} else {
			echo "Error: Page ID cannot be blank";
		}

	}

	if($functionName == "updatePageCustomFields") {

		if($_POST["fileName"]) {
			$fileName = $_POST["fileName"];
			$collectArray = [];
			foreach ($_POST as $name => $value) {
				if ($name != "fileName" && $name != "function") {
					$collectArray[$name] = $value;
				}
			}
			$result = setProperty($fileName,"customFields",$collectArray);
			if ($result['status']) {
				echo "Custom Fields updated! ";
			} else {
				echo "Error: Could not update Custom Fields ";
			}			
		} else {
			echo "Error: File name cannot be blank ";
		}

	}

	if($functionName == "updateModuleName") {

		if($_POST["newName"]) {
			$fileName = $_POST["fileName"];
			$moduleId = $_POST["moduleId"];
			$newName = $_POST["newName"];
			$result = setProperty($fileName,"name",$newName);
			if ($result['status']) {
				echo "File name updated!";
				$result = setTableRowProperty("modules","module_array",$moduleId,"name",$newName);
				if ($result['status']) {
					echo "Module array name updated!";
				} else {
					echo "Error: Module array name was not updated";
				}
			} else {
				echo "Error: File name was not updated";
			}
		} else {
			echo "Error: Name cannot be blank ";
		}

	}

	if($functionName == "updateModuleType") {

		if($_POST["type"]) {
			$fileName = $_POST["fileName"];
			$moduleId = $_POST["moduleId"];
			$type = $_POST["type"];
			$result = setProperty($fileName,"type",$type);
			if ($result['status']) {
				echo "File type updated!";
				$result = setTableRowProperty("modules","module_array",$moduleId,"type",$type);
				if ($result['status']) {
					echo "Module array type updated!";
				} else {
					echo "Error: Moule array type was not updated";
				}
			} else {
				echo "Error: File type was not updated";
			}
		} else {
			echo "Error: Type cannot be blank ";
		}

	}

	if($functionName == "updateModuleDescription") {

		if($_POST["description"]) {
			$fileName = $_POST["fileName"];
			$description = $_POST["description"];
			$result = setProperty($fileName,"description",$description);
			if ($result['status']) {
				echo "Description has been saved!";
			}
		} else {
			echo "Error: Description cannot be blank";
		}

	}

	if($functionName == "updateModuleFunctionName") {

		if($_POST["functionName"]) {
			$fileName = $_POST["fileName"];
			$name = $_POST["functionName"];
			$result = setProperty($fileName,"functionName",$name);
			if ($result['status']) {
				echo "Function Name has been saved!";
			}
		} else {
			echo "Error: Function Name cannot be blank";
		}

	}

	if($functionName == "addModuleVariable") {

		if($_POST["variableName"]) {
			$moduleId = $_POST["moduleId"];
			$dataArray = [];
			$dataArray['name'] = $_POST["variableName"];
			$dataArray['editor'] = $_POST["variableEditor"];
			$result = setSubTableRow("modules","module_array",$moduleId,"variable_array",$dataArray);
			if ($result['status']) {
				echo "Variable has been saved!";
			}
		} else {
			echo "Error: Variable name cannot be blank";
		}

	}

}


if($_GET["function"]) {

	$functionName = $_GET["function"];


	if($functionName == "updateAmpDisable") {

		if($_GET["value"]) {
			$value = $_GET["value"];
			$fileName = $_GET["fileName"];
			$result = setProperty($fileName,"ampDisabled",$value);
			if ($result['status']) {
				echo "Amp Disabled value was updated";
			}
		} 

	}

	if($functionName == "deleteMetaTag") {

		if($_GET["id"]) {
			$id = $_GET["id"];
			$fileName = $_GET["fileName"];
			$result = deleteTableRow($fileName,"metaTags",$id);
			if ($result['status']) {
				echo "Meta Tag has been deleted";
			}
		} 

	}

	if($functionName == "deleteInclude") {

		if($_GET["id"]) {
			$id = $_GET["id"];
			$fileName = $_GET["fileName"];
			$result = deleteTableRow($fileName,"includes",$id);
			if ($result['status']) {
				echo "Include has been deleted";
			}
		} 

	}

	if($functionName == "deletePageCategory") {

		if($_GET["id"]) {
			$id = $_GET["id"];
			$result = deleteTableRow("taxonomy","category_lookup_array",$id);
			if ($result['status']) {
				echo "Category has been deleted";
			}
		} 

	}

	if($functionName == "deletePageTag") {

		if($_GET["id"]) {
			$id = $_GET["id"];
			$result = deleteTableRow("taxonomy","tag_lookup_array",$id);
			if ($result['status']) {
				echo "Tag has been deleted";
			}
		} 

	}

	if($functionName == "revertPageHtml") {
		if($_GET["id"]) {
			$page = new Page($_GET["id"]);
			$status = $page->revert_elements();
			return $status;
		}
	}

	if($functionName == "deleteModuleVariable") {

		if($_GET["moduleId"]) {
			$moduleId = $_GET["moduleId"];
			$variableId = $_GET["variableId"];
			$result = deleteSubTableRow("modules","module_array",$moduleId,"variable_array",$variableId);
			if ($result['status']) {
				echo "Variable has been deleted";
			}
		} 

	}

	if($functionName == "printModulesJSON") {
		
		$result = getTableRows("modules","module_array");
		if ($result['status']) {

			// Print Modules in JSON Variable
			echo "<script>\n";
			echo "var modules = " . json_encode($result['data']) . ";\n";
			echo "</script>\n";

		}
	}


}


