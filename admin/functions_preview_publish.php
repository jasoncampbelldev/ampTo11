
<?php
require 'json_db_extended.php';
require 'classes.php';
require 'constants.php';
require 'php_module_functions.php';
$settings = new Settings();
$GLOBALS['globalCSS'] = $settings->get_globalCSS();
$GLOBALS['globalIncludes'] = $settings->get_globalIncludes();
$GLOBALS['urlBase'] = $settings->get_urlBase();

if ($_GET["function"]) {

	$functionName = $_GET["function"];

	if ($functionName == "printPreview") {
		$id = $_GET["editId"];
		$type = $_GET["editType"];
		$dbEntry = $_GET["dbEntry"];
		if ($type == "page") {
			$html = printPagePreview($id,$dbEntry);
		} else {
			$html = printModule($id,$dbEntry);
		}
		$html = str_replace("[%url%]",$GLOBALS['urlBase'],$html);
		echo $html;
	}

	if ($functionName == "publishPage") {
		$id = $_GET["pageId"];
		publishPage($id);
	}

	if ($functionName == "publishAllPages") {
		$result = getTableRows("pages","page_array");
		if ($result['status']) {
			foreach ($result['data'] as $pageKey => $page) {
				publishPage($pageKey);
			}
		}
	}

}

function publishPage($id){
	$preview = false;
	$page = new Page($id);
	$page->publish_elements();
	$page->set_modDate();
	$result;
	if ( $page->get_type() == "database_template") {
		$databaseId = $page->get_database();
		$database = new Database($databaseId);
		$entries = $database->get_entries();
		$pageUrl = $page->get_id();
		if ( $page->get_url() ) {
			$pageUrl = $page->get_url();
		}
		foreach ( $entries as $entry_key => $entry ) {
			$url = $pageUrl . "-" . $entry_key;
			if ( $entry['url'] ) {
				$url = $pageUrl . "-" . $entry['url'];
			}
			$result = exportPage($id,$url,$entry_key);
		}
	} else {
		$url = $page->get_id();
		if ( $page->get_url() ) {
			$url = $page->get_url();
		}
		$result = exportPage($id,$url,"");
	}	
}


function exportPage($id,$url,$dbEntry){
	date_default_timezone_set('America/Los_Angeles');
	ob_start("ob_gzhandler");
	$html = printPage($id,$dbEntry);
	$html = str_replace("[%url%]",$GLOBALS['urlBase'],$html);
	echo $html;
	$fp = fopen("../" . $url . ".html", 'w'); // open the cache file for writing
	fwrite($fp, ob_get_contents()); // save the contents of output buffer to the file
	fclose($fp); // close the file
	ob_end_flush(); // Send the output to the browser
}



// Print HTML Functions

$GLOBALS['Collect'];
$GLOBALS['ModuleIndexArray'] = [];
$GLOBALS['ModuleContentArray'] = [];
$GLOBALS['DbFields'];
$GLOBALS['DbEntries'];
$GLOBALS['CollectChildContent'];


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


