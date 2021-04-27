

// basic form function
$(".basicForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_general.php', $(this).serialize(), function(data, status){
		//check for error
		if (data.indexOf("Error:") >= 0) {
			var formElement = $(e.target);
			formElement.find(".error").text(data);
		} else {
			//refresh page
			location.reload(); 
		}
	});
});

function addPageListeners() {

	$(".deletePage").click( function() {
	    var pageId = $(this).data("id");
	    var pageName = $(this).data("name");
	    var r = confirm("Are you sure you want to delete " + pageName + "?");
	    if (r == true) {
			$.get('./functions_general.php?function=deletePage&pageID=' + pageId, function(data, status){
				initPageList();
			});
	    }
	});

	$(".publishPage").click( function() {
	    var pageId = $(this).data("id");
	    var statusSpan = $(this).parent().find(".status");
		$.get('./functions_preview_publish.php?function=publishPage&pageId=' + pageId, function(data, status){
			var message = '<span class="success">Published</span>';
			statusSpan.hide().html(message).fadeIn();
		});
	});

	$(".copyPage").click( function() {
	    var pageFile = $(this).data("file");
	    var statusSpan = $(this).parent().find(".status");

	    $("#dialog").dialog({
	      	modal: true,
	      	resizable: true
	    });
	    var collect = '<h3 class="mt0">Copy Page</h3>';
	    collect += '<form id="copyPageForm">';
	    collect += '<label for="newPageName">New Page Name</label>';
	    collect += '<input id="newPageName" type="text" name="pageName" required />';
	    collect += '<input type="hidden" name="pageFile" value="' + pageFile + '" />';
	    collect += '<input type="hidden" name="function" value="copyPage" /><br><br>';
	    collect += '<input type="submit" name="submit" value="Copy" />';
	    collect += '<p class="error"></p>';
	    collect += '</form>';
	    $("#dialog").html(collect);
	    $("#copyPageForm").submit( function(e) {
			e.preventDefault();
			$.post('./functions_general.php', $(this).serialize(), function(data, status){
				//check for error
				if (data.indexOf("Error:") >= 0) {
					var formElement = $(e.target);
					formElement.find(".error").text(data);
				} else {
					//refresh page
					location.reload(); 
				}
			});
	    });
	});

}

function addModuleListeners() {

	$(".deleteModule").click( function() {
	    var moduleId = $(this).data("id");
	    var moduleName = $(this).data("name");
	    var r = confirm("Are you sure you want to delete " + moduleName + "?");
	    if (r == true) {
			$.get('./functions_general.php?function=deleteModule&moduleID=' + moduleId, function(data, status){
				initModuleList();
			});
	    }
	});
}

function addDatabaseListeners() {
	$(".deleteDatabase").click( function() {
	    var databaseId = $(this).data("id");
	    var databaseName = $(this).data("name");
	    var r = confirm("Are you sure you want to delete " + databaseName + "?");
	    if (r == true) {
			$.get('./functions_general.php?function=deleteDatabase&databaseID=' + databaseId, function(data, status){
				initDatabaseList();
			});
	    }
	});
}

function addCategoryListeners() {

	var childIdArray = [];
	function getParents(list,parentId) {
		for (var item in list) {
			if (list[item]['parentId'] == parentId) {
				childIdArray.push(item);
				getParents(list,item);
			}
		}
	}

	$(".editCategory").click( function() {
		$("#newCategoryFormWrapper").hide();
	    var categoryId = $(this).data("id");
	    var categoryArray = categoryList[categoryId];
	    var wrapper = $("#categoryEditWrapper");
		var collect = '<h3>Edit Category</h3>';
		collect += '<form method="POST" id="categoryEditForm">';
		collect += '<div class="formElement">';
		collect += '<label for="categoryName">Name</label>';
		collect += '<input id="categoryName" type="text" name="name" value="' + categoryArray['name'] + '" required>';
		collect += '</div>';
		collect += '<div class="formElement">';
		collect += '<label for="categoryUrl">URL</label>';
		collect += '<input id="categoryUrl" type="text" name="url" value="' + categoryArray['url'] + '" class="noSpacesAllowe" required>';
		collect += '</div>';
		collect += '<div class="formElement">';
		collect += '<label for="categoryParent">Parent</label>';
		collect += '<select id="categoryParent" name="parentId">';
		collect += '<option value="">None</option>';
		getParents(categoryList,categoryId);
		for (var category in categoryList) {
			if (category !== categoryId && childIdArray.indexOf(category) < 0) {
				collect += '<option value="' + category + '" '
				if (category === categoryArray['parentId']) { 
					collect += 'selected="selected" ';
				}
				collect += '>' + categoryList[category]['name'] + '</option>';
			}
		}
		collect += '</select>';
		collect += '</div>';
		collect += '<input type="hidden" name="function" value="updateCategory">';
		collect += '<input type="hidden" name="id" value="' + categoryId + '">';
		collect += '<input type="submit" value="Update"> ';
		collect += '<a id="cancelCategoryEdit" class="button bgRed" href="javascript:;">Cancel</a>';
		collect += '<div class="error"></div>';
		collect += '</form>';
		wrapper.html(collect);
		wrapper.fadeIn();	

		var form = $("#categoryEditForm");
		form.submit( function(e) {
			e.preventDefault();
			$.post('./functions_general.php', form.serialize(), function(data, status){
				//check for error
				if (data.indexOf("Error:") >= 0) {
					var formElement = $(e.target);
					formElement.find(".error").text(data);
				} else {
					initCategoryList();
					$("#categoryEditWrapper").hide().html("");
					$("#newCategoryFormWrapper").fadeIn();
				}
			});
		});
		$("#cancelCategoryEdit").click( function() {
			wrapper.hide();
			$("#newCategoryFormWrapper").fadeIn();
		});
	});	

	$(".deleteCategory").click( function() {
	    var categoryId = $(this).data("id");
	    var categoryName = $(this).data("name");
	    var r = confirm("Are you sure you want to delete " + categoryName + "?");
	    if (r == true) {
			$.get('./functions_general.php?function=deleteCategory&categoryId=' + categoryId, function(data, status){
				initCategoryList();
			});
	    }
	});
}

