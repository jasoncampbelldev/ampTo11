<?php

//echo "test<br><br>";

function setProperty($fileName,$key,$value) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	if ($jsonArray === null) {
	     $jsonArray = array();   
	}

	$jsonArray[$key] = $value;

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$jsonArray);

}
//Sets property in JSON file
//Example:
//$result = setProperty("test","title","home page");
//if ($result['status']) {
//	echo $result['data']['type'] . "<br><br>";
//}


function setTableRow($fileName,$table,$array) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	if ($jsonArray === null) {
	     $jsonArray = array();   
	}

	if ($jsonArray[$table] === null) {
		$jsonArray[$table] = array();

	}

	$uniqID = uniqid();
	$jsonArray[$table][$uniqID] = $array;

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$uniqID);

}
//Sets row of a table. Creates table is it doesn't not exist
//Example:
//$dataArray = array();
//$dataArray['name'] = "Jason";
//$dataArray['description'] = "Hello World";
//$result = setTableRow("test","pages",$dataArray);
//if ($result['status']) {
//	echo $result['data'] . "<br><br>";
//}

function setTableRowProperty($fileName,$table,$rowId,$property,$value) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// if row doesn't exist return early
	if ($jsonArray[$table][$rowId] == null) {
		return array('status'=>false,'data'=>'row ' . $rowId . ' does not exist in ' . $table);
	}

	$jsonArray[$table][$rowId][$property] = $value;

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$jsonArray[$table][$rowId][$property]);

}
//Sets row sub property in JSON file
//Example:
//$result = setTableRowSubProperty("test_db","entry_array","777","name","jason");
//if ($result['status']) {
//	echo $result['data'] . "<br><br>";
//}

function setTableRowObjectProperty($fileName,$table,$rowId,$object,$property,$value) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// if row doesn't exist return early
	if ($jsonArray[$table][$rowId] == null) {
		return array('status'=>false,'data'=>'row ' . $rowId . ' does not exist in ' . $table);
	}

	if ($jsonArray[$table][$rowId][$object] === null) {
		$jsonArray[$table][$rowId][$object] = array();
	}

	$jsonArray[$table][$rowId][$object][$property] = $value;

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$jsonArray[$table][$rowId][$object]);

}
//Sets row sub property in JSON file
//Example:
//$result = setTableRowSubProperty("test_page","elements","777","attr","class","btn");
//if ($result['status']) {
//	echo $result['data']['class'] . "<br><br>";
//}


function setSubTableRow($fileName,$table,$elementId,$subTable,$array) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	if ($jsonArray === null) {
	     $jsonArray = array();   
	}

	if ($jsonArray[$table] === null) {
		$jsonArray[$table] = array();
	}

	if ($jsonArray[$table][$elementId] === null) {
		$jsonArray[$table][$elementId] = array();
	}

	if ($jsonArray[$table][$elementId][$subTable] === null) {
		$jsonArray[$table][$elementId][$subTable] = array();
	}

	$uniqID = uniqid();
	$jsonArray[$table][$elementId][$subTable][$uniqID] = $array;


	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$uniqID);

}
//Sets row of a sub table. Creates table and/or subtable if they doesn't not exist
//Example:
//$dataArray = array();
//$dataArray['type'] = "div";
//$dataArray['content'] = "hello world";
//$result = setSubTableRow("test","pages","1234567","tags",$dataArray);
//if ($result['status']) {
//	echo $result['data'] . "<br><br>";
//}

function updateTableRow($fileName,$table,$rowId,$array) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	foreach ($array as $key => $value){
		$jsonArray[$table][$rowId][$key] = $value;
	}

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$jsonArray[$table][$rowId]);

}
//Updates Table Row
//Example:
//$dataArray = array();
//$dataArray['name'] = "Jax";
//$dataArray['description'] = "Meow Meow";
//$result = updateTableRow("test","pages","5ee5bab598bd0",$dataArray);
//if ($result['status']) {
//	echo $result['data']['name'] . "<br><br>";
//}

function updateSubTableRow($fileName,$table,$subTable,$subRowID,$array) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	foreach ($array as $key => $value){
		$jsonArray[$table][$subTable][$subRowID][$key] = $value;
	}

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>$jsonArray[$table][$subTable][$subRowID]);

}
//Updates Subtable Row
//Example:
//$dataArray = array();
//$dataArray['type'] = "h3";
//$dataArray['content'] = "garden";
//$result = updateSubTableRow("test","pages","tags","5ef2e5a9a2502",$dataArray);
//if ($result['status']) {
//	echo $result['data']['type'] . "<br><br>";
//}

