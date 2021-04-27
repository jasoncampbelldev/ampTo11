<?php
require 'json_db_extended.php';
require 'classes.php';
require 'php_module_functions.php';


if ($_POST["function"]) {

	$functionName = $_POST["function"];

	if ($functionName == "addElement") {

		if ($_POST["type"]) {
			$containerFile = $_POST["containerFile"];
			$parentId = $_POST["parentId"];
			$orderPosition = $_POST["orderPosition"];
			$dataArray = [];
			$dataArray['type'] = $_POST["type"];
			$dataArray['name'] = $_POST["name"];
			$dataArray['editor'] = $_POST["editor"];
			$dataArray['moduleId'] = $_POST["moduleId"];
			$dataArray['moduleType'] = $_POST["moduleType"];
			$dataArray['parentId'] = $_POST["parentId"];
			$childNum = $_POST["childNum"];
			if ($orderPosition == "top") {
				$orderNum = 0;
			} else {
				$orderNum = (int)$childNum;
			}
			$dataArray['orderNum'] = $orderNum;
			$setElement = setTableRow($containerFile,"elements",$dataArray);;
			if ($setElement['status']){
				echo "Element has been added! ";
				if ($orderNum == 0) {
					$reorderElements = reorderTableRows($containerFile,"elements",$parentId,$setElement['data'],$orderNum,"top");
					if ($reorderElements['status']) {
						echo "Elements have been reordered!";
					}
				}
			}
		} else {
			echo "Error: Tag type cannot be blank";
		}

	}

	if ($functionName == "editElement") {
		if ($_POST["type"]) {
			$elementId = $_POST["elementId"];
			$containerFile = $_POST["containerFile"];
			$dataArray = [];
			$dataArray['type'] = $_POST["type"];
			$dataArray['name'] = $_POST["name"];
			$dataArray['editor'] = $_POST["editor"];
			$dataArray['content'] = $_POST["content"];
			$dataArray['moduleId'] = $_POST["moduleId"];
			$dataArray['moduleType'] = $_POST["moduleType"];
			$updateElement = updateTableRow($containerFile,"elements",$elementId,$dataArray);
			if ($updateElement){
				echo "Element has been edited!";
			}
		} else {
			echo "Error: Tag type cannot be blank";
		}

	}

	if ($functionName == "addElementAttr") {

		if ($_POST["name"]) {
			$elementId = $_POST["elementId"];
			$containerFile = $_POST["containerFile"];
			$name = $_POST["name"];
			$value = $_POST["value"];
			$populateElementAttr = setTableRowObjectProperty($containerFile,"elements",$elementId,"attrs",$name,$value);
			if ($populateElementAttr['status']) {
				echo "Property Attr has been added!";
			}
		} else {
			echo "Error: Element Attr name cannot be blank";
		}

	}

	if ($functionName == "updateElementVars") {

		if ($_POST["elementId"]) {
			$elementId = $_POST["elementId"];
			$containerFile = $_POST["containerFile"];
			foreach ($_POST as $name => $value) {
				if ($name != "elementId" && $name != "containerFile" && $name != "function") {
				    $result = setTableRowObjectProperty($containerFile,"elements",$elementId,"vars",$name,$value);
				    if ($result['status']) {
						echo "Variables has been saved!";
					}
				}
			}
		} else {
			echo "Error: Element ID cannot be blank";
		}

	}

	if ($functionName == "editElementAttr") {

		if ($_POST["value"]) {
			$elementId = $_POST["elementId"];
			$containerFile = $_POST["containerFile"];
			$name = $_POST["name"];
			$value = $_POST["value"];
			$populateElementAttr = setTableRowObjectProperty($containerFile,"elements",$elementId,"attrs",$name,$value);
			if ($populateElementAttr['status']) {
				echo "Property Attr has been saved!";
			}
		} else {
			echo "Error: Element Attr value cannot be blank";
		}

	}

}