function addTagListeners() {

	$(".editTag").click( function() {
		$("#newTagFormWrapper").hide();
	    var tagId = $(this).data("id");
	    var tagArray = tagList[tagId];
	    var wrapper = $("#tagEditWrapper");
		var collect = '<h3>Edit Tag</h3>';
		collect += '<form method="POST" id="tagEditForm">';
		collect += '<div class="formElement">';
		collect += '<label for="tagName">Name</label>';
		collect += '<input id="tagName" type="text" name="name" value="' + tagArray['name'] + '" required>';
		collect += '</div>';
		collect += '<div class="formElement">';
		collect += '<label for="tagUrl">Name</label>';
		collect += '<input id="tagUrl" type="text" name="url" value="' + tagArray['url'] + '" class="noSpacesAllowe" required>';
		collect += '</div>';
		collect += '<input type="hidden" name="function" value="updateTag">';
		collect += '<input type="hidden" name="id" value="' + tagId + '">';
		collect += '<input type="submit" value="Update"> ';
		collect += '<a id="cancelTagEdit" class="button bgRed" href="javascript:;">Cancel</a>';
		collect += '<div class="error"></div>';
		collect += '</form>';
		wrapper.html(collect);
		wrapper.fadeIn();	

		var form = $("#tagEditForm");
		form.submit( function(e) {
			e.preventDefault();
			$.post('./functions_general.php', form.serialize(), function(data, status){
				//check for error
				if (data.indexOf("Error:") >= 0) {
					var formElement = $(e.target);
					formElement.find(".error").text(data);
				} else {
					initCategoryList();
					$("#tagEditWrapper").hide().html("");
					$("#newTagFormWrapper").fadeIn();
				}
			});
		});
		$("#cancelTagEdit").click( function() {
			wrapper.hide();
			$("#newTagFormWrapper").fadeIn();
		});
	});	 

	$(".deleteTag").click( function() {
	    var tagId = $(this).data("id");
	    var tagName = $(this).data("name");
	    var r = confirm("Are you sure you want to delete " + tagName + "?");
	    if (r == true) {
			$.get('./functions_general.php?function=deleteTag&tagId=' + tagId, function(data, status){
				initTagList();
			});
	    }
	});   

}



function initPageList(filterType,sortBy) {
	var pageList = $("#pageList");
	var url = "./functions_general.php?function=printPageList";

	if(filterType || sortBy) {
		url = "./functions_general.php?function=printPageList&filterType=" + filterType + "&sortBy=" + sortBy;
	}

	$.get(url, function(data, status){
	    pageList.html(data);
	    addPageListeners();
	});

}

initPageList();


function initModuleList(filterType,sortBy) {
	var moduleList = $("#moduleList");
	var url = "./functions_general.php?function=printModuleList";

	if(filterType || sortBy) {
		url = "./functions_general.php?function=printModuleList&filterType=" + filterType + "&sortBy=" + sortBy;
	}

	$.get(url, function(data, status){
	    moduleList.html(data);
	    addModuleListeners();
	});

}

initModuleList();


function initDatabaseList(sortBy) {
	var databaseList = $("#databaseList");
	var url = "./functions_general.php?function=printDatabaseList";

	if(sortBy) {
		url = "./functions_general.php?function=printDatabaseList&sortBy=" + sortBy;
	}

	$.get(url, function(data, status){
	    databaseList.html(data);
	    addDatabaseListeners();
	});

}