function getTableRow($fileName,$table,$rowID) {

	$db = file_get_contents("json/" . $fileName . ".json");
	
	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// if JSON doesn't exist return early
	if ($jsonArray == null) {
		return array('status'=>false,'data'=>'json does not exist');
	} else {
		$tableArray = $jsonArray[$table];
	}

	// if table doesn't exist return early
    if ($tableArray == null) {
    	return array('status'=>false,'data'=>'table does not exist');
    }

    $rowArray = $jsonArray[$table][$rowID];

	// if row doesn't exist return early else return data
 	if ($rowArray == null) {
 		return array('status'=>false,'data'=>'row does not exist');
 	} else {
 		return array('status'=>true,'data'=>$rowArray);
 	}

}
//Gets Table Row
//Example:
//$result = getTableRow("test","pages","5ee0936778f06");
//if ($result['status']) {
//	echo $result['data']['name'] . "<br>";
//	echo $result['data']['description'] . "<br><br>";
//}


function getTableRows($fileName,$table) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// if JSON doesn't exist return early
	if ($jsonArray == null) {
		return array('status'=>false,'data'=>'json does not exist');
	} else {
		$tableArray = $jsonArray[$table];
	}

	// if table doesn't exist return early else return data
	if ($tableArray == null) {
		return array('status'=>false,'data'=>'table does not exist');
	} else {
		return array('status'=>true,'data'=>$tableArray);		
	}

}
//Gets multiple table rows
//Example:
//$result = getTableRows("test","pages");
//if ($result['status']) {
//	foreach ($result['data'] as $row) {
//		echo $row['name'] . "<br>";
//		echo $row['description'] . "<br><br>";
//	}
//}


function deleteProperty($fileName,$key) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	
	// delete property from JSON
	unset($jsonArray[$key]);

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>'');

}
//Deletes Table Row
//Example:
//$dataArray = array();
//$result = deleteProperty("test","title");
//if ($result['status']) {
//	echo "Property Deleted<br><br>";
//}


function deleteTableRow($fileName,$table,$rowID) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	
	// delete row from JSON
	unset($jsonArray[$table][$rowID]);

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>'');

}
// Deletes Table Row
// Example:
//$result = deleteTableRow("test","pages","5ee5ba94f3e35");
//if ($result['status']) {
//	echo "Row Deleted<br><br>";
//}


function deleteTableRowProperty($fileName,$table,$rowId,$property) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	
	// delete table row property from JSON
	unset($jsonArray[$table][$rowId][$property]);

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>'');

}



function deleteTableRowObjectProperty($fileName,$table,$rowId,$object,$property) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);
	
	// delete table row object property from JSON
	unset($jsonArray[$table][$rowId][$object][$property]);

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>'');

}


function deleteSubTableRow($fileName,$table,$rowId,$subTable,$subRowId) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// delete sub table row from JSON	
	unset($jsonArray[$table][$rowId][$subTable][$subRowId]);

	$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true,'data'=>'');

}
//Deletes sub table row
//Example:
//$dataArray = array();
//$result = deleteSubTableRow("test","pages","tags","5ef2e3a36ae10");
//if ($result['status']) {
//	echo "SubTable Row Deleted<br><br>";
//}


function createFile($fileName) {
	// if no file name use a unique ID
	if ($fileName == false) {
		$fileName = uniqid();
	}

	// try to make the file
	try {
		$newFile = fopen("json/" . $fileName . ".json", "w");

		// write empty JSON object to file
		fwrite($newFile, "{/n}");

		return array('status'=>true,'data'=>$fileName);

	} catch(Exception $e) {

		return array('status'=>false,'data'=>$e->getMessage());

	}

}
// Creates JSON file
// Example:
//$result = createFile("pages");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function deleteFile($fileName) {
	// try to delete the file
	try {
		unlink("json/" . $fileName . ".json");
		return array('status'=>true,'data'=>$fileName);
	} catch(Exception $e) {
		return array('status'=>false,'data'=>$e->getMessage());
	}
}
// Deletes JSON file
// Example:
//$result = deleteFile("test");
//if ($result['status']) {
//	echo $result['data'] . "<br>";
//}


function getFileObj($fileName) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	$jsonArray = json_decode($db, true);

	// if JSON doesn't exist return early else return data
	if ($jsonArray == null) {
	    return array('status'=>false,'data'=>'json does not exist');
	} else {
		return array('status'=>true,'data'=>$jsonArray);
	}

}
//Gets File Object
//Example:
//$result = getPageObj("page_5ef437a4d3edc");
//if ($result['status']) {
//	echo $result['data']['name'] . "<br>";
//}

function setFileObj($fileName,$object) {

	$db = file_get_contents("json/" . $fileName . ".json");

	// if db file doesn't exist return early
	if (!$db) {
		return array('status'=>false,'data'=>'file ' . $fileName . ' does not exist');
	}

	// encode JSON object
	$encodedJSON = json_encode($object,JSON_PRETTY_PRINT);

	// write JSON back to file
	$file = fopen("json/" . $fileName . ".json", "w") or die("Unable to open file");
	fwrite($file, $encodedJSON);
	fclose($file);

	return array('status'=>true);

}
//Gets File Object
//Example:
//$object = "{ test: 'test123' }";
//$result = setPageObj("page_5ef437a4d3edc",$object);
//if ($result['status']) {
//	echo "success";
//}


?>
