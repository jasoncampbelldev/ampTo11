<?php
require 'json_db.php';

function createPage($name,$type) {

	// create page ID with unique ID
	$uniqId = uniqid();
	$pageId = "page_" . $uniqId;

	// make page file
	$result = createFile($pageId);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to create page file');
	}

	// set page name
	$result = setProperty($pageId,"name",$name);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write name to file');
	}

	// set page type
	$result = setProperty($pageId,"type",$type);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write type to file');
	}
	
	// add empty element
	$dataArray = array();
	$dataArray['type'] = "";	
	$result = setTableRow($pageId,"elements",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write element to file');
	}

	// add page to page index file
	$dataArray = array();
	$dataArray['name'] = $name;
	$dataArray['type'] = $type;
	$dataArray['file'] = $pageId;
	$result = setTableRow("pages","page_array",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write page to page index');
	}

	return array('status'=>true,'data'=>$pageId);

}
// Creates Page //
// Example: //
//$result = createPage("test page");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}

function copyPage($pageFile,$newPageName) {

	// create new page ID with unique ID
	$uniqId = uniqid();
	$newPageId = "page_" . $uniqId;

	// get original page object
	$result = getFileObj($pageFile);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get original page object');
	}
	$data = $result['data'];

	// make page file and return new file name
	$result = createFile($newPageId);
	if (!$result['status'] ) {
		return array('status'=>false,'data'=>'failed to create page file');
	}

	// set original page object into new page file
	$result = setFileObj($newPageId,$data);
	if (!$result['status'] ) {
		return array('status'=>false,'data'=>'failed to set object in page file');
	}

	// add new page to page index file
	$dataArray['name'] = $newPageName;
	$dataArray['type'] = $data['type'];
	$dataArray['file'] = $newPageId;
	$result = setTableRow("pages","page_array",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to add to page index');
	}

	// set name in new page file
	$result = setProperty($newPageId,"name",$newPageName);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to set page name in new file');
	}

	return array('status'=>true);

}

function createModule($name,$type) {

	// create module ID with unique ID
	$uniqID = uniqid();
	$moduleID = "module_" . $uniqID;

	// make module data file
	$result = createFile($moduleID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to create module file');
	}

	// set name property
	$result = setProperty($moduleID,"name",$name);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write name to file');
	}

	// add empty element
	$dataArray = array();
	$dataArray['type'] = "";		
	$result = setTableRow($moduleID,"elements",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write element to file');
	}

	// add module to module index file
	$dataArray['name'] = $name;
	$dataArray['type'] = $type;
	$dataArray['file'] = $moduleID;
	$result = setTableRow("modules","module_array",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write module to module index');
	}

	return array('status'=>true,'data'=>$moduleID);

}
// Creates Module //
// Example: //
//$result = createModule("test module","html");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function createDatabase($name) {

	// create database ID with unique ID
	$uniqID = uniqid();
	$databaseID = "database_" . $uniqID;

	// make DB file
	$result = createFile($databaseID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to create database file');
	}

	// set DB name
	$result = setProperty($databaseID,"name",$name);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write name to file');
	}

	// add URL feild to DB
	$dataArray['name'] = "url";
	$dataArray['editor'] = "text";		
	$result = setTableRow($databaseID,'field_array',$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write URL field to database file');
	}
	
	// add DB to index DB file
	$dataArray['name'] = $name;
	$dataArray['file'] = $databaseID;
	$result = setTableRow("databases","database_array",$dataArray);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to write database to database index');
	}

	return array('status'=>true,'data'=>$databaseID);


}
// Creates Database //
// Example: //
//$result = createDatabase("test database");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function deletePage($pageId) {

	// get page file name
	$result = getTableRow("pages","page_array",$pageId);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get page file name');
	}
	$pageFile = $result['data']['file'];

	// delete from page from index	
	$result = deleteTableRow("pages","page_array",$pageId);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete page from index');
	}

	// delete page data file
	$result = deleteFile($pageFile);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete page file');
	}

	return array('status'=>true,'data'=>$pageId);

}
// Deletes Page
// Example:
//$result = deletePage("5ef2ee59d7858");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function deleteModule($moduleID) {

	// get module file name
	$result = getTableRow("modules","module_array",$moduleID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get module file name');
	}
	$moduleFile = $result['data']['file'];

	// delete module from index
	$result = deleteTableRow("modules","module_array",$moduleID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete module from index');
	}

	// delete module data file
	$result = deleteFile($moduleFile);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete module file');
	}

	return array('status'=>true,'data'=>$moduleID);	

}
// Deletes Module
// Example:
//$result = deleteModule("5ef2ee59d7858");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function deleteDatabase($databaseID) {

	// get DB file
	$result = getTableRow("databases","database_array",$databaseID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get database file name');
	}
	$databaseFile = $result['data']['file'];

	// delete DB from index
	$result = deleteTableRow("databases","database_array",$databaseID);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete database in database_array');
	}

	// delete DB file
	$result = deleteFile($databaseFile);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to delete database file');
	}

	return array('status'=>true,'data'=>$databaseID);

}
// Deletes Database
// Example:
//$result = deleteDatabase("5ef2ee59d7858");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}

