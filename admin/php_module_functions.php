
<?php

// Child content comes in the variable %content%
// Example:
// $content = $vars['%content%'];

$settings = new Settings;
$GLOBALS['urlBase'] = $settings->get_urlBase();


function ampImage($vars) {

	if ($vars['imageURL']) {

		// get image width and height
		$imageURL = str_replace(' ', "%20", $vars['imageURL']);
		list($width, $height) = getimagesize($imageURL);

		return '
		<div class="amp-img">
			<div style="max-width:' . $width . 'px;" >
				<amp-img
				  alt="'. $vars['altText'] .'" 
				  src="'. $vars['imageURL'] .'"  
				  width="' . $width . '"  
				  height="' . $height . '"  
				  layout="responsive" 
				>
					Unable to display image.
				</amp-img>
			</div>
			<div>'. $vars['%content%'] .'</div>
		</div>
		';

	}
}

function ampYoutube($vars) {

	if ($vars['youtubeID']) {

		$widthRatio = $vars['widthRatio'] ? $vars['widthRatio'] : 560;
		$heightRatio = $vars['heightRatio'] ? $vars['heightRatio'] : 315;

		$collect = '
		<div class="amp-youtube" ';
		if ($vars['maxWidth']) {
			$collect .= 'style="max-width:' . $vars['maxWidth'] . 'px;';
		}
		$collect .= '>
			<amp-youtube
			  width="'. $widthRatio .'" 
			  height="'. $heightRatio .'"  
			  data-videoid="' . $vars['youtubeID'] . '"   
			  layout="responsive" 
			>
				Unable to display video.
			</amp-youtube>
			<div>'. $vars['%content%'] .'</div>
		</div>
		';

		return $collect;

	}
}

function ampSocialShare($vars) {

	return '
	<div class="amp-social-share">
		<amp-social-share type="twitter" aria-label="Share on Twitter"></amp-social-share>
		<amp-social-share type="linkedin" aria-label="Share on LinkedIn"></amp-social-share>
		<amp-social-share type="pinterest" aria-label="Share on Pinterest" data-param-media="https://amp.dev/static/samples/img/amp.jpg"></amp-social-share>
		<amp-social-share type="email" aria-label="Share by email"></amp-social-share>
	</div>
	';

}

function displayPosts($vars) {

	$content = $vars['%content%'];
	$limit = $vars['limit'] ? $vars['limit'] : false;

	$posts = [];
	$result = getTableRows('pages','page_array');
	if ($result['status']) {
		foreach ($result['data'] as $pageKey => $page) {
			if ($page['type'] == "post") {
				if ($count < $limit || $limit == false) {
					$postData = new Page($pageKey);
					$pubDate = $postData->get_pubDate();
					$posts[$pageKey] = $page;
					$posts[$pageKey]['pubDate'] = $postData->get_pubDate();
					$posts[$pageKey]['seoDescription'] = $postData->get_seoDescription();
					$posts[$pageKey]['displayImage'] = $postData->get_customFields()['displayImage'];
					$posts[$pageKey]['displayImageAltText'] = $postData->get_customFields()['displayImageAltText'];
					$count++;
				}
			}
		}
	}
	$collect = '<div class="displayPosts">';
	$collect .= $content;
	$collect .= '<div class="displayPostsInner">';
	$count = 0;
	$posts = array_reverse(sortMultiArray($posts,"pubDate"));
	$postCount = count($posts);
	foreach ($posts as $postKey => $post){
		if ($count % 3 == 0 || (($count + 1) == $postCount && $count % 3 == 1)) {
			$collect .= '<div class="displayPost displayPostFullWidth">';
		} else {
			$collect .= '<div class="displayPost">';			
		}
		$collect .= '<a class="displayPostInner" href="' . $GLOBALS['urlBase'] . '/' . $post['url'] . '.html">';
		if ($post['displayImage']) {
			$imageVars = [];
			$imageVars['imageURL'] = $post['displayImage'];
			$imageVars['altText'] = $post['displayImageAltText'] ? $post['displayImageAltText'] : "image for " . $post['name'];
			$collect .= '<div class="displayPostImage">' . ampImage($imageVars) . "</div>\n";
			$collect .= "\n";
		}
		$collect .= '<div class="displayPostContent">';
		$collect .= '<h3>' . $post['name'] . "</h3>\n";
		if ($post['pubDate']) {
			$collect .= '<p class="date">' . date("M j Y", strtotime($post['pubDate'])) . "</p>\n";
		}
		$collect .= "<p>" . $post['seoDescription'] . "</p>\n";
		$collect .= "</div>\n";
		$collect .= "</a>\n";
		$collect .= "</div>\n";
		$count++;
	}
	$collect .= "</div>\n";
	$collect .= "</div>\n";

	return $collect;	


}