function getPageHeader($page,$preview) {
	// get database feilds stored in DbFields global variable
	$dbFields = $GLOBALS['DbFields'];
	$ampDisabled = $page->get_ampDisabled();
	$GLOBALS['Collect'] .= "<!doctype html>\n";
	if ($ampDisabled == "true") {
		$GLOBALS['Collect'] .= "<html>\n";
	} else {
		$GLOBALS['Collect'] .= "<html amp>\n";
	}
	// print header
	$GLOBALS['Collect'] .= "<head>\n";
	// required for AMP
	$GLOBALS['Collect'] .= '<meta charset="utf-8">';
	$GLOBALS['Collect'] .= "\n";
	if ($ampDisabled != "true") {
		$GLOBALS['Collect'] .= '<script async src="https://cdn.ampproject.org/v0.js"></script>';
		$GLOBALS['Collect'] .= "\n";
	}
	$url = $page->get_id();
	if ($page->get_url() ) { $url = $page->get_url();}
	$GLOBALS['Collect'] .= '<link rel="canonical" href="[%url%]/' . $url . '.html">';
	$GLOBALS['Collect'] .= "\n";
	if ($preview == false || $preview == "mobile") {
		$GLOBALS['Collect'] .=  '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">';
		$GLOBALS['Collect'] .= "\n";
	}
	if ($ampDisabled != "true") {
		$GLOBALS['Collect'] .= $GLOBALS['ampBoilerplate'] . "\n";
	}
	if ($page->get_seoTitle()) {
		$seoTitle = $page->get_seoTitle();
		if ($dbFields) {
			$seoTitle = replaceContentDBTokens($seoTitle,$dbFields);						
		}		
		$GLOBALS['Collect'] .=  "<title>" . $seoTitle . "</title>\n";
	}
	if ($page->get_seoDescription()) {
		$seoDescription = $page->get_seoDescription();
		if ($dbFields) {
			$seoDescription = replaceContentDBTokens($seoDescription,$dbFields);		
		}			
		$GLOBALS['Collect'] .=  '<meta name="description" content="' . $seoDescription . '">';
		$GLOBALS['Collect'] .= "\n";
	}

	$jsonLd = $page->get_jsonLd();
	if (is_array($jsonLd)) {
	    $GLOBALS['Collect'] .= '<script type="application/ld+json">{ "@context": "https://schema.org"';
	    if ($jsonLd['type']) {
	    	$GLOBALS['Collect'] .= ',"@type": "' . $jsonLd['type'] . '"';
	    }
	    if ($jsonLd['headline']) {
	    	$GLOBALS['Collect'] .= ',"headline": "' . $jsonLd['headline'] . '"';
	    } elseif ($seoTitle) {
	    	$GLOBALS['Collect'] .= ',"headline": "' . $seoTitle . '"';
	    }
	    if ($jsonLd['image1x1'] || $jsonLd['image4x3'] || $jsonLd['image16x9']) {
	    	$GLOBALS['Collect'] .=  ',"image": [';
	    	$jsonLdImageArray = [];
	    	if ($jsonLd['image1x1']) { array_push($jsonLdImageArray, $jsonLd['image1x1']); }
	    	if ($jsonLd['image4x3']) { array_push($jsonLdImageArray, $jsonLd['image4x3']); }
	    	if ($jsonLd['image16x9']) { array_push($jsonLdImageArray, $jsonLd['image16x9']); }
	    	$i = 1;
	    	foreach ($jsonLdImageArray as $jsonLdImage) {
	    		$GLOBALS['Collect'] .= '"' . $jsonLdImage . '"';
	    		if ($i < count($jsonLdImageArray)) { $GLOBALS['Collect'] .= ','; }
	    		$i++;
	    	}

	    	$GLOBALS['Collect'] .=  ']';
	    }
	    if ($page->get_pubDate()) {
			$GLOBALS['Collect'] .= ',"datePublished": "' . $page->get_pubDate() . '"';
		}
	    if ($jsonLd['author']) {				
	      	$GLOBALS['Collect'] .= ',"author": { "@type": "Person", "name": "' . $jsonLd['author'] . '"}';
	    }
	    if ($jsonLd['publisher']) {				
	      	$GLOBALS['Collect'] .= ',"publisher": { "@type": "Organization", "name": "' . $jsonLd['publisher'] . '"';
	    	if ($jsonLd['publisherLogo']) {	
				$GLOBALS['Collect'] .= ',"logo": { "@type": "ImageObject","url": "' . $jsonLd['publisherLogo'] . '" }';
			}
			$GLOBALS['Collect'] .= '}';
	    }

	    $GLOBALS['Collect'] .=  '}</script>';
	    $GLOBALS['Collect'] .= "\n";
	}


	$metaTags = $page->get_metaTags();
	if (is_array($metaTags)) {
		// print meta tags
		foreach ($metaTags as $metaTagKey => $metaTag) {
			$metaTagVal = $metaTag['value'];
			if ($dbFields) {
				$metaTagVal = replaceContentDBTokens($metaTagVal,$dbFields);			
			}			
			$GLOBALS['Collect'] .= '<meta ' . $metaTag['type'] . '="' . $metaTag['name'] . '" content="' . $metaTagVal . '"/>';
			$GLOBALS['Collect'] .= "\n";
		}
	}
	$globalIncludes = $GLOBALS['globalIncludes'];
	if (is_array($globalIncludes)) {
		// print header global includes
		foreach ($globalIncludes as $includeKey => $include) {
			if ($include['type'] == "ampJS" && $ampDisabled != "true") {
				$GLOBALS['Collect'] .= '<script async custom-element="' . $include['customElement'] . '" src="' . $include['url'] . '"></script>';
			} elseif ($include['type'] == "js" && $ampDisabled == "true") {
				$GLOBALS['Collect'] .= '<script async src="' . $include['url'] . '"></script>';
			} elseif ($include['type'] == "css" && $ampDisabled == "true") {
				$GLOBALS['Collect'] .= '<link rel="stylesheet" href="' . $include['url'] . '" />';
			}
			$GLOBALS['Collect'] .= "\n";
		}
	}
	$includes = $page->get_includes();
	if ( is_array($includes) ) {
		// print header page includes
		foreach ($includes as $includeKey => $include) {
			if ($include['type'] == "ampJS") {
				$GLOBALS['Collect'] .= '<script async custom-element="' . $include['customElement'] . '" src="' . $include['url'] . '"></script>';
			} elseif ($include['type'] == "js") {
				$GLOBALS['Collect'] .= '<script async src="' . $include['url'] . '"></script>';
			} elseif ($include['type'] == "css") {
				$GLOBALS['Collect'] .= '<link rel="stylesheet" href="' . $include['url'] . '" />';
			}
			$GLOBALS['Collect'] .= "\n";
		}
	}
	// print inline css
	$GLOBALS['Collect'] .= '<style amp-custom>' . $GLOBALS['constGlobalCSS'] . ' ' . $GLOBALS['globalCSS'] . ' ' . $page->get_css() . '</style>';
	$GLOBALS['Collect'] .= "</head>\n";
	$GLOBALS['Collect'] .= "<body>\n";
}

