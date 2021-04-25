 <?php

class Page {
  public $id;
  public $name;
  public $type;
  public $file;
  public $database;
  public $ampDisabled;
  public $elements;
  public $elementNum;
  public $metaTags;
  public $url;
  public $seoTitle;
  public $seoDescription;
  public $pubDate;
  public $modDate;
  public $jsonLd;
  public $css;
  public $js;
  public $includes;
  public $customFields;

  public function __construct($pageID) {
    
    $result = getTableRow("pages","page_array",$pageID);

    if ($result['status']) {
      $fileName = $result['data']['file'];
      $this->url = $result['data']['url'];

      $result2 = getFileObj($fileName);
      if ($result2['status']) {
        $obj = $result2['data'];
        $this->id = $pageID;
        $this->name = $obj['name'];
        $this->type = $obj['type'];
        $this->file = $fileName;
        $this->database = $obj['database'];
        $this->ampDisabled = $obj['ampDisabled'];
        $this->elements = $obj['elements'];
        $this->elementNum = count($obj['elements']);
        $this->metaTags = $obj['metaTags'];
        $this->seoTitle = $obj['seoTitle'];
        $this->seoDescription = $obj['seoDescription'];
        $this->pubDate = $obj['pubDate'];
        $this->modDate = $obj['modDate'];
        $this->jsonLd = $obj['jsonLd'];
        $this->css = $obj['css'];
        $this->js = $obj['js'];
        $this->includes = $obj['includes'];
        $this->customFields = $obj['customFields'];
        $this->publishedElements = $obj['publishedElements'];
      }
    }
  }

  function get_id() {
    return $this->id;
  }
  function get_name() {
    return $this->name;
  }
  function get_type() {
    return $this->type;
  }
  function get_file() {
    return $this->file;
  }
  function get_database() {
    return $this->database;
  }
  function get_ampDisabled() {
    return $this->ampDisabled;
  }
  function get_elements() {
    return $this->elements;
  }
  function get_elementNum() {
    return $this->elementNum;
  }
  function get_metaTags() {
    return $this->metaTags;
  }
  function get_url() {
    return $this->url;
  }
  function get_seoTitle() {
    return $this->seoTitle;
  }
  function get_seoDescription() {
    return $this->seoDescription;
  }
  function get_pubDate() {
    return $this->pubDate;
  }
  function set_modDate() {
    $result = setProperty($this->file,'modDate',date('c',time()));
    if ($result['status']) {
      return true;
    } else {
      return false;
    }
  }
  function get_modDate() {
    return $this->modDate;
  }
  function get_jsonLd() {
    return $this->jsonLd;
  }
  function get_css() {
    return $this->css;
  }
  function get_js() {
    return $this->js;
  }
  function get_includes() {
    return $this->includes;
  }
  function get_customFields() {
    return $this->customFields;
  }
  function get_publishedElements() {
    return $this->publishedElements;
  }
  function push_elements() {
    return $this->customFields;
  }
  function publish_elements() {
    $elements = $this->elements;
    $result = setProperty($this->file,'publishedElements',$this->elements);
    if ($result['status']) {
      return true;
    } else {
      return false;
    }
  }
  function revert_elements() {
    $elements = $this->elements;
    $result = setProperty($this->file,'elements',$this->publishedElements);
    if ($result['status']) {
      return true;
    } else {
      return false;
    }
  }

}

//Example:
//$page = new Page("5eb4e831f161c");
//echo $page->get_id() . "<br>\n";
//echo $page->get_title() . "<br>\n";
//echo $page->get_name() . "<br>\n";
//echo $page->get_status() . "<br>\n";
//echo $page->get_pubDate() . "<br>\n";
//echo $page->get_type() . "<br>\n";


class Module {
  public $id;
  public $name;
  public $file;
  public $elements;
  public $elementNum;
  public $type;
  public $functionName;
  public $variableArray;
  public $description;

  public function __construct($moduleID) {
    
    $result = getTableRow("modules","module_array",$moduleID);
    if ($result['status']) {
      $name = $result['data']['name'];
      $fileName = $result['data']['file'];
      $type = $result['data']['type'];
      $variableArray = $result['data']['variable_array'];

      $result2 = getFileObj($fileName);
      if ($result2['status']) {
        $obj = $result2['data'];
        $this->id = $moduleID;
        $this->name = $name;
        $this->file = $fileName;
        $this->elements = $obj['elements'];
        if ($obj['elements']) {
          $this->elementNum = count($obj['elements']);
        }
        $this->type = $type;
        $this->description = $obj['description'];
        $this->functionName = $obj['functionName'];
        $this->variableArray = $variableArray;
      }
    }
  }

  function get_id() {
    return $this->id;
  }
  function get_name() {
    return $this->name;
  }
  function get_file() {
    return $this->file;
  }
  function get_elements() {
    return $this->elements;
  }
  function get_elementNum() {
    return $this->elementNum;
  }
  function get_functionName() {
    return $this->functionName;
  }
  function get_type() {
    return $this->type;
  }
  function get_description() {
    return $this->description;
  }
  function get_variableArray() {
    return $this->variableArray;
  }

}

