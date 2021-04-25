
<?php
require 'json_db_extended.php';
require 'classes.php';

if ($_GET["id"]) {
	$module = new Module($_GET["id"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Module Edit - <?php echo $module->get_name() ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
		var moduleInfo = {
			"id" : "<?php echo $module->get_id() ?>",
			"file" : "<?php echo $module->get_file() ?>",
			"elementNum" : "<?php echo $module->get_elementNum() ?>",
		};
	</script>

	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

	<link href="css/style.css" rel="stylesheet">

</head>	

<body>

	<div id="dialog"></div>
	<div id="elementEditDialog"></div>

	<div class="topNav">
		<a id="mobileMenu" href="javascript:;"><img src="icons/menu.svg" alt="menu"/></a>
		<div class="topNavInner">
			<nav>
				<a class="navLink navHomeLink" href="index.php">Home</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="info">Info</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="variables">Variables</a>
				<div class="floatRight desktopOnly">
					<a class="navLink navImagesLink" href="images.php" target="_blank">
						<img class="navImagesIcon" src="icons/images.svg" alt="image admin" title="image admin" />
					</a>
				</div>
			</nav>
		</div>
	</div>

	<div class="wrapper" id="edit-module-wrapper">

		<h1>
			<?php echo $module->get_name() ?> 
			<a class="nameChangeToggle" href="javascript:;">
				<img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Name" title="Edit Name" />
			</a>
		</h1>
		<form id="databaseForm" class="basicForm nameChange" method="POST" autocomplete="off">
			<div class="formElement">
				<label for="pageURL">New Name</label>
				<input type="text" name="newName" required>
			</div>
			<input type="hidden" name="fileName" value="<?php echo $module->get_file() ?>">
			<input type="hidden" name="moduleId" value="<?php echo $module->get_id() ?>">
			<input type="hidden" name="function" value="updateModuleName">
			<input type="submit" value="Update">
			<p class="error"></p>
		</form>

		<section id="info" class="toggleSections">

			<?php

				echo "<h2>Info:</h2>";

				echo '<ul class="itemList">';
				echo "<li><strong>ID:</strong> " . $module->get_id() . "</li>\n";
				echo '<li><strong>File:</strong> <a href="json/' . $module->get_file() . '.json" target="_blank">' . $module->get_file() . "</a></li>\n";
				$typeName = $module->get_type();
				if ($typeName == "html") {
					$typeName = "HTML Template";
				} elseif ($typeName == "php") {
					$typeName = "PHP Function";					
				}
				echo "<li><strong>Type:</strong> " . $typeName;
				echo '<a class="typeChangeToggle" href="javascript:;"><img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Type" title="Edit Type" /></a>';
				echo "</li>\n";
				echo "</ul>";

			?>

			<form id="databaseForm" class="basicForm typeChange hidden" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="moduleType">Type</label>
					<select id="moduleType" name="type">
						<option value="html">HTML Template</option>
						<option value="php">PHP Function</option>
					</select>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $module->get_file() ?>">
				<input type="hidden" name="moduleId" value="<?php echo $module->get_id() ?>">
				<input type="hidden" name="function" value="updateModuleType">
				<input type="submit" value="Update">
				<p class="error"></p>
			</form>

			<?php
				echo '<ul class="itemList">';
				echo "<li><strong>Description:</strong> " . $module->get_description();
				echo '<a class="descriptionChangeToggle" href="javascript:;"><img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Type" title="Edit Type" /></a>';
				echo "</li>\n";				
				echo '</ul>';
			?>

			<form id="databaseForm" class="basicForm descriptionChange hidden" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="moduleDescription">Description</label>
					<textarea id="moduleDescription" name="description"><?php echo $module->get_description(); ?></textarea>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $module->get_file() ?>">
				<input type="hidden" name="moduleId" value="<?php echo $module->get_id() ?>">
				<input type="hidden" name="function" value="updateModuleDescription">
				<input type="submit" value="Update">
				<p class="error"></p>
			</form>

			<ul class="itemList">
				<li><strong>Child Content Token:</strong> [%content%]</li>
			</ul>

		</section>

		<section id="variables" class="toggleSections">

			<h2>Variables</h2>

			<form id="functionVariableForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="variableName">Name</label>
					<input type="text" id="variableName" name="variableName" class="noSpacesAllowed" required/>
				</div>
				<div class="formElement">
					<label for="variableEditor">Editor</label>
					<select id="variableEditor" name="variableEditor">
						<option value="text">Text</option>
						<option value="image">Image</option>
					</select>
				</div>
				<input type="hidden" name="function" value="addModuleVariable">
				<input type="hidden" name="moduleId" value="<?php echo $module->get_id(); ?>">
				<input type="submit" value="Add"/>
			</form>

			<div id="variableList">
			<?php
				$variableArray = $module->get_variableArray();

				if ($variableArray) {
					echo '<hr>';
					echo '<ul class="itemList">';
					foreach ($variableArray as $key => $row) {
						echo "<li>";
					    echo "<strong>" . $row['name'] . "</strong>";
					    echo ' (Editor: ' . $row['editor'] . ')';
					    echo ' <a class="button deleteVariable" href="javascript:;" data-id="' . $key . '" data-name="' . str_replace(["'",'"'],"",$row['name']) . '">Delete</a> <br>';
					    echo 'Token: {{' . $row['name'] . '}}';
					    echo "</li>\n";
					}
					echo "</ul>\n";
				}	
			?>			
			</div>


		</section>

		<?php if ($module->get_type() == "html") { ?>

			<div class="twoColumnWrapper">

				<div class="twoColumn htmlColumn">

					<h2>HTML</h2>
					<div id="elementWrapper">
						<p>Loading...</p>
					</div>

				</div>

				<div class="twoColumn previewColumn">

					<h2>Preview</h2>
			 		<iframe id="elementPreviewFrame" title="element preview"></iframe> 

			 	</div>

			</div>

		<?php } else { ?>

			<h2>PHP Function</h2>

			<form id="functionNameForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="functionName">Function Name</label>
					<input type="text" id="functionName" name="functionName" value="<?php echo $module->get_functionName(); ?>"/>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $module->get_file() ?>">
				<input type="hidden" name="function" value="updateModuleFunctionName">
				<input type="submit" value="Update"/>
				<p class="error"></p>
			</form>

		<?php } ?>


	</div>
	<script>
		var mainNavToggleClose = true;
	</script>
	<script src="js/general.js"></script>
	<script src="js/edit_page_module.js"></script>
	<script src="js/edit_elements.js"></script>

</body>
</html>


