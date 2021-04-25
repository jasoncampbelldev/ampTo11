<?php
require 'json_db_extended.php';
require 'classes.php';


if ($_POST["function"]) {

	$functionName = $_POST["function"];


	if ($functionName == "updateDatabaseName") {

		if($_POST["newName"]) {
			$fileName = $_POST["fileName"];
			$databaseId = $_POST["databaseId"];
			$newName = $_POST["newName"];
			$result = setProperty($fileName,"name",$newName);
			if ($result['status']) {
				echo "Database name updated!";
				$result = setTableRowProperty("databases","database_array",$databaseId,"name",$newName);
				if ($result['status']) {
					echo "Database array name updated!";
				} else {
					echo "Error: Database array name was not updated";
				}
			} else {
				echo "Error: File name was not updated";
			}
		} else {
			echo "Error: Name cannot be blank ";
		}

	}

	if ($functionName == "updateDatabaseSortBy") {

		if($_POST["sortBy"]) {
			$databaseFile = $_POST["databaseFile"];
			$databaseId = $_POST["databaseId"];
			$sortBy = $_POST["sortBy"];
			$result = setProperty($databaseFile,"sortBy",$sortBy);
			if ($result['status']) {
				echo "Database Sort By updated!";
			} else {
				echo "Error: Sort By was not updated";
			}
		} else {
			echo "Error: Sort by cannot be blank ";
		}

	}

	if ($functionName == "addDatabaseField") {

		if ($_POST["fieldName"]) {
			$databaseFile = $_POST["databaseFile"];
			$fieldName = $_POST["fieldName"];
			$dataArray = [];
			$dataArray['name'] = $fieldName;
			$dataArray['editor'] = $_POST["fieldEditor"];
			$nameCheck = true;
			$result = getTableRows($databaseFile,"field_array");
			if ($result['status']) {
				$fields = $result['data'];
				foreach ($fields as $fieldKey => $field) {
					if ($field['name'] == $fieldName) {
						$nameCheck = false;
					}
				}
			}
			if ($nameCheck) {
				$result = setTableRow($databaseFile,"field_array",$dataArray);
				if ($result['status']) {
					$result = getTableRows($databaseFile,"entry_array");
					if ($result['status']) {
						$entries = $result['data'];
						if (count($entries) > 0) {
							foreach ($entries as $entryKey => $entry) {
								$dataArray = [];
								$dataArray[$fieldName] = "";
								$result = setTableRowProperty($databaseFile,"entry_array",$entryKey,$fieldName,"");
								if ($result['status']) {
									echo "Field has been added!";
								}
							}
						}
					}
				}
			} else {
				echo "Error: Field name already in use.";
			}
		} else {
			echo "Error: Field name cannot be blank.";
		}

	}

	if ($functionName == "addDatabaseEntry") {

		if ($_POST["databaseFile"]) {
			$databaseFile = $_POST["databaseFile"];
			$urlField = "";
			$dataArray = [];
			foreach ($_POST as $name => $value) {
				if ($name != "databaseFile" && $name != "function") {
					if ( $name == "url" ) {
						$urlField = $value;
					}
					$dataArray[$name] = $value;
				}
			}
			$urlCheck = true;
			if ($urlField !== ""){
				$result = checkUniqueField($databaseFile,"url",$urlField);
				if ($result['status'] == false) { 
					$urlCheck = false;
				}
			}
			
			if ($urlCheck) {
				$result = setTableRow($databaseFile,"entry_array",$dataArray);
				if ($result['status']) {
					echo "Entry has been saved!";
				} else {
					echo "Error: Entry was not saved. Try again.";
				}
			} else {
				echo "Error: URL already exists in another entry.";
			}
		} else {
			echo "Error: Database File name cannot be blank";
		}

	}

	if ($functionName == "editDatabaseEntry") {

		if ($_POST["databaseFile"]) {
			$databaseFile = $_POST["databaseFile"];
			$entryId = $_POST["entryId"];
			$dataArray = [];
			$urlField = "";
			foreach ($_POST as $name => $value) {
				if ($name != "databaseFile" && $name != "entryId" && $name != "function") {
					if ( $name == "url" ) {
						$urlField = $value;
					} 
					$dataArray[$name] = $value;
				}
			}
			$urlCheck = true;
			if ($urlField !== ""){
				$result = submitUniqueField($databaseFile,$entryId,"url",$urlField);
				if ($result['status'] == false) {
					$urlCheck = false;
					echo "Error: " . $result['data'];
				}
			}
			if ($urlCheck) {
				$result = updateTableRow($databaseFile,"entry_array",$entryId,$dataArray);
				if ($result['status']) {
					echo "Entry has been updated!";
				}
			}
		} else {
			echo "Error: Database File name cannot be blank";
		}

	}

}