function deleteLookups($file,$array,$key,$id) {
	$result = getTableRows($file,$array);
	if ($result['status']) {
		$allLookups = $result['data'];
		print_r($allLookups);
		foreach ($allLookups as $lookupKey => $lookup) {
			echo "key: " . $lookup[$key] . " id:" . $id . "<br>";
			if ($lookup[$key] == $id) {
				echo "delete lookup <br>";
				deleteTableRow($file,$array,$lookupKey);
			}
		}
	}
}


function reorderTableRows($file,$table,$parentId,$elementId,$orderNum,$reorderType) {

	// get all elements
	$result = getTableRows($file,$table);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get page file name');
	}

	// loop through elements
	foreach ($result['data'] as $rowId => $row) {
		$rowOrderNum = $row['orderNum'];
		$dataArray = [];
		$update = false;

		// if element is child of parent element process
		if ($row['parentId'] == $parentId) {

			if ($reorderType == "top") {
				if ($rowOrderNum >= $orderNum && $rowId != $elementId) {
					$dataArray['orderNum'] = $rowOrderNum + 1;
					$update = true;
				}
			} elseif ($reorderType == "moveUp") {
				if ($rowOrderNum == $orderNum && $rowId != $elementId) {
					$dataArray['orderNum'] = $rowOrderNum + 1;
					$update = true;
				}				
			} elseif ($reorderType == "moveDown") {
				if ($rowOrderNum == $orderNum && $rowId != $elementId) {
					$dataArray['orderNum'] = $rowOrderNum - 1;
					$update = true;
				}				
			} elseif ($reorderType == "delete") {
				if ($rowOrderNum >= $orderNum) {
					$dataArray['orderNum'] = $rowOrderNum - 1;
					$update = true;
				}				
			} elseif ($reorderType == "add") {
				if ($rowOrderNum >= $orderNum  && $rowId != $elementId) {
					$dataArray['orderNum'] = $rowOrderNum + 1;
					$update = true;
				}				
			}

			// update row if needed
			if ($update) {
				updateTableRow($file,$table,$rowId,$dataArray);
				$update = false;
			}
		}
	}
	return array('status'=>true);

}
// Example:
//$reorderElements = reorderTableRows($pageFile,"elements",$parentId,$elementId,$orderNum,"top");
//if ($reorderElements['status']) {
//	echo "Elements have been reordered!";
//}

function dragDropElement($containerFile,$elementId,$oldParentId,$oldOrderNum,$newParentId,$newOrderNum) {
	//echo $containerFile . ", " . $elementId . ", " . $oldParentId . ", " . $oldOrderNum . ", " . $newParentId . ", " . $newOrderNum;

	$result = reorderTableRows($containerFile,"elements",$oldParentId,$elementId,$oldOrderNum,"delete");
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to reorder old parent');
	}

	$result = reorderTableRows($containerFile,"elements",$newParentId,$elementId,$newOrderNum,"add");
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to reorder new parent');
	}

	return array('status'=>true);

}


