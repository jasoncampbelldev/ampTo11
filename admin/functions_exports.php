
<?php
require 'json_db_extended.php';
require 'classes.php';
require 'constants.php';
require 'php_module_functions.php';
$settings = new Settings();
$GLOBALS['globalCSS'] = $settings->get_globalCSS();
$GLOBALS['urlBase'] = $settings->get_urlBase();

if ($_GET["function"]) {

	$functionName = $_GET["function"];

	if ($functionName == "exportCategoryJSON") {
		$fileURL = $_GET["url"];
		$jsonArray = [];
		$taxonomy = new Taxonomy(false);

		if ($taxonomy->get_categoryList()) {
			$i = 0;
			foreach ($taxonomy->get_categoryList() as $categoryKey => $category) {
				$jsonArray[$i] = [];
				$jsonArray[$i]['index'] = $i;
				$jsonArray[$i]['name'] = $category['name'];
				$jsonArray[$i]['url'] = $category['url'];
				if ($taxonomy->get_categoryLookup()) {
					$jsonArray[$i]['pages'] = [];
					$j = 0;
					foreach ($taxonomy->get_categoryLookup() as $categoryLookupKey => $categoryLookup) {
						if ($categoryLookup['categoryId'] == $categoryKey) {
							$page = new Page($categoryLookup['pageId']);
							$pageUrls = getAllPageUrlTitles($page);

							print_r($pageUrls);
							foreach ($pageUrls as $pageUrlKey => $pageUrl) {
								$jsonArray[$i]['pages'][$j]['url'] = $pageUrl['url'];
								$jsonArray[$i]['pages'][$j]['title'] = $pageUrl['title'];
								$j++;
							}
						}
					}
				}
				$i++;
			}
		}

		$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

		$outputJSON = '{ "items": ' . $encodedJSON . '}';

		// write JSON back to file
		$file = fopen("../" . $fileURL, "w") or die("Unable to open file");
		fwrite($file, $outputJSON);
		fclose($file);

	}

	if ($functionName == "exportTagJSON") {
		$fileURL = $_GET["url"];
		$jsonArray = [];
		$taxonomy = new Taxonomy(false);

		if ($taxonomy->get_tagList()) {
			$i = 0;
			foreach ($taxonomy->get_tagList() as $tagKey => $tag) {
				$jsonArray[$i] = [];
				$jsonArray[$i]['index'] = $i;
				$jsonArray[$i]['name'] = $tag['name'];
				$jsonArray[$i]['url'] = $tag['url'];
				if ($taxonomy->get_tagLookup()) {
					$jsonArray[$i]['pages'] = [];
					$j = 0;
					foreach ($taxonomy->get_tagLookup() as $tagLookupKey => $tagLookup) {
						if ($tagLookup['tagId'] == $tagKey) {
							$page = new Page($tagLookup['pageId']);
							$pageUrls = getAllPageUrlTitles($page);

							//print_r($pageUrls);
							foreach ($pageUrls as $pageUrlKey => $pageUrl) {
								$jsonArray[$i]['pages'][$j]['url'] = $pageUrl['url'];
								$jsonArray[$i]['pages'][$j]['title'] = $pageUrl['title'];
								$j++;
							}
						}
					}
				}
				$i++;
			}
		}

		$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

		$outputJSON = '{ "items": ' . $encodedJSON . '}';

		// write JSON back to file
		$file = fopen("../" . $fileURL, "w") or die("Unable to open file");
		fwrite($file, $outputJSON);
		fclose($file);

	}

	if ($functionName == "exportPageJSON") {
		$fileURL = $_GET["url"];

		$jsonArray = [];

		$result = getTableRows("pages","page_array");
		if ($result['status']) {
			$i = 0;
			foreach ($result['data'] as $key => $row) {
				$page = new Page($key);
				$title = $page->get_seoTitle();
				$description = $page->get_seoDescription();
				$url;
				if ($page->get_type() == "database_template") {
					$databaseId = $page->get_database();
					$database = new Database($databaseId);
					$entries = $database->get_entries();
					$pageUrl = $page->get_id();
					if ($page->get_url()) {
						$pageUrl = $page->get_url();
					}
					foreach ($entries as $entry_key => $entry) {
						$url = $pageUrl . "-" . $entry_key;
						if ( $entry['url'] ) {
							$url = $pageUrl . "-" . $entry['url'];
						}
						$jsonArray[$i] = []; 

						$jsonArray[$i]['title'] = $title ? replaceContentDBTokens($title,$entry) : "";
						$jsonArray[$i]['description'] = $description ? replaceContentDBTokens($description,$entry) : "";
						$jsonArray[$i]['url'] = $url;
						$i++;
					}
				} else {
					$url = $page->get_id();
					if ($page->get_url()) {
						$url = $page->get_url();
					}
					$jsonArray[$i] = [];
					$jsonArray[$i]['title'] = $title ? $title : "";
					$jsonArray[$i]['description'] = $description ? $description : "";
					$jsonArray[$i]['url'] = $url;
					$i++;
				}
			}
		}

		$encodedJSON = json_encode($jsonArray,JSON_PRETTY_PRINT);

		$outputJSON = '{ "items": ' . $encodedJSON . '}';

		// write JSON back to file
		$file = fopen("../" . $fileURL, "w") or die("Unable to open file");
		fwrite($file, $outputJSON);
		fclose($file);

	}

	if ($functionName == "exportSiteMap") {
		$fileURL = $_GET["url"];
		$collect = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$collect .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

		function addUrlToCollect($url,$page) {
			$lastMod;
			if ($page->get_modDate()) { 
				$lastMod = $page->get_modDate(); 
			} elseif ($page->get_pubDate()) { 
				$lastMod = $page->get_pubDate(); 
			}
			$urlCollect = '<url>' . "\n";
			$urlCollect .= '<loc>' . $GLOBALS['urlBase'] . $url . '</loc>' . "\n";
			if ($lastMod) {
				$urlCollect .= '<lastmod>' . $lastMod . '</lastmod>' . "\n";
			}
			$urlCollect .= '</url>' . "\n";
			return $urlCollect;	
		}

		$result = getTableRows("pages","page_array");
		if ($result['status']) {
			foreach ($result['data'] as $key => $row) {
				$page = new Page($key);
				$title = $page->get_seoTitle();
				$url;
				if ($page->get_type() == "database_template") {
					$databaseId = $page->get_database();
					$database = new Database($databaseId);
					$entries = $database->get_entries();
					$pageUrl = $page->get_id();
					if ($page->get_url() ) {
						$pageUrl = $page->get_url();
					}
					foreach ($entries as $entry_key => $entry) {
						$url = $pageUrl . "-" . $entry_key;
						if ($entry['url']) {
							$url = $pageUrl . "-" . $entry['url'];
						}
						$collect .= addUrlToCollect($url,$page);
					}
				} else {
					$url = $page->get_id();
					if ($page->get_url()) {
						$url = $page->get_url();
					}
					$collect .= addUrlToCollect($url,$page);
				}
			}
		}

		$collect .= '</urlset>' . "\n";	

		// write JSON back to file
		$file = fopen("../" . $fileURL, "w") or die("Unable to open file");
		fwrite($file, $collect);
		fclose($file);

	}


}