if ($_GET["function"]) {

	$functionName = $_GET["function"];


	if ($functionName == "deleteDatabaseField") {

		if ($_GET["fieldId"]) {
			$fieldId = $_GET["fieldId"];
			$fieldName = $_GET["fieldName"];
			$databaseFile = $_GET["databaseFile"];
			$result = deleteTableRow($databaseFile,"field_array",$fieldId);
			if ($result['status']) {
				$result = getTableRows($databaseFile,"entry_array");
				if ($result['status']) {
					$entries = $result['data'];
					if (count($entries) > 0) {
						foreach ($entries as $entryKey => $entry) {
							$result = deleteTableRowProperty($databaseFile,"entry_array",$entryKey,$fieldName);
							echo $entryKey;
						}
					}
				}
				echo "Field has been deleted";
			}
		} 

	}

	if ($functionName == "deleteDatabaseEntry") {

		if ($_GET["fieldId"]) {
			$fieldId = $_GET["fieldId"];
			$databaseFile = $_GET["databaseFile"];
			$result = deleteTableRow($databaseFile,"entry_array",$fieldId);
			if ($result['status']) {
				$result2 = deleteUrl($fieldId);
				echo "Entry has been deleted";
			}
		} 

	}

	if ($functionName == "printDatabaseFields") {

		if ($_GET["databaseId"]) {
			$database = new Database($_GET["databaseId"]);
			$fields = $database->get_fields();

			if ($fields) {
				echo '<ul class="itemList">';
				foreach ($fields as $key => $row) {
					echo "<li>";
				    echo '<strong>' . $row['name'] . '</strong>';
				    echo ' (Editor: ' . $row['editor'] . ')';
				    echo ' <a class="button deleteField" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$row['name']) . '">Delete</a>';
				    echo "</li>\n";
				}
				echo "</ul>\n";
			}
		} else {
			echo "Error: Database id cannot be blank";
		}

		// Print Fields in JSON Variable
		echo "<script>\n";
		echo "var databaseFields = " . json_encode($database->get_fields()) . ";\n";
		echo "</script>\n";

	}

	if ($functionName == "printDatabaseEntries") {

		if ($_GET["databaseId"]) {
			$database = new Database($_GET["databaseId"]);

			$fields = $database->get_fields();

			echo '<div class="entriesWrapper">';


			$entries = $database->get_entriesSorted();
			if ($entries) {

				$i = 1;
				echo '<div class="entriesNav">';
				echo '<div class="entriesNavInner">';
				foreach ($entries as $key => $row) {
					echo '<a href="javascript:;" data-entry="' . $i . '"';
					if ($i == 1) {
						echo ' class="button active"';
					} else {
						echo ' class="button"';
					}
					echo '>' . $i . '</a>';
					$i++;
				}
				echo '</div>';
				echo '</div>';

				echo '<style>.entriesNavInner { width: ' . $i * 50 . 'px; } </style>';

				$i = 1;
				foreach ($entries as $key => $row) {
					echo '<div id="entry' . $i . '" ';
					if ($i == 1) {
						echo 'class="active entryWrapper">'; 
					} else {
						echo 'class="entryWrapper">';
					}

					echo '<div class="entryID">Entry ID: ' . $key . '</div>';

					foreach ($fields as $field) {

						$fieldName = str_replace(" ", "_", $field['name']);
						echo '<div class="entryFieldName"><strong>';
						echo $fieldName;
					    echo "</strong></div>\n";

						echo '<div class="entryFieldContent">';
					    echo htmlentities(addslashes($row[$fieldName]));
					    echo "</div>\n";

					}

					echo '<div class="entryButtons">';
					echo ' <a class="button editEntry" href="javascript:;" data-id="' . $key . '">Edit</a>';
					echo '<a class="button deleteEntry" href="javascript:;" data-id="' . $key . '">Delete</a>';
					echo "</div>\n";

				    echo "</div>\n";

				    $i++;
				}
			} else {
				echo '<a class="button" href="javascript:;" onclick="openSection(this)" data-section="fields">Add Fields</a> ';
				echo '<a class="button" href="javascript:;" onclick="openSection(this)" data-section="entry">Add Entry</a>';
			}

			echo "</div>\n";

			// Print Fields in JSON Variable
			echo "<script>\n";
			echo "var databaseEntries = " . json_encode($database->get_entries()) . ";\n";
			echo "</script>\n";

		} else {
			echo "Error: Database id cannot be blank";
		}

	}


}