function getPageFooter($page) {
	$ampDisabled = $page->get_ampDisabled();
	$globalIncludes = $GLOBALS['globalIncludes'];
	if (is_array($globalIncludes)) {
		// print footer global JS includes
		foreach ($globalIncludes as $includeKey => $include) {
			if ($include['type'] == "jsFooter" && $ampDisabled == "true") {
				$GLOBALS['Collect'] .= '<script src="' . $include['url'] . '"></script>';
			}
		}
	}
	$includes = $page->get_includes();
	if (is_array($includes)) {
		// print footer page JS includes
		foreach ($includes as $includeKey => $include) {
			if ($include['type'] == "jsFooter") {
				$GLOBALS['Collect'] .= '<script src="' . $include['url'] . '"></script>';
			}
		}
	}
	if ($page->get_js()) {
		// print inline JS
		$GLOBALS['Collect'] .= '<script>' . $page->get_js() . '</script>';
	}
	$GLOBALS['Collect'] .= "</body>";
	$GLOBALS['Collect'] .= "</html>";
}

function getChildContent($elementArray,$parentId,$vars) {

	// loop through all elements in elementArray
	foreach ($elementArray as $elementKey => $element) {
		if ($element['parentId'] == $parentId) {
			$elementId = $element['id'];

			// if element is module type see if the ID exists in the ModuleContentArray global variable
			if ($element['type'] == "module") {
				if ($GLOBALS['ModuleContentArray'][$elementId]) {
					$GLOBALS['CollectChildContent'] .= $GLOBALS['ModuleContentArray'][$elementId];
				}
			} else {
				// if element has a name print the begining of the tag
				if ($element['name']) {
					$topTag = "\n<" . $element['name'];
					// print attributes
					if ($element['attrs']) {
						foreach ($element['attrs'] as $elementAttr => $elementAttrValue ) {
							$topTag .= ' ' . $elementAttr . '="' . $elementAttrValue . '"';
						}
					}
					$topTag .= ">";

					$GLOBALS['CollectChildContent'] .= $topTag;
				}

				$content = "";
				// get database feilds stored in DbFields global variable
				$dbFields = $GLOBALS['DbFields'];
				//print_r($vars);
				if ($vars != '') {
					// replace {{...}} with module variable
					$content = $element['content'];
					if ($vars) {
						foreach ($vars as $varKey => $var) {
							$content = str_replace("{{" . $varKey . "}}",$var,$content);
						}

						if ($dbFields) {
							$content = replaceContentDBTokens($content,$dbFields);						
						}
					}
				} else {
					$content = $element['content'];
					if ($dbFields) {
						$content = replaceContentDBTokens($content,$dbFields);			
					}
				}

				$GLOBALS['CollectChildContent'] .= $content;
				
				// get content of child elements
				getChildContent($elementArray,$elementId,$vars);

				// if element has a name print end of the tag 
				if ($element['name']) {
					$GLOBALS['CollectChildContent'] .= "</" . $element['name'] . ">\n";
				} 
			}
		}
	}
}