initDatabaseList();


function initCategoryList() {
	var categoryList = $("#categoryList");

	$.get("./functions_general.php?function=printCategoryList", function(data, status){
	    categoryList.html(data);
	    addCategoryListeners();
	});

}

initCategoryList();


function initTagList() {
	var tagList = $("#tagList");

	$.get("./functions_general.php?function=printTagList", function(data, status){
	    tagList.html(data);
	    addTagListeners();
	});

}

initTagList();


// make a new thing forms

$("#newPageForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_general.php', $(this).serialize(), function(data, status){
		initPageList();
		$("#newPageForm")[0].reset();
	});
});

$("#newModuleForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_general.php', $(this).serialize(), function(data, status){
		initModuleList();
		$("#newModuleForm")[0].reset();
	});
});

$("#newDatabaseForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_general.php', $(this).serialize(), function(data, status){
		initDatabaseList();
		$("#newDatabaseForm")[0].reset();
	});
});

$("#newTagForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_general.php', $(this).serialize(), function(data, status){
		//check for error
		if (data.indexOf("Error:") >= 0) {
			var formElement = $(e.target);
			formElement.find(".error").text(data);
		} else {
			initTagList();
			$("#newTagForm")[0].reset();
			$("#newTagForm .error").html("");
		}
	});
});


// sort forms
$("#sortPageForm").submit( function(e) {
	e.preventDefault();
	var filterType = $('#pageFilterType').val();
	var sortBy = $('#pageSortBy').val();
	initPageList(filterType,sortBy);
});

$("#sortModuleForm").submit( function(e) {
	e.preventDefault();
	var filterType = $('#moduleFilterType').val();
	var sortBy = $('#moduleSortBy').val();
	initModuleList(filterType,sortBy);
});

$("#sortDatabaseForm").submit( function(e) {
	e.preventDefault();
	var sortBy = $('#databaseSortBy').val();
	initDatabaseList(sortBy);
});

// export listeners

$("#exportPublishAllPages").click( function() {
	var statusP = $(this).parent().find('.status');
	$.get('./functions_preview_publish.php?function=publishAllPages', function(data, status){
		var status = '<span class="error">Could not publish. Try again.</span>';
		if (data !== "") {
			status = '<span class="success">All Pages Published</span>';
		}
		statusP.hide().html(status).fadeIn();
	});
});

$("#exportTagJSON").click( function() {
	var url = "tag.json";
	var statusP = $(this).parent().find('.status');
	$.get('./functions_exports.php?function=exportTagJSON&url=' + url, function(data, status){
		var status = '<span class="error">Could not export. Try again.</span>';
		if (data !== "") {
			status = '<span class="success">Tag JSON Published <a href="../' + url + '" target="_blank">View</a></span>';
		}
		statusP.hide().html(status).fadeIn();
	});
});

$("#exportCategoryJSON").click( function() {
	var url = "category.json";
	var statusP = $(this).parent().find('.status');
	$.get('./functions_exports.php?function=exportCategoryJSON&url=' + url, function(data, status){
		var status = '<span class="error">Could not export. Try again.</span>';
		if (data !== "") {
			status = '<span class="success">Category JSON Published <a href="../' + url + '" target="_blank">View</a></span>';
		}
		statusP.hide().html(status).fadeIn();
	});
});

$("#exportPageJSON").click( function() {
	var url = "page.json";
	var statusP = $(this).parent().find('.status');
	$.get('./functions_exports.php?function=exportPageJSON&url=' + url, function(data, status){
		var status = '<span class="error">Could not export. Try again.</span>';
		if (data !== "") {
			status = '<span class="success">Page JSON Published <a href="../' + url + '" target="_blank">View</a></span>';
		}
		statusP.hide().html(status).fadeIn();
	});
});

$("#exportSiteMap").click( function() {
	var url = "sitemap.xml";
	var statusP = $(this).parent().find('.status');
	$.get('./functions_exports.php?function=exportSiteMap&url=' + url, function(data, status){
		var status = '<span class="error">Could not export. Try again.</span>';
		if (data !== "") {
			status = '<span class="success">Site Map XML Published <a href="../' + url + '" target="_blank">View</a></span>';
		}
		statusP.hide().html(status).fadeIn();
	});
});


// delete listeners

$(".deleteInclude").click( function() {
	var includeId = $(this).data("include-id");
	var confirmDialog = confirm("Are you sure you want to delete this include?");
	if (confirmDialog == true) {
		$.get('./functions_general.php?function=deleteGlobalInclude&id=' + includeId, function(data, status){
		    //refresh page
			location.reload(); 
		});
	}
});