function submitPageUrl($url,$id) {

	$urlCheck = true;
	$sameUrl = false;

	// get page array from index
	$result = getTableRows("pages","page_array");
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get page array');
	}
	// check for URL value in array
	foreach ($result['data'] as $page_key => $page) {
		if ($page['url'] == $url) {
			$urlCheck = false;
			if ($page_key == $id) {
				$sameUrl = true;
			}
		}
	}

	// if the URL is the same as the old one return early
	if ($sameUrl) {
		return array('status'=>true,'data'=>'Same URL');
	}

	// if the URL is alreay taken return early
	if (!$urlCheck) {
		return array('status'=>false,'data'=>'URL is already registered');
	}
		
	// set URL property for page in index
	$result = setTableRowProperty("pages","page_array",$id,"url",$url);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'Unable to register URL. Try again.');
	}

	return array('status'=>true);

}

function checkUniqueField($databaseFile,$fieldName,$fieldValue) {

	$fieldCheck = true;

	// get entry array
	$result = getTableRows($databaseFile,"entry_array");

	// check for value in fields
	if ($result['status']) {
		foreach ($result['data'] as $entry_key => $entry) {
			if ($entry[$fieldName] == $fieldValue) {
				$fieldCheck = false;
			}
		}
	}
	
	// if field value already exists return early
	if (!$fieldCheck) {
		return array('status'=>false,'data'=>'Value already exists for "' . $fieldName . '" in another entry');
	}

	return array('status'=>true);
}

function submitUniqueField($databaseFile,$entryId,$fieldName,$fieldValue) {

	$fieldCheck = true;
	$sameValue = false;

	// get entry array
	$result = getTableRows($databaseFile,"entry_array");
	if (!$result['status']) {
		return array('status'=>false,'data'=>'failed to get entry array');
	}
	// check for value in fields
	foreach ($result['data'] as $entry_key => $entry) {
		if ($entry[$fieldName] == $fieldValue) {
			$fieldCheck = false;
			if ($entry_key == $entryId) {
				$sameValue = true;
			}
		}
	}

	// if new value is the same as the old value return early
	if ($sameValue) {
		return array('status'=>true,'data'=>'Same feild value entered for ' . $fieldName);
	}

	// if field value already exists return early
	if (!$fieldCheck) {
		return array('status'=>false,'data'=>'Value already exists for "' . $fieldName . '" in another entry');
	}

	// set value in entry
	$result = setTableRowProperty($databaseFile,"entry_array",$entryId,$fieldName,$fieldValue);
	if (!$result['status']) {
		return array('status'=>false,'data'=>'Unable to update ' . $fieldName . '  value. Try again.');
	}

	return array('status'=>true);

}

function toLowerCase($a) { 
	return strtolower($a); 
}
	
function sortMultiArray($multiArray,$sortKey) {
	if ($multiArray) {
		$sortArray = array_map("toLowerCase",array_column($multiArray, $sortKey));
		array_multisort($sortArray, SORT_ASC, $multiArray);
		return $multiArray;
	}
}

function filterMultiArray($multiArray,$filterKey,$filterValue) {
	$filteredArray = [];
	foreach ($multiArray as $key => $row) {
		if ($row[$filterKey] == $filterValue) {
			$filteredArray[$key] = $row;
		}
	}
	return $filteredArray;
}

function replaceContentDBTokens($content,$dbFields) {
	if ($dbFields) {
		foreach ($dbFields as $dbFieldKey => $dbField) {
			// replace {{...}} with DB entry field
			$content = str_replace("{{" . $dbFieldKey . "}}",$dbField,$content);
		}						
	}
	return $content;
}

?>