function getEntryPageTitle($title,$entryFields) {
	if ($entryFields) {
		foreach ($entryFields as $entryFieldKey => $entryField) {
			$title = str_replace("{{" . $entryFieldKey . "}}",$entryField,$title);
		}						
	}
	return $title;				
}

function getAllPageUrlTitles($page) {
	$collectArray = [];
	$title = $page->get_seoTitle();
	$url;
	if ($page->get_type() == "database_template") {
		$databaseId = $page->get_database();
		$database = new Database($databaseId);
		$entries = $database->get_entries();
		$pageUrl = $page->get_id();
		if ($page->get_url()) {
			$pageUrl = $page->get_url();
		}
		foreach ($entries as $entry_key => $entry) {
			$url = $pageUrl . "-" . $entry_key;
			if ($entry['url']) {
				$url = $pageUrl . "-" . $entry['url'];
			}
			if ($url) {
				$pageArray = [];
				$pageArray['title'] = getEntryPageTitle($title,$entry);
				$pageArray['url'] = $url;
				array_push($collectArray,$pageArray);
			}
		}
	} else {
		$url = $page->get_id();
		if ($page->get_url()) {
			$url = $page->get_url();
		}
		if ($url) {
			$pageArray = [];
			$pageArray['title'] = getEntryPageTitle($title,$entry);
			$pageArray['url'] = $url;
			array_push($collectArray,$pageArray);
		}
	}

	return $collectArray;
}

?>