	

// set file name and edit Id based on type of edit page
var fileName;
var editId;
var editType;
if (typeof moduleInfo !== 'undefined') {
	fileName = moduleInfo.file;
	editId = moduleInfo.id;
	editType = "module";
} else if (typeof pageInfo !== 'undefined') {
	fileName = pageInfo.file;
	editId = pageInfo.id;
	editType = "page";
}


// basic form function
$(".basicForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_page_module.php', $(this).serialize(), function(data, status){
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

$("#ampDisableCheckbox").click( function() {
	var value = $(this).prop('checked');
	$.get('./functions_page_module.php?function=updateAmpDisable&fileName=' + pageInfo['file'] + '&value=' + value, function(data, status){
	    //refresh page
		location.reload(); 
	});
});

$(".publishPage").click( function() {
    var pageId = $(this).data("id");
    var pageUrl = $(this).data("url");
    var entryUrls = $(this).data("entry-urls");
    var statusP = $(this).parent().find('.status');
	$.get('./functions_preview_publish.php?function=publishPage&pageId=' + pageId, function(data, status){
		var status = '<span class="error">Could not publish. Try again.</span>';
		if (data !== "") {
			if (entryUrls) {
				status = '<span class="success">Pages Published: ';
				entryUrls = entryUrls.split(",");
				for (var entryUrl in entryUrls) {
					var entryUrlValue = entryUrls[entryUrl];
					status += '<a href="../' + pageUrl + '-' + entryUrlValue + '.html" target="_blank">' + entryUrlValue + '</a> ';
				}
			} else {
				status = '<span class="success">Page Published <a href="../' + pageUrl + '.html" target="_blank">View</a></span>';
			}
		}
		statusP.hide().html(status).fadeIn();
	});
});

$(".revertPageHtml").click( function() {
    var pageId = $(this).data("id");
	var confirmDialog = confirm("Are you sure you want to revert HTML elements from published version?");
	if (confirmDialog == true) {
		$.get('./functions_page_module.php?function=revertPageHtml&id=' + pageId, function(data, status){
			location.reload();
		});
	}
});

$(".deleteMetaTag").click( function() {
	var metaTagId = $(this).data("meta-tag-id");
	var confirmDialog = confirm("Are you sure you want to delete this meta tag?");
	if (confirmDialog == true) {
		$.get('./functions_page_module.php?function=deleteMetaTag&fileName=' + pageInfo['file'] + '&id=' + metaTagId, function(data, status){
		    //refresh page
			location.reload(); 
		});
	}
});

$(".deleteInclude").click( function() {
	var includeId = $(this).data("include-id");
	var confirmDialog = confirm("Are you sure you want to delete this include?");
	if (confirmDialog == true) {
		$.get('./functions_page_module.php?function=deleteInclude&fileName=' + pageInfo['file'] + '&id=' + includeId, function(data, status){
		    //refresh page
			location.reload(); 
		});
	}
});

$(".deletePageCategory").click( function() {
	var id = $(this).data("id");
	var name = $(this).data("name");
	var confirmDialog = confirm("Are you sure you want to delete the " + name + " category?");
	if (confirmDialog == true) {
		$.get('./functions_page_module.php?function=deletePageCategory&fileName=' + pageInfo['file'] + '&id=' + id, function(data, status){
		    //refresh page
			location.reload(); 
		});
	}
});

$(".deletePageTag").click( function() {
	var id = $(this).data("id");
	var name = $(this).data("name");
	var confirmDialog = confirm("Are you sure you want to delete the " + name + " tag?");
	if (confirmDialog == true) {
		$.get('./functions_page_module.php?function=deletePageTag&fileName=' + pageInfo['file'] + '&id=' + id, function(data, status){
		    //refresh page
			location.reload(); 
		});
	}
});

$(".deleteVariable").click( function() {
    var variableId = $(this).data("id");
    var variableName = $(this).data("name");
    var r = confirm("Are you sure you want to delete " + variableName + "?");
    if (r == true) {
		$.get('./functions_page_module.php?function=deleteModuleVariable&variableId=' + variableId + '&moduleId=' + editId, function(data, status){
		    //refresh page
			location.reload();
		});
    }
});


// add module JSON	
$.get("./functions_page_module.php?function=printModulesJSON", function(data, status){
	$('head').append(data);
});