function tagList($vars) {

	$content = $vars['%content%'];
	$tagPageUrl = $vars['tagPageUrl'] ? $vars['tagPageUrl'] : "tags.html";

	$taxonomy = new Taxonomy('');
	$tags = $taxonomy->get_tagList();

	$collect = '<div class="tagList">';
	$collect .= $content;
	$collect .= '<ul>';
	foreach ($tags as $tag){
		$collect .= "<li>";
		$collect .= '<a href="' . $GLOBALS['urlBase'] . '/' . $tagPageUrl . '?tag=' . $tag['url'] . '">' . $tag['name'] . '</a>';
		$collect .= "</li>\n";
	}
	$collect .= "</ul>\n";
	$collect .= "</div>\n";

	return $collect;	


}



function categoryList($vars) {

	$content = $vars['%content%'];
	$categoryPageUrl = $vars['categoryPageUrl'] ? $vars['categoryPageUrl'] : "category.html";
	$parentCategoryId = "";
	$taxonomy = new Taxonomy('');
	$categories = $taxonomy->get_categoryList();

	if ($vars['parentCategoryUrl']) {
		foreach ($categories as $categoryId => $category) {
			if ($category['url'] == $vars['parentCategoryUrl']) {
				$parentCategoryId = $categoryId;
			}
		}
	}

	$collect = '<div class="categoryList">';
	$collect .= $content;
	$collect .= '<ul class="categoryParent">';

	$collect .= printCategory($categories,$parentCategoryId,0,$categoryPageUrl);

	$collect .= $GLOBALS['collectList'];
	$collect .=  "</ul>\n";
	$collect .= "</div>\n";

	return $collect;	

}
// function for categoryList() because functions inside functions cause error in All Page Publish
function printCategory($array,$parentId,$curLevel,$categoryPageUrl) {
	$collectList;
	$curLevel++;
	$listCount = 0;

	foreach ($array as $key => $row) {
		if ($row['parentId'] == $parentId) {
			$listCount++;
		}
	}

	$count = 0;
	foreach ($array as $key => $row) {
		if ($row['parentId'] == $parentId) {
			$count++;
			if ($curLevel > 1 && $count == 1) {
				$collectList .= '<ul class="categoryChildren">';
			}
			$collectList .= "<li>";
		    $collectList .= '<a href="' . $GLOBALS['urlBase'] . '/' . $categoryPageUrl . '?category=' . $row['url'] . '">' . $row['name'] . '</a>';
		    $collectList .= "</li>\n";
			$collectList .= printCategory($array,$key,$curLevel,$categoryPageUrl);
			if ($curLevel > 1 && $count == $listCount) {
				$collectList .=  "</ul>\n";
			}
		}
	}
	return $collectList;

}


function dbToCTAs($vars) {

	if ($vars['databaseID']) {

		$db = new Database($vars['databaseID']);

		$entries = $db->get_entriesSorted();

		$collect = '<div class="databaseCTAs ' . $vars['class'] . '">';
		foreach ($entries as $entry){
			$collect .= '<div class="databaseCTA">';
			if ($entry[$vars['imageUrlVar']]) {
				$imageVars = [];
				$imageVars['imageURL'] = $entry[$vars['imageUrlVar']];
				$imageVars['altText'] = $entry[$vars['imageAltTextVar']];
				$collect .= ampImage($imageVars);
			}
			if ($entry[$vars['titleVar']]) {
				$collect .= '<h3>' . $entry[$vars['titleVar']] . "</h3>\n";
			}
			if ($entry[$vars['descriptionVar']]) {
				$collect .= '<p>' . $entry[$vars['descriptionVar']] . "</p>\n";
			}
			if ($vars['databasePageUrl'] && $entry['url'] && $vars['linkText']) {
				$collect .= '<a class="button" href="' . $GLOBALS['urlBase'] . '/' . $vars['databasePageUrl'] . '-' . $entry['url'] . '.html">' . $vars['linkText'] . "</a>\n";
			}
			$collect .= "</div>\n";
		}
		$collect .= "</div>\n";

		return $collect;
	}

}