function addAllModulesToIndexArray($elementArray,$parentId,$curLevel) {
	// loop through all elements in elementArray
	foreach ($elementArray as $elementKey => $element) {
		$curLevel++;
		// if child of parentId and type is module add to global ModuleIndexArray
		if ($element['parentId'] == $parentId) {
			if ($element['type'] == "module") {
				$array = [];
				$array['id'] = $element['id'];
				$array['moduleId'] = $element['moduleId'];
				$array['vars'] = $element['vars'];
				$array['level'] = $curLevel;
				//print_r($array);
				array_push($GLOBALS['ModuleIndexArray'],$array);
			}
			addAllModulesToIndexArray($elementArray,$element['id'],$curLevel);
		}
	}

}

function addContentToModuleArray($moduleIndexArray,$elementArray) {

	// set ModuleContentArray global variable as empty array
	$GLOBALS['ModuleContentArray'] = [];

	// loop through all modules in module index array
	foreach ($moduleIndexArray as $moduleIndexKey => $moduleIndex) {	
		$elementId = $moduleIndex['id'];
		$moduleId = $moduleIndex['moduleId'];
		$moduleVars = $moduleIndex['vars'];
		$module = new Module($moduleId);


		// if module is php function
		if ($module->get_type() == "php") {
			if ($module->get_functionName()) {
				// replace {{...}} with DB entry field
				$dbFields = $GLOBALS['DbFields'];;
				if ($dbFields && $moduleVars) {
					$newElementVars = [];
					foreach ($moduleVars as $elementVarKey => $elementVar) {
						foreach ($dbFields as $dbFieldKey => $dbField) {
							$keyCurly = "{{" . strval($dbFieldKey) . "}}";
							if (strpos( $elementVar, $keyCurly ) !== false) {
								$elementVar = str_replace($keyCurly,$dbField,$elementVar);
								$newElementVars[$elementVarKey] = $elementVar;
							} else {
								$newElementVars[$elementVarKey] = $elementVar;
							}
						}	
					}
					$moduleVars = $newElementVars;			
				}

				// get content from child elements and add it to CollectChildContent global variable
				getChildContent($elementArray,$elementId,$moduleVars);

				// get content stored in CollectChildContent global variable
				$content = $GLOBALS['CollectChildContent'];

				// if content exists add it to the %content% module variable
				if ($content) {
					$moduleVars['%content%'] = $content;
				}

				// run the function and add the result to the ModuleContentArray global variable
				$GLOBALS['ModuleContentArray'][$elementId] = call_user_func($module->get_functionName(), $moduleVars);
			}
		} else {
			
			// if html mobule add content to ModuleContentArray global variable

			// get content from child elements and add it to CollectChildContent global variable
			getChildContent($elementArray,$elementId,$moduleVars);
			$elementContent = $GLOBALS['CollectChildContent'];

			// clear CollectChildContent global variable
			$GLOBALS['CollectChildContent'] = "";

			// get element array from module class
			$moduleElementArray = $module->get_elements();

			// order moduleElementArray by element order numbers
			$moduleElementArray = sortByOrder($moduleElementArray);

			// get content from child elements and add it to CollectChildContent global variable
			getChildContent($moduleElementArray,'',$moduleVars);

			// replace [%content%] token with element children content
			$moduleHtml = str_replace("[%content%]",$elementContent,$GLOBALS['CollectChildContent']);

			// add html to ModuleContentArray global variable
			$GLOBALS['ModuleContentArray'][$elementId] = $moduleHtml;
				
		}

		// clear CollectChildContent variable
		$GLOBALS['CollectChildContent'] = "";
	}
}

