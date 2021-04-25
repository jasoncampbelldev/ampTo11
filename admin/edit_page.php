
<?php
require 'json_db_extended.php';
require 'classes.php';

if ($_GET["id"]) {
	$page = new Page($_GET["id"]);
	$taxonomy = new Taxonomy($page->get_id());
	$pageUrl = $page->get_id();
	if ($page->get_url()) {
		$pageUrl = $page->get_url();
	}
	$database;
	if ($page->get_type() == "database_template" && $page->get_database()) {
		$database = new Database($page->get_database());
		$entries = $database->get_entries();
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Page Edit - <?php echo $page->get_name() ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
		var pageInfo = {
			"id" : "<?php echo $page->get_id() ?>",
			"file" : "<?php echo $page->get_file() ?>",
			"elementNum" : "<?php echo $page->get_elementNum() ?>",
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
		<div class="mobileNavIcons">
			<a class="mobileNavIcon toggleSectionsLink" href="javascript:;" data-section="publish"><img class="navCogIcon" src="icons/cog.svg" alt="publish menu" title="publish menu" /></a>
			<a class="mobileNavIcon" href="images.php" target="_blank"><img class="navImagesIcon" src="icons/images.svg" alt="image admin" title="image admin" /></a>
		</div>
		<div class="topNavInner">
			<nav>
				<a class="navLink navHomeLink" href="index.php">Home</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="info">Info</a>
				<?php if ($page->get_type() == "database_template") { ?>
					<a class="navLink toggleSectionsLink" href="javascript:;" data-section="database">Database</a>
				<?php } ?>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="seo">SEO</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="metaTags">Meta Tags</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="includes">Includes</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="inlineCode">Inline Code</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="taxonomy">Taxonamy</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="custom">Custom</a>
				<div class="floatRight desktopOnly">
					<a class="navLink cog toggleSectionsLink" href="javascript:;" data-section="publish">
						<img class="navCogIcon" src="icons/cog.svg" alt="publish menu" title="publish menu" />
					</a>
					<a class="navLink navImagesLink" href="images.php" target="_blank">
						<img class="navImagesIcon" src="icons/images.svg" alt="image admin" title="image admin" />
					</a>
				</div>
			</nav>
		</div>
	</div>

	<div class="wrapper" id="edit-page-wrapper">

		<h1>
			<?php echo $page->get_name() ?> 
			<a class="nameChangeToggle" href="javascript:;">
				<img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Name" title="Edit Name" />
			</a>
		</h1>
		<form id="databaseForm" class="basicForm nameChange" method="POST" autocomplete="off">
			<div class="formElement">
				<label for="pageURL">New Name</label>
				<input type="text" name="newName" required>
			</div>
			<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
			<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
			<input type="hidden" name="function" value="updatePageName">
			<input type="submit" value="Update">
			<p class="error"></p>
		</form>

		<section id="publish" class="toggleSections">
			<h2>Publish</h2>
			<div>
				<?php
					echo '<a class="button publishPage" href="javascript:;" data-id="' . $page->get_id() . '" data-url="';
					if ($page->get_url() != "") {
						echo $page->get_url();
					} else {
						echo $page->get_id();
					}
					if ($page->get_type() == "database_template") {
						echo '" data-entry-urls="';
						$entriesCount = count($entries);
						$i = 1;
						foreach ($entries as $entry_key => $entry) {
							$url = $entry_key;
							if ($entry['url']) {
								$url = $entry['url'];
							}
							echo $url;
							if ($i < $entriesCount) {
								echo ",";
							}
							$i++;
						}
					}
					echo '" >Publish Page</a>';
				?>
				<p class="status"></p>
			</div>
			<div>
				<a class="button revertPageHtml" href="javascript:;" data-id="<?php echo $page->get_id() ?>">Revert HTML Elements</a>
				<p class="status"></p>
			</div>
		</section>

		<section id="info" class="toggleSections">

			<?php

				echo "<h2>Info:</h2>";

				echo '<ul class="itemList">';
				echo "<li><strong>ID:</strong> " . $page->get_id() . "</li>\n";
				echo '<li><strong>File:</strong> <a href="json/' . $page->get_file() . '.json" target="_blank">' . $page->get_file() . "</a></li>\n";
				echo "<li><strong>URL:</strong> ";
				if ($page->get_url() != "") {
					if ($page->get_type() != "database_template") {
						echo '<a href="../' . $page->get_url() . '.html" target="_blank">' . $page->get_url() . '</a> ';
					} else {
						echo $page->get_url() . ' ';
					}
					echo '<a class="button toggleSectionsLink" href="javascript:;" data-section="seo">Edit</a>';
				} else {
					echo '<a class="button toggleSectionsLink" href="javascript:;" data-section="seo">Add URL</a><br>';
					if ($page->get_type() != "database_template") {
						echo '<a href="../' . $page->get_id() . '.html" target="_blank">View Live Page</a>';
					}
				}
				if ($page->get_type() == "database_template") {
					echo '<ul class="subList">';
					foreach ($entries as $entry_key => $entry) {
						$url = $pageUrl . "-" . $entry_key;
						if ($entry['url']) {
							$url = $pageUrl . "-" . $entry['url'];
						}
						echo '<li><a href="../' . $url . '.html" target="_blank">' . $url . ".html</a></li>\n";
					}
					echo "</ul>\n";
				}
				echo "</li>\n";
				echo "<li><strong>Type:</strong> " . $page->get_type();
				echo '<a class="typeChangeToggle" href="javascript:;"><img class="pencilEditIcon" src="icons/pencil.svg" alt="Edit Type" title="Edit Type" /></a>';
				echo "</li>\n";
				if ($page->get_type() == "database_template") {
					echo "<li><strong>Database:</strong> " . $page->get_database();
					echo ' <a class="button toggleSectionsLink" href="javascript:;" data-section="database">Edit</a>';
					echo "</li>\n";
				}
				echo "</ul>";

			?>

			<form id="databaseForm" class="basicForm typeChange" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="pageType">Type</label>
					<select id="pageType" name="type">
						<option value="page">Page</option>
						<option value="database_template">Database Template</option>
					</select>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
				<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
				<input type="hidden" name="function" value="updatePageType">
				<input type="submit" value="Update">
				<p class="error"></p>
			</form>

			<?php
				echo '<ul class="itemList">';
				echo '<li><strong>AMP Disabled:</strong> <input type="checkbox" id="ampDisableCheckbox" ';
				if ($page->get_ampDisabled() == 'true') {
					echo 'checked="checked" ';
				}
				echo "/>";
				echo "</li>";
				echo "</ul>";

			?>

		</section>

<?php if ($page->get_type() == "database_template") { ?>

		<section id="database" class="toggleSections">

			<h2>Database</h2>
			<form id="databaseForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="databaseId">Database</label>
					<select name="databaseId" id="databaseId">
						<option>- Select One -</option>
					<?php
					$databaseList = new DatabaseList();
					$databaseArray = $databaseList->get_list();
					foreach ($databaseArray as $databaseItemKey => $databaseItem) {
						echo '<option value="' . $databaseItemKey . '"';
							if ($databaseItemKey == $page->get_database()) {
								echo ' selected="selected" ';
							}
						echo '>' . $databaseItem['name'] . '</option>';
					}
					?>
					</select>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
				<input type="hidden" name="function" value="updatePageDatabase">
				<input type="submit" value="Update">
				<p class="error"></p>
			</form>	

			<?php

			if ($page->get_database()) {

				$fields = $database->get_fields();
				if ($fields) {
					echo "<h3>Available Fields</h3>";
					echo "<ul>";
					foreach ($fields as $fieldKey => $field) {
						echo "<li>{{" . $field['name'] . "}}</li>";
					}
					echo "</ul>";
				}

			}

			?>

		</section>

<?php } ?>

		<section id="seo" class="toggleSections">

			<div class="gridDesktop">

				<div>

					<h2>SEO</h2>

					<form id="pageUrlForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="pageURL">URL</label>
							<input id="pageURL" type="text" name="url" placeholder="url" value="<?php echo $page->get_url() ?>" class="noSpacesAllowed" required>
						</div>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
						<input type="hidden" name="function" value="updatePageUrl">
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>

					<form id="pageTitleForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="pageSeoTitle">Title</label>
							<input id="pageSeoTitle" type="text" name="seoTitle" placeholder="Title" value="<?php echo $page->get_seoTitle() ?>" required>
						</div>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="function" value="updatePageSeoTitle">
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>

					<form id="pageDescriptionForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="pageSeoDescription">Description</label>
							<textarea id="pageSeoDescription" name="seoDescription" placeholder="Description" required><?php echo $page->get_seoDescription() ?></textarea>
						</div>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="function" value="updatePageSeoDescription">
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>

					<form id="pagePubDateForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="pagePubDate">Publish Date</label>
							<input id="pagePubDate" class="datePicker" type="text" name="pubDate" placeholder="Publish Date" value="<?php echo $page->get_pubDate() ?>" required>
						</div>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="function" value="updatePagePubDate">
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>

				</div>

				<div>

					<h2>JSON-LD</h2>
					<form id="jsonLdForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="jsonLdType">Type</label>
							<input id="jsonLdType" type="text" name="type" placeholder="Type" value="<?php echo $page->get_jsonLd()['type'] ?>" required>
						</div><br>
						<div class="formElement">
							<label for="jsonLdHeadline">Headline</label>
							<input id="jsonLdHeadline" type="text" name="headline" placeholder="Headline" value="<?php echo $page->get_jsonLd()['headline'] ?>" />
						</div><br>
						<div class="formElement">
							<label for="jsonLdAuthor">Author</label>
							<input id="jsonLdAuthor" type="text" name="author" placeholder="Author" value="<?php echo $page->get_jsonLd()['author'] ?>" required />
						</div><br>
						<div class="formElement">
							<label for="jsonLdPublisher">Publisher</label>
							<input id="jsonLdAuthor" type="text" name="publisher" placeholder="Publisher" value="<?php echo $page->get_jsonLd()['publisher'] ?>" />
						</div><br>
						<div class="formElement">
							<label for="jsonLdPublisherLogo">Publisher Logo</label>
							<input id="jsonLdPublisherLogo" type="text" name="publisherLogo" placeholder="Publisher Logo" value="<?php echo $page->get_jsonLd()['publisherLogo'] ?>" />
							<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="jsonLdPublisherLogo">
								<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images">
							</a>
						</div><br>
						<div class="formElement">
							<label for="jsonLdImage1x1">Image 1x1</label>
							<input id="jsonLdImage1x1" type="text" name="image1x1" placeholder="Image 1x1" value="<?php echo $page->get_jsonLd()['image1x1'] ?>" />
							<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="jsonLdImage1x1">
								<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images">
							</a>
						</div><br>
						<div class="formElement">
							<label for="jsonLdImage4x3">Image 4x3</label>
							<input id="jsonLdImage4x3" type="text" name="image4x3" placeholder="Image 4x3" value="<?php echo $page->get_jsonLd()['image4x3'] ?>" />
							<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="jsonLdImage4x3">
								<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images">
							</a>
						</div><br>
						<div class="formElement">
							<label for="jsonLdImage16x9">Image 16x9</label>
							<input id="jsonLdImage16x9" type="text" name="image16x9" placeholder="Image 16x9" value="<?php echo $page->get_jsonLd()['image16x9'] ?>" />
							<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="jsonLdImage16x9">
								<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images">
							</a>
						</div><br>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="function" value="updatePageJsonLd">
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>

				</div>

		</section>


		<section id="metaTags" class="toggleSections">

			<h2>Meta Tags</h2>

			<form class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="metaTagName">Name</label>
					<input type="text" name="name" placeholder="name" required>
				</div>
				<div class="formElement">
					<label for="metaTagType">Type</label>
					<select id="metaTagType" name="type">
						<option value="name" selected>name</option>
						<option value="property">property</option>
					</select>
				</div>
				<div class="formElement">
					<label for="metaTagEditor">Editor</label>
					<select id="metaTagEditor" name="editor">
						<option value="text">text</option>
						<option value="image">image</option>
					</select>
				</div>
				<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
				<input type="hidden" name="function" value="addMetaTag">
				<input type="submit" value="Add">
				<p class="error"></p>
			</form>

			<hr>

			<?php
				$metaTags = $page->get_metaTags();
				if ($metaTags) {
					echo '<form class="basicForm" method="POST" autocomplete="off">';
					foreach ($metaTags as $metaTagKey => $metaTag) {
						echo '<p class="mb0"><strong>' . $metaTag['name'] . '</strong> (' . $metaTag['type'] . ')</p>';
						echo '<div class="formElement"><label for="' . $metaTagKey . '">Value</label>';
						echo '<input type="text" id="' . $metaTagKey . '" class="longInput" name="' . $metaTagKey . '" value="' . $metaTag['value'] . '" required> ';
						echo '</div>';
						if ($metaTag['editor'] == "image") {
							echo '<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="' . $metaTagKey . '"><img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images"></a> ';
						}
						echo '<a class="button deleteMetaTag" href="javascript:;" data-meta-tag-id="' . $metaTagKey . '">Delete</a><br>';
					}
					echo '<input type="hidden" name="fileName" value="' . $page->get_file() . '">';
					echo '<input type="hidden" name="function" value="updateMetaTags">';
					echo '<input class="blockSubmit" type="submit" value="Update All">';
					echo '</form>';
				}

			?>

		</section>

		<section id="includes" class="toggleSections">

			<h2>Includes</h2>

			<form class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="includesType">Type</label>
					<select id="includesType" name="type">
						<option value="ampJS">Amp JS</option>
						<option value="js">JS (for non-Amp pages)</option>
						<option value="jsFooter">JS Footer (for non-Amp pages)</option>
					</select>
				</div>
				<div class="formElement">
					<label for="includesURL">URL</label>
					<input id="includesURL" type="text" name="url" required>
				</div>
				<div class="formElement">
					<label for="customElement">Custom Element Attr</label>
					<input id="customElement" type="text" name="customElement">
				</div>
				<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
				<input type="hidden" name="function" value="addInclude">
				<input type="submit" value="Add">
				<p class="error"></p>
			</form>

			<?php
				$includes = $page->get_includes();
				if ($includes) {
					echo '<hr>';
					echo '<ul class="itemList">';
					foreach ($includes as $includeKey => $include) {
						echo '<li>';
						echo $include['url'] . ' <i>' . $include['customElement'] . '</i> (' . $include['type'] . ') ';
						echo '<a class="button deleteInclude" href="javascript:;" data-include-id="' . $includeKey . '">Delete</a>';
						echo '</li>';
					}
					echo '</ul>';
				}

			?>

		</section>

		<section id="inlineCode" class="toggleSections">

			<h2>Inline Code</h2>

			<div class="gridDesktop">
				<div>
					<form class="basicForm" method="POST" autocomplete="off">
						<label for="inlineCSS">CSS</label>
						<textarea id="inlineCSS" class="bigTextArea" name="css"><?php echo $page->get_css() ?></textarea>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
						<input type="hidden" name="function" value="updatePageCSS">
						<br><br>
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>
				</div>
				<div>
					<form class="basicForm" method="POST" autocomplete="off">
						<label for="inlineJS">JS (not AMP complient)</label>
						<textarea id="inlineJS" class="bigTextArea" name="js"><?php echo $page->get_js() ?></textarea>
						<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
						<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
						<input type="hidden" name="function" value="updatePageJS">
						<br><br>
						<input type="submit" value="Update">
						<p class="error"></p>
					</form>
				</div>
		</section>

		<section id="taxonomy" class="toggleSections">

			<div class="gridDesktop">

				<div>

					<h2>Categories</h2>

					<form id="categoryForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="categoryId">Category</label>
							<select name="categoryId" id="categoryId">
								<option>- Select One -</option>
							<?php
							$categories = $taxonomy->get_categoryList();
							$curCategories = $taxonomy->get_curCategoryList();
							foreach ($categories as $categoryKey => $category) {
								$categoryInUse = false;
								foreach ($curCategories as $curCategoryKey => $curCategory) {
									if ($categoryKey ==  $curCategoryKey) {
										$categoryInUse = true;
									}
								}
								if ($categoryInUse == false) {
									echo '<option value="' . $categoryKey . '">' . $category['name'] . '</option>';
								}
							}
							?>
							</select>
						</div>
						<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
						<input type="hidden" name="function" value="addPageCategory">
						<input type="submit" value="Add">
						<p class="error"></p>
					</form>	

					<?php
					if ($curCategories) {
						echo '<ul class="itemList">';
						foreach ($curCategories as $curCategoryKey => $curCategory) {
							$categoryName = $categories[$curCategoryKey]['name'];
							echo "<li>";
							echo $categoryName . ' ';
							echo '<a class="button deletePageCategory" href="javascript:;" data-id="' . $curCategory['lookupId'] . '" data-name="' . str_replace(["'",'"'],"", $categoryName) . '">Delete</a>';
							echo '</li>';
						}
						echo "</ul>";
					}

					?>

				</div>

				<div>

					<h2>Tags</h2>

					<form id="tagForm" class="basicForm" method="POST" autocomplete="off">
						<div class="formElement">
							<label for="tagId">Tag</label>
							<select name="tagId" id="tagId">
								<option>- Select One -</option>
							<?php
							$tags = $taxonomy->get_tagList();
							$curTags = $taxonomy->get_curTagList();
							foreach ($tags as $tagKey => $tag) {
								$tagInUse = false;
								foreach ($curTags as $curTagKey => $curTag) {
									if ($tagKey ==  $curTagKey) {
										$tagInUse = true;
									}
								}
								if ($tagInUse == false) {
									echo '<option value="' . $tagKey . '">' . $tag['name'] . '</option>';
								}
							}
							?>
							</select>
						</div>
						<input type="hidden" name="pageId" value="<?php echo $page->get_id() ?>">
						<input type="hidden" name="function" value="addPageTag">
						<input type="submit" value="Add">
						<p class="error"></p>
					</form>	

					<?php
					if ($curTags) {
						echo '<ul class="itemList">';
						foreach ($curTags as $curTagKey => $curTag) {
							$tagName = $tags[$curTagKey]['name'];
							echo "<li>";
							echo $tagName . ' ';
							echo '<a class="button deletePageTag" href="javascript:;" data-id="' . $curTag['lookupId'] . '" data-name="' . str_replace(["'",'"'],"", $tagName) . '">Delete</a>';
							echo '</li>';
						}
						echo "</ul>";
					}

					?>

				</div>

			</div>

		</section>

		<section id="custom" class="toggleSections">

			<h2>Custom Fields</h2>

			<form id="customFieldForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="displayImage">Display Image</label>
					<input id="displayImage" type="text" name="displayImage" placeholder="Display Image" value="<?php echo $page->get_customFields()['displayImage'] ?>" required>
					<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="displayImage">
						<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images">
					</a>
				</div><br>
				<div class="formElement">
					<label for="displayImageAltText">Display Image Alt Text</label>
					<input id="displayImageAltText" type="text" name="displayImageAltText" placeholder="Display Image Alt Text" value="<?php echo $page->get_customFields()['displayImageAltText'] ?>" required>
				</div><br>
				<!-- Add more -->
				<input type="hidden" name="fileName" value="<?php echo $page->get_file() ?>">
				<input type="hidden" name="function" value="updatePageCustomFields">
				<input type="submit" value="Update">
				<p class="error"></p>
			</form>

		</section>


		<div class="twoColumnWrapper">

			<div class="twoColumn htmlColumn">

				<h2>HTML</h2>
				<div id="elementWrapper">
					<p>Loading...</p>
				</div>

			</div>

			<div class="twoColumn previewColumn">

				<h2>
					Preview
					<a class="previewTab" target="_blank">
						<img class="newTabIcon" src="icons/new-tab.svg" alt="Open in new tab" title="Open in new tab" />
					</a>
				</h2>

				<?php 
				if ($page->get_type() == "database_template") {

					if ($page->get_database()) {

						$entries = $database->get_entries();
						$i = 1;
						echo '<p class="dbEntries"> <strong>DB Entries:</strong> ';
						if ($entries) {
							foreach ($entries as $entryKey => $entry) {
								if ($i == 1) {
									echo '<a class="button changeDbEntryPreview active" href="javascript:;" data-entry-id="' . $entryKey . '">';
								} else {
									echo '<a class="button changeDbEntryPreview" href="javascript:;" data-entry-id="' . $entryKey . '">';
								}
								echo $i;
								echo '</a> ';
								$i++;
							}
						}
						echo '</p>';

					}

				}
				?>

		 		<iframe id="elementPreviewFrame" title="element preview"></iframe> 

		 	</div>

		</div>

	</div>
	<script>
		var mainNavToggleClose = true;
	</script>
	<script src="js/general.js"></script>
	<script src="js/edit_page_module.js"></script>
	<script src="js/edit_elements.js"></script>

</body>
</html>

<?php
} else {
	echo "Page ID cannot be empty.";
}
?>