class ModuleName {
  public $name;

  public function __construct($moduleID) {
    
    $result = getTableRow("modules","module_array",$moduleID);
    if ($result['status']) {
      $this->name = $result['data']['name'];
    }
  }

  function get_name() {
    return $this->name;
  }

}


class Database {
  public $id;
  public $name;
  public $file;
  public $fields;
  public $sortBy;
  public $entries;
  public $entriesSorted;
  public $entriesNum;

  public function __construct($databaseId) {
    
    $result = getTableRow("databases","database_array",$databaseId);
    if ($result['status']) {
      $name = $result['data']['name'];
      $fileName = $result['data']['file'];

      $result2 = getFileObj($fileName);
      if ($result2['status']) {
        $obj = $result2['data'];
        $this->id = $databaseId;
        $this->name = $name;
        $this->file = $fileName;
        $this->fields = $obj['field_array'];
        $this->sortBy = $obj['sortBy'];
        $this->entries = $obj['entry_array'];
        if ( $obj['sortBy'] ) {
          $entryArray = $obj['entry_array'];
          $sortBy = $obj['field_array'][$obj['sortBy']]['name'];
          $entryArray = sortMultiArray($entryArray,$sortBy);
          $this->entriesSorted = $entryArray;
        } else {
          $this->entriesSorted = $obj['entry_array'];
        }
        if ($obj['entries']) {
          $this->entrieNum = count($obj['entries']);
        }
      }
    }
  }

  function get_id() {
    return $this->id;
  }
  function get_name() {
    return $this->name;
  }
  function get_file() {
    return $this->file;
  }
  function get_fields() {
    return $this->fields;
  }
  function get_sortBy() {
    return $this->sortBy;
  }
  function get_entries() {
    return $this->entries;
  }
  function get_entriesSorted() {
    return $this->entriesSorted;
  }
  function get_entriesNum() {
    return $this->entriesNum;
  }

}


class DatabaseList {
  public $list;

  public function __construct() {    
    $result = getTableRows("databases","database_array");
    if ($result['status']) {
      $this->list = $result['data'];
    }
  }

  function get_list() {
    return $this->list;
  }

}


class Taxonomy {
  public $categoryList;
  public $tagList;
  public $curCategoryList;
  public $curTagList;
  public $curCategoryLookup;
  public $curTagLookup;

  public function __construct($pageId) {
    
    $result = getFileObj("taxonomy");
    if ($result['status']) {
      $obj = $result['data'];
      $this->categoryList = sortMultiArray($obj['category_array'],'name');
      $this->tagList = sortMultiArray($obj['tag_array'],'name');
      $this->categoryLookup = $obj['category_lookup_array'];
      $this->tagLookup = $obj['tag_lookup_array'];

      if ($pageId) {
        $this->curCategoryList = [];
        foreach ($obj['category_lookup_array'] as $categoryLookupKey => $categoryLookup) {
          if ($categoryLookup['pageId'] == $pageId) {
            $categoryId = $categoryLookup['categoryId'];
            $curCategory = $obj['category_array'][$categoryId];
            $curCategory['lookupId'] = $categoryLookupKey;
            $this->curCategoryList[$categoryId] = $curCategory;
          }
        }
        $this->curCategoryList = sortMultiArray($this->curCategoryList,'name');

        $this->curTagList = [];
        foreach ($obj['tag_lookup_array'] as $tagLookupKey => $tagLookup) {
          if ($tagLookup['pageId'] == $pageId) {
            $tagId = $tagLookup['tagId'];
            $curTag = $obj['tag_array'][$tagId];
            $curTag['lookupId'] = $tagLookupKey;
            $this->curTagList[$tagId] = $curTag;
          }
        }
        $this->curTagList = sortMultiArray($this->curTagList,'name');
      }
    }

  }

  function get_categoryList() {
    return $this->categoryList;
  }
  function get_tagList() {
    return $this->tagList;
  }
  function get_categoryLookup() {
    return $this->categoryLookup;
  }
  function get_tagLookup() {
    return $this->tagLookup;
  }
  function get_curCategoryList() {
    return $this->curCategoryList;
  }
  function get_curTagList() {
    return $this->curTagList;
  }

}

class Settings {
  public $urlBase;
  public $globalCSS;
  public $globalIncludes;
  
  public function __construct() {    
    $result = getFileObj("settings");
    if ($result['status']) {
      $obj = $result['data'];
      $this->urlBase = $obj['urlBase'];
      $this->globalCSS = $obj['globalCSS'];
      $this->globalIncludes = $obj['globalIncludes'];
    }
  }

  function get_urlBase() {
    return $this->urlBase;
  }

  function get_globalCSS() {
    return $this->globalCSS;
  }

  function get_globalIncludes() {
    return $this->globalIncludes;
  }

}

 
?> 