if ($_GET["function"]) {

	$functionName = $_GET["function"];

	if ($functionName == "moveElementUp" || $functionName == "moveElementDown") {
		$direction = "moveUp";
		if ($functionName == "moveElementDown") {
			$direction = "moveDown";
		}

		$containerFile = $_GET["containerFile"];
		$elementId = $_GET["elementId"];
		$parentId = $_GET["parentId"];
		$orderNum = (int)$_GET["orderNum"];
		$dataArray = [];
		$dataArray['orderNum'] = $orderNum;
		$updateElement = updateTableRow($containerFile,"elements",$elementId,$dataArray);
		echo $updateElement['data'];
		if ($updateElement['status']){
			echo "Element has been edited!";
			$reorderElements = reorderTableRows($containerFile,"elements",$parentId,$elementId,$orderNum,$direction);
			if ($reorderElements['status']) {
				echo "Elements have been reordered!";
			}
		}

	}

	if ($functionName == "dragDropElement") {
		$direction = "dragDrop";
		$containerFile = $_GET["containerFile"];
		$elementId = $_GET["elementId"];
		$newParentId = $_GET["newParentId"];
		$newOrderNum = (int)$_GET["newOrderNum"];
		$result = getTableRow($containerFile,"elements",$elementId);
		if ($result['status']){
			$element = $result['data'];
			$oldParentId = $element["parentId"];
			$oldOrderNum = (int)$element["orderNum"];
			$dataArray = [];
			$dataArray['orderNum'] = $newOrderNum;
			$dataArray['parentId'] = $newParentId;

			// make sure it isn't being dropped in the same spot
			if ($newParentId != $oldParentId || ($newOrderNum != $oldOrderNum && $newOrderNum != ($oldOrderNum + 1)) ) {
				if ($newParentId == $oldParentId && $oldOrderNum == 0) {
					$newOrderNum = $newOrderNum - 1;
				}
				// make sure new order number isn't below 0 and it's not being dropped in itself
				if ($newOrderNum >= 0 && $newParentId != $elementId) {
					$updateElement = updateTableRow($containerFile,"elements",$elementId,$dataArray);
					if ($updateElement['status']){
						echo "Element has been edited!";
						$reorderElements = dragDropElement($containerFile,$elementId,$oldParentId,$oldOrderNum,$newParentId,$newOrderNum);
						if ($reorderElements['status']) {
							echo "Elements have been reordered!";
						}
					}
				}
			}
		}
	}

	if ($functionName == "deleteElement") {

		$containerFile = $_GET["containerFile"];
		$elementId = $_GET["elementId"];
		$parentId = $_GET["parentId"];
		$orderNum = (int)$_GET["orderNum"];
		$deleteElement = deleteTableRow($containerFile,"elements",$elementId);
		if ($deleteElement['status']) {
			echo "Element Deleted!";
			$reorderElements = reorderTableRows($containerFile,"elements",$parentId,$elementId,$orderNum,"delete");
			if ($reorderElements['status']) {
				echo "<br>Elements have been reordered!";
				//Delete child elements
				$allElements;
				$results = getTableRows($containerFile,"elements");
				if ($results['status']) {
					$allElements = $results['data'];
					function deleteChildren($allElements,$parentId,$containerFile)  {
						foreach ($allElements as $elementKey => $element) {
							if ($element['parentId'] == $parentId) {
								$deleteElement = deleteTableRow($containerFile,"elements",$elementKey);
								deleteChildren($allElements,$elementKey,$containerFile);
							}
						}
					}
					deleteChildren($allElements,$elementId,$containerFile);
					echo "<br>Children Deleted!";
				}
			}
		}

	}

	if ($functionName == "deleteElementAttr") {

		$containerFile = $_GET["containerFile"];
		$elementId = $_GET["elementId"];
		$name = $_GET["name"];
		$deleteElementAttr = deleteTableRowObjectProperty($containerFile,"elements",$elementId,"attrs",$name);
		if ($deleteElementAttr['status']) {
			echo "Element Attr Deleted!";
		}

	}



	if ($functionName == "printElements") {
		
		$container;
		if ($_GET["editType"] == "module") {
			$container = new Module($_GET["editId"]);
		} else {
			$container = new Page($_GET["editId"]);
		}


		// Print Elements in JSON Variable
		echo "<script>\n";
		echo "var pageElements = " . json_encode($container->get_elements()) . ";\n";
		echo "</script>\n";


		// Print Elements in HTML
		$elementArray = $container->get_elements();

		function sortByOrder($array) {
			$newArray = [];

			$highestOrderNum = 0;
			foreach ($array as $element) {
				if ($element['orderNum'] > $highestOrderNum) {
					$highestOrderNum = $element['orderNum'];
				}
			}

			$count = 0;
			for ($x = 0; $x <= $highestOrderNum; $x++) {
				foreach ($array as $elementID => $element) {
					if ($element['orderNum'] == $x) {
						$newArray[$count] = $element;
						$newArray[$count]['id'] = $elementID;
						$count++;
					}
				}
			}

		    return $newArray;
		}
		$elementArray = sortByOrder($elementArray);


		function getChildNum($elementArray,$elementId) {
			$count = 0;
			foreach ($elementArray as $elementKey => $element) {
				if ($element['parentId'] == $elementId) {
					$count++;
				}
			}
			return $count;
		}

		$GLOBALS['childNumArray'] = [];

		function printElements($elementArray,$parentId) {


			foreach ($elementArray as $elementKey => $element) {
				$elementId = $element['id'];
				if ($element['parentId'] == $parentId) {
					$childNum = getChildNum($elementArray,$elementId);
					$GLOBALS['childNumArray'][$elementId] = $childNum;
					if ( $element['orderNum'] == 0 && $element['parentId'] != "" ) {
						echo '<div id= "move' . $elementId . $element['orderNum'] . '" class="dropZone" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dropExit(event)" ';
						echo 'data-parent-id="' . $element['parentId'] . '" ';
						echo 'data-order="' . $element['orderNum'] . '" ';
						echo '>';
						echo '</div>';
					}
					echo '<div id="element' . $elementId . '" class="element" ';
					echo 'data-element-id="' . $elementId . '" ';
					if ($element['parentId'] != "") {
						echo ' draggable="true" ondragstart="drag(event)"';
					}
					echo ">";
					echo '<div class="elementInfo">';
					echo '<div class="elementInfoBar">';
					echo '<div class="elementInfoType">';
					if ($element['type'] == "module") {
						echo '<span title="Module">Module</span>';
					} else {
						echo '<span title="' . $element['name'] . '">' . $element['name'] . '</span>';
					}
					echo '</div>';
					echo '<div class="elementInfoIcons">';
					echo '<div class="elementInfoIconsMobileWrapper">';
					echo '<a class="elementInfoIconsMobile" href="javascript:;" data-element-id="' . $elementId . '" data-toggle="closed"><img src="icons/cog.svg" alt="edit" title="edit"/></a>';
					echo '</div>';
					echo '<div class="elementInfoIconsInner">';
					echo '<span class="elementInfoEdit"><a class="editElement" href="javascript:;" data-element-id="' . $elementId . '" data-toggle="closed"><img src="icons/pencil-white.svg" alt="edit" title="edit"/></a></span> ';
					echo '<span class="elementInfoAdd"><a class="addChildElement" href="javascript:;" data-element-id="' . $elementId . '" data-toggle="closed"><img src="icons/plus.svg" alt="add child" title="add child"/></a></span> ';
					if ($element['type'] == "module") {
						echo '<span class="elementInfoEditVars"><a class="editElementVars" href="javascript:;" data-element-id="' . $elementId . '" data-toggle="closed">Vars</a></span> ';
					} else {
						if ($element['name']) {
							echo '<span class="elementInfoEditAttrs"><a class="editElementAttrs" href="javascript:;" data-element-id="' . $elementId . '" data-toggle="closed">Attrs</a></span> ';
						}
					}
					if ($element['parentId'] != "") {
						if ($element['orderNum'] == 0) {
							echo '<span class="elementInfoMoveUp"><img class="opacity50" src="icons/arrow-up.svg" alt="up" title="up"/></span> ';
						} else {
							echo '<span class="elementInfoMoveUp"><a class="moveElementUp" href="javascript:;" data-element-id="' . $elementId . '" data-element-order="' . $element['orderNum'] . '" data-parent-id="' . $element['parentId'] . '"><img src="icons/arrow-up.svg" alt="up" title="up"/></a></span> ';
						}
						if ($element['orderNum'] == ( $GLOBALS['childNumArray'][$element['parentId']] - 1) ) {
							echo '<span class="elementInfoMoveDown"><img class="opacity50" src="icons/arrow-down.svg" alt="down" title="down"/></span> ';
						} else {
							echo '<span class="elementInfoMoveDown"><a class="moveElementDown" href="javascript:;" data-element-id="' . $elementId . '"><img src="icons/arrow-down.svg" alt="down" title="down"/></a></span> ';
						}
						echo '<span class="elementInfoDelete"><a class="deleteElement" href="javascript:;" data-element-id="' . $elementId . '"><img src="icons/cross.svg" alt="delete" title="delete"/></a></span> ';
					}
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<div id="elementContent' . $elementId . '" class="elementContent">';
					if ($element['type'] == "module") {
						$moduleName = New moduleName($element['moduleId']);
						echo '<p class="moduleName">Module: <strong><a href="edit_module.php?id=' . $element['moduleId'] . '" target="_blank">' . $moduleName->get_name() . '</a></strong></p>';
					} else {
						echo strip_tags($element['content']);
					}
					echo '</div>';

					// if element has no children print drop zone div
					if ($childNum == 0) {
						echo '<div id= "move' . $elementId . $element['orderNum'] . '" class="dropZone" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dropExit(event)" ';
						echo 'data-parent-id="' . $elementId . '" ';
						echo 'data-order="0" ';
						echo '>';
						echo '</div>';
					}

					printElements($elementArray,$elementId);

					echo "</div>\n";
					if ($element['parentId'] != "") {
						echo '<div id= "move' . $elementId . $element['type'] . '" class="dropZone" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dropExit(event)" ';
						echo 'data-parent-id="' . $element['parentId'] . '" ';
						echo 'data-order="' . ( $element['orderNum'] + 1 ) . '" ';
						echo '>';
						echo '</div>';
					}				
				}
			}
		}

		printElements($elementArray,'');


				// Print Elements in JSON Child Num array Variable
		echo "<script>\n";
		echo "var pageElementChildNums = " . json_encode($GLOBALS['childNumArray']) . ";\n";
		echo "</script>\n";

	}


}

?>