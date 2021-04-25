
<?php
require 'json_db_extended.php';
require 'classes.php';


if($_GET["id"]) {
	$database = new Database($_GET["id"]);
}
?>
<html>
<head>
	<title>Database Edit - <?php echo $database->get_name() ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
		var databaseInfo = {
			"id" : "<?php echo $database->get_id() ?>",
			"file" : "<?php echo $database->get_file() ?>"
		};
	</script>

	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

	<link href="css/style.css" rel="stylesheet">

</head>	

<body>

	<div id="dialog"></div>

	<div class="topNav">
		<a id="mobileMenu" href="javascript:;"><img src="icons/menu.svg" alt="menu"/></a>
		<div class="topNavInner">
			<nav>
				<a class="navLink navHomeLink" href="index.php">Home</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="info">Info</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="fields">Fields</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="entry">Add Entry</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="sortBy">Sort By</a>
				<div class="floatRight desktopOnly">
					<a class="navLink navImagesLink" href="images.php" target="_blank">
						<img class="navImagesIcon" src="icons/images.svg" alt="image admin" title="image admin" />
					</a>
				</div>
			</nav>
		</div>
	</div>

	<div class="wrapper" id="edit-database-wrapper">

		<h1>
			<?php echo $database->get_name() ?> 
			<a class="nameChangeToggle" href="javascript:;">
				<img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Name" title="Edit Name" />
			</a>
		</h1>
		<form id="databaseForm" class="basicForm nameChange" method="POST" autocomplete="off">
			<div class="formElement">
				<label for="pageURL">New Name</label>
				<input type="text" name="newName" required>
			</div>
			<input type="hidden" name="fileName" value="<?php echo $database->get_file() ?>">
			<input type="hidden" name="databaseId" value="<?php echo $database->get_id() ?>">
			<input type="hidden" name="function" value="updateDatabaseName">
			<input type="submit" value="Update">
			<p class="error"></p>
		</form>

		<section id="info" class="toggleSections">

			<?php

				echo "<h2>Info:</h2>";

				echo '<ul class="itemList">';

				echo '<li><strong>ID:</strong> ' . $database->get_id() . "</li>\n";
				echo '<li><strong>File:</strong> <a href="json/' . $database->get_file() . '.json" target="_blank">' . $database->get_file() . "</a></li>\n";
				echo '</ul>';

			?>

		</section>

		<section id="fields" class="toggleSections">

			<h2>Fields</h2>

			<form id="newFieldForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="fieldName">Field Name</label>
					<input type="text" id="fieldName" name="fieldName" class="noSpacesAllowed" placeholder="Name" required/>
				</div>
				<div class="formElement">
					<label for="fieldEditor">Field Editor</label>
					<select id="fieldEditor" name="fieldEditor">
						<option value="text">text</option>
						<option value="text_area">text area</option>
						<option value="rich_text">rich text</option>
						<option value="image">image</option>
						<option value="date">date</option>
					</select>
				</div>
				<input type="hidden" name="function" value="addDatabaseField">
				<input type="hidden" name="databaseFile" value="<?php echo $database->get_file(); ?>">
				<input type="submit" value="Add"/>
				<p class="error"></p>
			</form>

			<div id="fieldListWrapper"></div>

		</section>

		<section id="entry" class="toggleSections">

			<h2>Add Entry</h2>

			<form id="newEntryForm" method="POST" autocomplete="off">
				<?php
					$fields = $database->get_fields();
					if ($fields) {
						foreach ($fields as $fieldKey => $field) {
							if ($field['editor'] == "text") {
								echo '<div class="formElement">';
								echo '<label for="field' . $fieldKey . '">' . $field['name'] . '</label>';
								echo '<input type="text" id="field' . $fieldKey . '" name="' . $field['name'] . '" placeholder="' . $field['name'] . '" ';
								if ($field['name'] == 'url') {
									echo 'class="noSpacesAllowed" required ';
								}
								echo '/>';
								echo "</div><br>\n";
							} elseif  ($field['editor'] == "date") {
								echo '<div class="formElement">';
								echo '<label for="field' . $fieldKey . '">' . $field['name'] . '</label>';
								echo '<input class="datePicker" type="text" id="field' . $fieldKey . '" name="' . $field['name'] . '" placeholder="' . $field['name'] . '"/>';
								echo "</div><br>\n";
							} elseif  ($field['editor'] == "text_area") {
								echo '<div class="formElement">';
								echo '<label for="field' . $fieldKey . '">' . $field['name'] . '</label>';
								echo '<textarea id="field' . $field['name'] . '" name="' . $field['name'] . '"/></textarea><br>';
								echo "</div><br>\n";
							} elseif  ($field['editor'] == "rich_text") {
								echo '<div class="formElement">';
								echo '<label for="field' . $fieldKey . '-content">' . $field['name'] . '</label>';
								echo '<input type="hidden" id="field' . $fieldKey . '-content" name="' . $field['name'] . '"/>';
								echo '<div class="richTextEditor" id="field' . $fieldKey . '"></div>'; 
								echo "</div><br>\n";
							} elseif  ($field['editor'] == "image") {
								echo '<div class="formElement">';
								echo '<label for="field' . $fieldKey . '">' . $field['name'] . '</label>';
								echo '<input type="text" id="field' . $fieldKey . '" name="' . $field['name'] . '"/> ';
								echo '<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="field' . $fieldKey . '"><img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images"/></a>';
								echo "</div><br>\n";
							}			
						}
					}

				?>
				<input type="hidden" name="function" value="addDatabaseEntry">
				<input type="hidden" name="databaseFile" value="<?php echo $database->get_file(); ?>">
				<input type="submit" value="Add"/>
				<p class="error"></p>
			</form>

		</section>

		<section id="sortBy" class="toggleSections">

			<h2>SortBy</h2>

			<form id="newFieldForm" class="basicForm" method="POST">
				<div class="formElement">
					<label for="sortBy">Field Editor</label>
					<select id="sortBy" name="sortBy">
						<option value="">- Select Field -</option>
						<?php
							$sortBy = $database->get_sortBy();
							$fields = $database->get_fields();
							if ($fields) {
								foreach ($fields as $fieldKey => $field) {
									echo '<option value="' . $fieldKey. '"';
									if ($sortBy == $fieldKey) {
										echo ' selected="selected" ';
									}
									echo '>' . $field['name'] . '</option>';
								}
							}
						?>
					</select>
				</div>
				<input type="hidden" name="function" value="updateDatabaseSortBy">
				<input type="hidden" name="databaseFile" value="<?php echo $database->get_file(); ?>">
				<input type="submit" value="Update"/>
			</form>

		</section>

		<div id="entryEditWrapper" style="display:none;"></div>

		<div id="databaseWrapper"></div>

	</div>

	<script>
		var mainNavToggleClose = true;
	</script>
	<script src="js/general.js"></script>
	<script src="js/edit_database.js"></script>

</body>
</html>