function makeModuleContentArray($page) {
	$elementArray = $page->get_elements();

	if ($elementArray) {

		// order elementArray by element order numbers
		$elementArray = sortByOrder($elementArray);

		// set ModuleIndexArray global variable as empty array
		$GLOBALS['ModuleIndexArray'] = [];

		// add all modules to ModuleIndexArray global variable
		addAllModulesToIndexArray($elementArray,'',0);

		// sort ModuleIndexArray by level inner to outer
		usort($GLOBALS['ModuleIndexArray'], function($a, $b) {
    		return $b['level'] - $a['level'];
		});

		//print_r($GLOBALS['ModuleIndexArray']);

		// add content to ModuleContentArray global variable using ModuleIndexArray global variable
		addContentToModuleArray($GLOBALS['ModuleIndexArray'],$elementArray);

		//print_r($GLOBALS['ModuleContentArray']);

	}
}

function getHtmlContent($page) {

	// render module content first
	makeModuleContentArray($page);

	$elementArray = $page->get_elements();

	if ($elementArray) {
		// sort elementArray by element order numbers
		$elementArray = sortByOrder($elementArray);

		// add all page content to CollectChildContent global variable
		getChildContent($elementArray,'','');

		// add CollectChildContent global variable to Collect global variable
		$GLOBALS['Collect'] .= $GLOBALS['CollectChildContent'];

		// clear CollectChildContent variable
		$GLOBALS['CollectChildContent'] = "";
	}
}

function setDatabaseGlobalVars($page,$dbEntry) {
	// clear variables
	$GLOBALS['DbFields'] = "";
	$GLOBALS['DbEntries'] = "";

	// add values to DbFields global varable and entries to DbEntries global variable
	if ($page->get_database()) {
		$database = New Database( $page->get_database() );
		$dbEntries  = $database->get_entries();
		if ($dbEntries) {
			$dbFields;
			if ($dbEntry) {
				$dbFields = $dbEntries[$dbEntry];
			} else {
				// if no entry (dbEntry) is specified use the first entry
				$firstKey = key($dbEntries);
				$dbFields = $dbEntries[$firstKey];
			}
			$GLOBALS['DbFields'] = $dbFields;
		}
		$GLOBALS['DbEntries'] = $dbEntries;
	}
}

function printPagePreview($id,$dbEntry) {
	// clear Collect global variable
	$GLOBALS['Collect'] = "";

	// get Page class
	$page = new Page($id);

	// if database page, add field values to DbFields global varable and entries to DbEntries global variable
	if ($page->get_type() == "database_template") {
		setDatabaseGlobalVars($page,$dbEntry);
	}

	// construct page
	getPageHeader($page,true);
	getHtmlContent($page);
	getPageFooter($page);

	// return htm collected in Collect global variable
	return $GLOBALS['Collect'];
}

function printPage($id,$dbEntry) {
	// clear Collect global variable
	$GLOBALS['Collect'] = "";

	// get Page class
	$page = new Page($id);

	// if database page, add field values to DbFields global varable and entries to DbEntries global variable
	if ($page->get_type() == "database_template") {
		setDatabaseGlobalVars($page,$dbEntry);
	}

	// construct page
	getPageHeader($page,false);
	getHtmlContent($page);
	getPageFooter($page);

	// return htm collected in Collect global variable
	return $GLOBALS['Collect'];
}

function getModuleContent($module) {

	$elementArray = $module->get_elements();

	if ($elementArray) {
		// sort elementArray by element order numbers
		$elementArray = sortByOrder($elementArray);

		// add all module content to CollectChildContent global variable
		getChildContent($elementArray,'','');

		$GLOBALS['Collect'] .= $GLOBALS['CollectChildContent'];
	}
}

function printModule($id) {
	// clear Collect global variable
	$GLOBALS['Collect'] = "";

	// get Module class
	$module = new Module($id);

	// construct module
	getHtmlContent($module);

	// return htm collected in Collect global variable
	return $GLOBALS['Collect'];
}

?>