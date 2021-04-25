
<?php
require 'json_db_extended.php';
require 'classes.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home Page</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link href="css/style.css" rel="stylesheet">

</head>

<body class="homePage">
	<div class="topNav">
		<a id="mobileMenu" href="javascript:;"><img src="icons/menu.svg" alt="menu"/></a>
		<div class="topNavInner">
			<nav>
				<a class="navLink navHomeLink" href="index.php">Home</a>
				<a class="navLink toggleSectionsLink  active" href="javascript:;" data-section="pages">Pages</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="modules">Modules</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="databases">Databases</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="categories">Categories</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="tags">Tags</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="exports">Exports</a>
				<a class="navLink toggleSectionsLink" href="javascript:;" data-section="settings">Settings</a>
				<div class="floatRight desktopOnly">
					<a class="navLink navImagesLink" href="images.php" target="_blank">
						<img class="navImagesIcon" src="icons/images.svg" alt="image admin" title="image admin" />
					</a>
				</div>
			</nav>
		</div>
	</div>

	<div id="dialog"></div>

	<div class="wrapper">
		<section id="pages" class="toggleSections active">

			<h2>Pages</h2>

			<form id="newPageForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="pageName">Name</label>
					<input id="pageName" type="text" name="name" placeholder="page name" required>
				</div>
				<div class="formElement">
					<label for="pageType">Type</label>
					<select id="pageType" name="type">
						<option value="page">Page</option>
						<option value="post">Post</option>
						<option value="database_template">Database Template</option>
					</select>
				</div>
				<input type="hidden" name="function" value="addPage">
				<div class="formElement">
					<input type="submit" value="Add">
				</div>
			</form>

			<hr>

			<form id="sortPageForm" autocomplete="off">
				<div class="formElement">
					<label for="pageFilterType">Type</label>
					<select id="pageFilterType" name="pageFilterType">
						<option value="all" selected="">All</option>
						<option value="page">Page</option>
						<option value="post">Post</option>
						<option value="database_template">Database Template</option>
					</select>
				</div>
				<div class="formElement">
					<label for="pageSortBy">Sort By</label>
					<select id="pageSortBy" name="pageSortBy">
						<option value="orderAsc" selected>Order Asc</option>
						<option value="alphabetical">Alphabetical</option>
						<option value="orderDesc">Order Desc</option>
					</select>
				</div>
				<div class="formElement">
					<input type="submit" value="Sort">
				</div>
			</form>

			<hr>

			<div id="pageList">Loading...</div>

		</section>

		<section id="modules" class="toggleSections">

			<h2>Modules</h2>

			<form id="newModuleForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="moduleName">Name</label>
					<input id="moduleName" type="text" name="name" placeholder="module name" required>
				</div>
				<div class="formElement">
					<label for="moduleType">Type</label>
					<select id="moduleType" name="type">
						<option value="html">HTML Template</option>
						<option value="php">PHP Function</option>
					</select>
				</div>
				<input type="hidden" name="function" value="addModule">
				<div class="formElement">
					<input type="submit" value="Add">
				</div>
			</form>

			<hr>

			<form id="sortModuleForm" autocomplete="off">
				<div class="formElement">
					<label for="moduleFilterType">Type</label>
					<select id="moduleFilterType" name="moduleFilterType">
						<option value="all" selected="">All</option>
						<option value="html">HTML Template</option>
						<option value="php">PHP Function</option>
					</select>
				</div>
				<div class="formElement">
					<label for="moduleSortBy">Sort By</label>
					<select id="moduleSortBy" name="pageSortBy">
						<option value="orderAsc" selected>Order Asc</option>
						<option value="alphabetical">Alphabetical</option>
						<option value="orderDesc">Order Desc</option>
					</select>
				</div>
				<div class="formElement">
					<input type="submit" value="Sort">
				</div>
			</form>

			<hr>

			<div id="moduleList">Loading...</div>

		</section>

		<section id="databases" class="toggleSections">

			<h2>Databases</h2>

			<form id="newDatabaseForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="moduleName">Name</label>
					<input id="moduleName" type="text" name="name" placeholder="database name" required>
				</div>
				<input type="hidden" name="function" value="addDatabase">
				<div class="formElement">
					<input type="submit" value="Add">
				</div>
			</form>

			<hr>

			<form id="sortDatabaseForm" autocomplete="off">
				<div class="formElement">
					<label for="databaseSortBy">Sort By</label>
					<select id="databaseSortBy" name="pageSortBy">
						<option value="orderAsc" selected>Order Asc</option>
						<option value="alphabetical">Alphabetical</option>
						<option value="orderDesc">Order Desc</option>
					</select>
				</div>
				<div class="formElement">
					<input type="submit" value="Sort">
				</div>
			</form>

			<hr>

			<div id="databaseList">Loading...</div>

		</section>

		<section id="categories" class="toggleSections">

			<h2>Categories</h2>

			<div id="newCategoryFormWrapper">
				<form id="newCategoryForm" class="basicForm" method="POST" autocomplete="off">
					<div class="formElement">
						<label for="categoryName">Name</label>
						<input id="categoryName" type="text" name="name" required>
					</div>
					<div class="formElement">
						<label for="categoryUrl">URL</label>
						<input id="categoryUrl" type="text" name="url" class="noSpacesAllowed" required>
					</div>
					<div class="formElement">
						<label for="categoryParent">Parent</label>
						<select id="categoryParent" type="text" name="parentId">
							<option value="">None</option>
							<?php
								$result = getTableRows("taxonomy","category_array");
								if ($result['status']) {
									foreach ($result['data'] as $key => $row) {
										echo '<option value="' . $key . '">' . $row['name'] . '</option>';
									}
								}
							?>
						</select>
					</div>
					<input type="hidden" name="function" value="addCategory">
					<div class="formElement">
						<input type="submit" value="Add">
					</div>
					<div class="error"></div>
				</form>
			</div>

			<hr>

			<div id="categoryEditWrapper"></div>

			<div id="categoryList">Loading...</div>

		</section>

		<section id="tags" class="toggleSections">

			<h2>Tags</h2>

			<div id="newTagFormWrapper">
				<form id="newTagForm" method="POST" autocomplete="off">
					<div class="formElement">
						<label for="tagName">Name</label>
						<input id="tagName" type="text" name="name" required>
					</div>
					<div class="formElement">
						<label for="tagUrl">URL</label>
						<input id="tagUrl" type="text" name="url" class="noSpacesAllowed" required>
					</div>
					<input type="hidden" name="function" value="addTag">
					<div class="formElement">
						<input type="submit" value="Add">
					</div>
					<div class="error"></div>
				</form>
			</div>

			<hr>

			<div id="tagEditWrapper"></div>

			<div id="tagList">Loading...</div>

		</section>

		<section id="exports" class="toggleSections">

			<h2>Exports</h2>

			<div>
				<a id="exportTagJSON" class="button" href="javascript:;">Tag JSON</a>
				<p class="status"></p>
			</div>

			<div>
				<a id="exportCategoryJSON" class="button" href="javascript:;">Category JSON</a>
				<p class="status"></p>
			</div>

			<div>
				<a id="exportPageJSON" class="button" href="javascript:;">Page JSON</a>
				<p class="status"></p>
			</div>

			<div>
				<a id="exportSiteMap" class="button" href="javascript:;">Site Map XML</a>
				<p class="status"></p>
			</div>				


		</section>

		<section id="settings" class="toggleSections">

			<h2>Settings</h2>

			<?php $settings = new Settings(); ?>

			<form id="urlBaseForm" class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="urlBase">URL Base</label>
					<input id="urlBase" type="text" name="urlBase" class="noSpacesAllowed" value="<?php echo $settings->get_urlBase(); ?>" required>
					<?php if ( $settings->get_urlBase() == "" ) { ?>
						<p class="small m0">example: https://example.com or https://example.com/mySite</p>
					<?php } ?>
				</div>
				<br>
				<input type="hidden" name="function" value="updateUrlBase">
				<input type="submit" value="Update">
				<div class="error"></div>
			</form>

			<form id="globalCSSForm" class="basicForm mt20" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="globalCSS">Global CSS</label>
					<textarea id="globalCSS" name="globalCSS" class="bigTextArea"><?php echo $settings->get_globalCSS(); ?></textarea>
				</div>
				<br>
				<input type="hidden" name="function" value="updateGlobalCSS">
				<input type="submit" value="Update">
				<div class="error"></div>
			</form>

			<h3>Includes</h3>

			<form class="basicForm" method="POST" autocomplete="off">
				<div class="formElement">
					<label for="includesType">Type</label>
					<select id="includesType" name="type">
						<option value="ampJS">Amp JS</option>
						<option value="js">JS (non-Amp pages)</option>
						<option value="jsFooter">JS Footer (non-Amp pages)</option>
						<option value="css">CSS (non-Amp pages)</option>
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
				<input type="hidden" name="function" value="addGlobalInclude">
				<input type="submit" value="Add">
				<p class="error"></p>
			</form>

			<?php
				$includes = $settings->get_globalIncludes();
				if ($includes) {
					echo '<hr>';
					echo '<ul class="itemList">';
					foreach ($includes as $includeKey => $include) {
						echo '<li>';
						echo $include['url'] . ' <i>' . $include['customElement'] . '</i> (' . $include['type'] . ') ';
						echo ' <a class="button deleteInclude" href="javascript:;" data-include-id="' . $includeKey . '">Delete</a>';
						echo '</li>';
					}
					echo '</ul>';
				}

			?>


		</section>


		<?php if ($settings->get_urlBase() == "") { ?>
			<p class="warningBanner">Don't forget to add your <a class="toggleSectionsLink" href="javascript:;" data-section="settings">URL Base</a> before you export!</p>
		<?php } ?>

	</div>

	<footer>


	</footer>
	<script>
		var mainNavToggleClose = false;
	</script>
	<script src="js/general.js"></script>
	<script src="js/edit_home.js"></script>


</body>
</html>