function dbToList($vars) {

	if ($vars['databaseID']) {

		$menuDB = new Database($vars['databaseID']);

		$items = $menuDB->get_entriesSorted();

		$collect = '<ul class="' . $vars['class'] . '">';
		foreach ($items as $item){
			$collect .= "<li>";
			$collect .= '<a href="' . $GLOBALS['urlBase'] . '/' . $item['link'] . '">' . $item['text'] . '</a>';
			$collect .= "</li>";
		}
		$collect .= "</ul>";

		return $collect;
	}

}


function globalHeader($vars) {

	$dbToListVars = [];
	$dbToListVars['class'] = "nav-list";
	$dbToListVars['databaseID'] = $vars['menuDatabaseID'];
	$menuList = dbToList($dbToListVars);

	$collect = '
		<header>
			<div class="header-inner">
				<div class="header-top">
					<div class="header-top-left">
				  		<div class="hamburger_wrapper">
				    		<div id="hamburger" tabindex="1" role="button" on="tap:hamburger.toggleClass(class=' . "'" . 'close' . "'" . '),nav-menu.toggleClass(class=' . "'" . 'now-active' . "'" . ')">
				      			<span></span>
				      			<span></span>
				      			<span></span>
				      		</div>
				   		</div>
					</div>
					<div class="header-top-center">
						<div class="logo"><a href="' . $GLOBALS['urlBase'] . '">' . $GLOBALS['logoSVG'] . '</a></div>
					</div>
					<div class="header-top-right">
						<div id="search" tabindex="1" role="button" on="tap:search.toggleClass(class=' . "'" . 'close' . "'" . '),nav-search.toggleClass(class=' . "'" . 'now-active' . "'" . ')">
							<div class="header-search-icon">' . $GLOBALS['searchSVG'] . '</div>
							<div class="header-close-icon">' . $GLOBALS['closeSVG'] . '</div>
						</div>
					</div>
				</div>
				<div id="nav-menu">
					' . $menuList . '
				</div>
				<div id="nav-search">
					<form class="header-search-form" method="GET" action="' . $GLOBALS['urlBase'] . '/search.html" target="_top">
  						<input type="search" placeholder="Keyword..." name="search">
  						<input type="submit" value="Search">
					</form>
				</div>
			</div>
		</header>
	';

	return $collect;

}

function globalFooter($vars) {
	$collect = "";
	$collect .= '
		<footer>
			<div class="footer-inner">
				<p class="text-center">Copyright ' . date("Y") . '. All rights reserved.</p>
			</div>
		</footer>
	';

	return $collect;

}

function fullWidthTemplate($vars) {
	$content = $vars['%content%'];
	$headerVars = [];
	$headerVars['menuDatabaseID'] = $vars['menuDatabaseID'];

	$collect = '
	<div class="fullWidth-template wrapper">
		' . globalHeader($headerVars) . '
		<div class="content">
			' . $content . "
		</div>\n
		" . globalFooter($vars) . "
	</div>\n
	";
	return $collect;
}

function sidebarTemplate($vars) {
	$content = $vars['%content%'];
	$headerVars = [];
	$headerVars['menuDatabaseID'] = $vars['menuDatabaseID'];

	$collect = '
	<div class="sidebar-template wrapper">
		' . globalHeader($headerVars) . '
		<div class="content">
			<div class="main">
				' . $content . '
			</div>
			<div class="sidebar">
	';
	if (!isset($vars['share']) || $vars['share'] != "off") {
		$collect .= '
				<div class="sidebar-section">
					<h2>Share</h2>
					' . ampSocialShare('') . '
				</div>
		';
	}
	if (!isset($vars['categories']) || $vars['categories'] != "off") {
		$categoryVars = [];
		$categoryVars['%content%'] = "";
		$categoryVars['categoryPageUrl'] = "categories.html";
		$categoryVars['parentCategoryUrl'] = "";
		$collect .= '
				<div class="sidebar-section">
					<h2>Categories</h2>
					' . categoryList($categoryVars) . '
				</div>
		';
	}
	if (!isset($vars['tags']) || $vars['tags'] != "off") {
		$tagVars = [];
		$tagVars['%content%'] = "";
		$tagVars['tagPageUrl'] = "tags.html";
		$collect .= '
				<div class="sidebar-section">
					<h2>Tags</h2>
					' . tagList($tagVars) . '
				</div>
		';
	}
	$collect .= "
			</div>\n
		</div>\n
		" . globalFooter($vars) . "
	</div>\n
	";

	return $collect;
}

?>