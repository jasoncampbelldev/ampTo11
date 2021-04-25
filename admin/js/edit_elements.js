

// Quill editor settings
var toolbarOptions = [
	[{ 'header': [1, 2, 3, 4, 5, 6, false] }],			// custom dropdown
  	['bold', 'italic', 'underline', 'strike'],        	// toggled buttons
  	['link','blockquote', 'code-block'],
  	[{ 'list': 'ordered'}, { 'list': 'bullet' }],
  	[{ 'script': 'sub'}, { 'script': 'super' }],      	// superscript/subscript
  	[{ 'indent': '-1'}, { 'indent': '+1' }],          	// outdent/indent
  	[{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  	[{ 'font': [] }],
  	[{ 'align': [] }],
  	['clean']                                         // remove formatting button
];


var elementTypes = [
	"body",
	"a",
	"article",
	"br",
	"code",
	"div",
	"footer",
	"h1",
	"h2",
	"h3",
	"h4",
	"h5",
	"h6",
	"header",
	"hr",
	"img",
	"li",
	"nav",
	"p",
	"section",
	"span",
	"strong",
	"table",
	"tbody",
	"td",
	"th",
	"thead",
	"tr",
	"ul"
];

var editStorage = "curEdit" + editId;
localStorage[editStorage] = '';

var isModule = false;
if (typeof moduleInfo !== 'undefined') {
	isModule = true;
}

var allowElementMoves = true;

// Drag and drop events
function allowDrop(ev) {
  	ev.preventDefault();
  	$(ev.target).addClass('active');
}

function dropExit(ev) {
  	ev.preventDefault();
  	$(ev.target).removeClass('active');	
}

function drag(ev) {
  	var elementId = $(ev.target).data('element-id');
  	ev.dataTransfer.setData("elementId", elementId);
}

function stopDrag(ev) {
    ev.preventDefault();
    ev.stopPropagation();
}

function drop(ev) {
  	ev.preventDefault();
  	if (allowElementMoves) {
  		allowElementMoves = false;
	  	var elementId = ev.dataTransfer.getData("elementId");
	  	var newParentId = $(ev.target).data('parent-id');
	  	var newOrderNum = $(ev.target).data('order');
		console.log(elementId + " - " + newParentId + " - " + newOrderNum );
		var url = "./functions_elements.php?id=" + editId + "&function=dragDropElement&elementId=" + elementId + "&newOrderNum=" + newOrderNum + "&newParentId=" + newParentId + "&containerFile=" + fileName;
		$.get(url, function(data, status){
			initElements();
			allowElementMoves = true;
		});
	}
}

// Element Edit Dialog

var editDialog = $("#elementEditDialog");

function openEditDialog(html,elementId) {

	var windowWidth = $(window).width();
	var dialogWidth = windowWidth * 1; //this will make the dialog 100% of the window width
	var leftOffset = 0;
	if (windowWidth > 600) {
		dialogWidth = windowWidth * 0.5; //this will make the dialog 50% of the window width
		leftOffset = 20;
	}

	var element = $( "#element" + elementId );
	var elementPositionLeft = element.offset().left;
	var elementBarHeight = element.find(".elementInfo:first-child").height();

    editDialog.dialog({
      	modal: false,
      	width: dialogWidth,
      	resizable: true,
      	classes: { "ui-dialog": "elementEditDialogWrapper" },
      	position: { 
      		my: "left top", 
      		at: "left-" + (elementPositionLeft - leftOffset) + " top+" + (elementBarHeight + 10), 
      		of: "#element" + elementId
      	},
      	close: function( event, ui ) { closeEditDialog(); }

    });

    editDialog.html(html);
}

function closeEditDialog() {
	if (editDialog.dialog( "instance" )) {
		editDialog.dialog( "close" );
	}
	editDialog.html("");
	$("#elementWrapper span a").data("toggle","closed");
	localStorage[editStorage] = '';
}

// Listeners

function addElementListeners() {

	$(".addChildElement").click( function () {
		var toggle = $(this).data("toggle");
		var elementId = $(this).data("element-id");
		var elementChildNum = pageElementChildNums[elementId];
		var formId = 'addChildElementForm' + elementId;
		var elementType;
		if (toggle == "closed") {
			closeEditDialog();
			var collect = '<form method="POST" id="' + formId + '">';
			collect += '<div class="formElement"><label for="orderPosition">Position</label>';
			collect += '<select name="orderPosition" id="orderPosition"><option value="top">Top</option><option value="bottom">Bottom</option></select>';
			collect += '</div>';
			collect += '<div id="elementTypeWrapper" class="formElement"><label for="elementType">Type</label>';
			collect += '<select class="elementType" id="elementType" name="type">';
			collect += '<option value="html" selected="selected">HTML</option>';
			collect += '<option value="module">Module</option>';
			collect += '</select>';
			collect += '</div>';
			collect += '<div id="elementNameWrapper" class="formElement"><label for="elementName">Tag Name</label>';
			collect += '<input class="elementName" id="elementName" type="text" name="name" placeholder="name">';
			collect += '</div>';
			collect += '<div id="elementEditorWrapper" class="formElement"><label for="elementEditor">Editor</label>';
			collect += '<select id="elementEditor" name="editor">';
			collect += '<option value="textField">Text Field</option>';
			collect += '<option value="wysiwyg">WYSIWYG</option>';
			collect += '<option value="textArea">Text Area</option>';
			collect += '</select>';
			collect += '</div>';
			collect += '<div id="moduleIdWrapper" class="formElement"></div>';
			collect += '<input type="hidden" name="function" value="addElement">';
			collect += '<input type="hidden" name="containerFile" value="' + fileName + '">';
			collect += '<input type="hidden" name="parentId" value="' + elementId + '">';
			collect += '<input type="hidden" name="childNum" value="' + elementChildNum + '">';
			collect += '<br><input type="submit" value="Add">';
			collect += '</form>';
			openEditDialog(collect,elementId);
			$(".elementName").autocomplete({ source: elementTypes });	
			// don't allow modules in modules
			if (isModule) {
				$("#" + formId + " #elementTypeWrapper").addClass("hidden");
			}

			function onTypeChange() {
				if (elementType === "module") {
					$("#" + formId + " #elementNameWrapper").addClass("hidden");
					$("#" + formId + " #elementEditorWrapper").addClass("hidden");
					var collect = '<label for="moduleId">Module</label>';
					collect += '<select id="moduleId" class="moduleId" name="moduleId"></select>';
					collect += '<input type="hidden" id="moduleType" name="moduleType" />';
					$("#" + formId + " #moduleIdWrapper").html(collect);
					if (modules) {
						for (var moduleData in modules) {
							var printModule = true;
							if (isModule) {
								if (moduleData === moduleInfo['id']) {
									printModule = false;
								}
							}
							if (printModule) {
								$("#" + formId + " .moduleId").append('<option value="' + moduleData + '">' + modules[moduleData]['name'] + '</option>');
							}
						}					
					}
					$("#moduleType").val(modules[$("#moduleId").val()]["type"]);
					$("#moduleId").change( function() {
						$("#moduleType").val(modules[$("#moduleId").val()]["type"]);
					});
				} else {
					$("#" + formId + " #elementNameWrapper").removeClass("hidden");
					$("#" + formId + " #elementEditorWrapper").removeClass("hidden");
					$("#" + formId + " #moduleIdWrapper").html("");
				}
			}
			onTypeChange();
			$("#" + formId + " .elementType").on("change", function () { 
				elementType = $("#" + formId + " #elementType").val();
				onTypeChange();
			});

			var form = $('#' + formId);
			form.submit( function(e) {
				e.preventDefault();
				$('#' + formId + " input[type='submit']").prop( "disabled", true );
				$.post('./functions_elements.php', form.serialize(), function(data, status){
					closeEditDialog();
					initElements();
				}).fail(function() {
    				$('#' + formId + " input[type='submit']").prop( "disabled", false );
  				});
			});
			$(this).data("toggle","open");
		} else {
			closeEditDialog();
		}
	});

	$(".editElement").click( function () {
		var toggle = $(this).data("toggle");
		var elementId = $(this).data("element-id");
		var elementType = pageElements[elementId]['type'];
		var elementName = "";
		if (pageElements[elementId]['name']) { 
			elementName = pageElements[elementId]['name']; 
		}
		var elementEditor = pageElements[elementId]['editor'];
		var elementModuleId = "";
		if (pageElements[elementId]['moduleId']) {
			elementModuleId = pageElements[elementId]['moduleId'];
		}
		var elementContent = pageElements[elementId]['content'];
		var formId = 'editElementForm' + elementId;
		var editorId = 'richEditor' + elementId;
		var editor;
		if (toggle == "closed") {
			closeEditDialog();
			var collect = '<form method="POST" id="' + formId + '">';
			collect += '<div id="elementTypeWrapper" class="formElement"><label for="elementType">Type</label>';
			collect += '<select class="elementType" id="elementType" name="type">';
			collect += '<option value="html">HTML</option>';
			collect += '<option value="module">Module</option>';
			collect += '</select>';
			collect += '</div>';
			collect += '<div id="elementNameWrapper" class="formElement"><label for="elementName">Tag Name</label>';
			collect += '<input class="elementName" id="elementName" type="text" name="name" value="' + elementName + '">';
			collect += '</div>';
			collect += '<div id="elementEditorWrapper" class="formElement"><label for="elementEditor">Editor</label>';
			collect += '<select id="elementEditor" name="editor">';
			collect += '<option class="textField" value="textField">Text Field</option>';
			collect += '<option class="wysiwyg" value="wysiwyg">WYSIWYG</option>';
			collect += '<option class="textArea" value="textArea">Text Area</option>';
			collect += '</select>';
			collect += '</div>';
			collect += '<div id="contentWrapper"></div>';
			collect += '<input type="hidden" name="function" value="editElement">';
			collect += '<input type="hidden" name="elementId" value="' + elementId + '">';
			collect += '<input type="hidden" name="containerFile" value="' + fileName + '">';
			collect += '<br><input type="submit" value="Update">';
			collect += '</form>';
			openEditDialog(collect,elementId);
			$(".elementName").autocomplete({ source: elementTypes });
			// don't allow modules in modules
			if (isModule) {
				$("#" + formId + " #elementTypeWrapper").addClass("hidden");
			}
			// select current element type
			$("#" + formId + " #elementType option[value='" + elementType + "']").attr("selected","selected");

			function onTypeChange() {
				if (elementType === "module") {
					$("#" + formId + " #elementEditorWrapper").addClass("hidden");
					$("#" + formId + " #elementNameWrapper").addClass("hidden");
					var collect = '<div class="formElement moduleIdWrapper"><label for="moduleId">Module</label>';
					collect += '<select id="moduleId" class="moduleId" name="moduleId"></select>';
					collect += '<input type="hidden" id="moduleType" name="moduleType" />';
					collect += '</div>';
					$("#" + formId + " #contentWrapper").html(collect);
					if (modules) {
						for (var moduleData in modules) {
							var printModule = true;
							if (isModule) {
								if (moduleData === moduleInfo['id']) {
									printModule = false;
								}
							}
							if (printModule) {
								if (moduleData === elementModuleId) {
									$("#" + formId + " .moduleId").append('<option value="' + moduleData + '" selected>' + modules[moduleData]['name'] + '</option>');
								} else {
									$("#" + formId + " .moduleId").append('<option value="' + moduleData + '">' + modules[moduleData]['name'] + '</option>');								
								}	
							}			    	
						}					
					}
					$("#moduleType").val(modules[$("#moduleId").val()]["type"]);
					$("#moduleId").change( function() {
						$("#moduleType").val(modules[$("#moduleId").val()]["type"]);
					});
				} else {
					$("#" + formId + " #elementNameWrapper").removeClass("hidden");
					$("#" + formId + " #elementEditorWrapper").removeClass("hidden");
					initEditor();
				}
			}
			onTypeChange();
			$("#" + formId + " .elementType").on("change keyup blur input", function () { 
				elementType = $("#" + formId + " #elementType").val();
				onTypeChange();
			});

			function initEditor() {
				if (elementEditor != "") {
					$("#" + formId + " ." + elementEditor).attr("selected","selected");
				}
				if (elementEditor == "textField") {
					var collect = '<div class="formElement"><label for="content">Content</label>';
					collect += '<input class="content longInput" id="content" type="text" name="content" placeholder="content" value="">';
					collect += '</div>';
					$("#" + formId + " #contentWrapper").html(collect);
					$("#" + formId + " #content").val(elementContent);
				} else if (elementEditor == "textArea") {
					var collect = '<div class="formElement"><label for="content">Content</label>';
					collect += '<textarea class="content" id="content" name="content"></textarea>';
					collect += '</div>';
					$("#" + formId + " #contentWrapper").html(collect);
					$("#" + formId + " #content").val(elementContent);
				} else {
					// if editor is WYSIWYG
					var collect = '<div class="formElement"><label for="content">Content</label>';
					collect += '<input class="content" id="content" type="hidden" name="content" placeholder="content" value="">';
					collect += '<div id="' + editorId + 'wrapper"><div id="' + editorId + '"></div></div>';
					collect += '</div>';
					$("#" + formId + " #contentWrapper").html(collect);
					editor = new Quill("#" + editorId, {
		    			theme: 'snow',
		    			modules: {
		    				toolbar: toolbarOptions
		  				}
		  			});
		  			if(elementContent) {
		  				editor.root.innerHTML = elementContent;
		  			}
		  		}
	  		}
			$("#" + formId + " #elementEditor").change(function () { 
				elementEditor = $("#" + formId + " #elementEditor").val();
				initEditor();
			});

 			var form = $("#" + formId);
			form.submit( function(e) {
				e.preventDefault();
				if (elementEditor == "wysiwyg" && elementType !== 'module') {
					$('#' + formId + ' .content').val(editor.root.innerHTML);
				}
				$.post('./functions_elements.php', form.serialize(), function(data, status){
					closeEditDialog();
					initElements();
				});
			});

			$(this).data("toggle","open");
		} else {
			closeEditDialog();
		}
	});


	$(".moveElementUp").click( function () {
	  	if (allowElementMoves) {
	  		allowElementMoves = false;
			var elementId = $(this).data("element-id");
			var orderNum = pageElements[elementId]['orderNum'];
			var parentId = pageElements[elementId]['parentId'];
			var url = "./functions_elements.php?id=" + editId + "&function=moveElementUp&elementId=" + elementId + "&orderNum=" + (orderNum - 1) + "&parentId=" + parentId + "&containerFile=" + fileName;
			if (orderNum > 0) {
				$.get(url, function(data, status){
				    initElements();
				    allowElementMoves = true;
				});
			}
		}
	});

	$(".moveElementDown").click( function () {
	  	if (allowElementMoves) {
	  		allowElementMoves = false;
			var elementId = $(this).data("element-id");
			var orderNum = pageElements[elementId]['orderNum'];
			var parentId = pageElements[elementId]['parentId'];
			var parentChildNum = pageElementChildNums[parentId];
			var url = "./functions_elements.php?id=" + editId + "&function=moveElementDown&elementId=" + elementId + "&orderNum=" + (orderNum + 1) + "&parentId=" + parentId + "&containerFile=" + fileName;
			if (orderNum < parentChildNum) {
				$.get(url, function(data, status){
				    initElements();
				    allowElementMoves = true;
				});
			} 
		}
	});

	$(".deleteElement").click( function () {
	  	if (allowElementMoves) {
	  		allowElementMoves = false;
			var elementId = $(this).data("element-id");
			var orderNum = pageElements[elementId]['orderNum'];
			var parentId = pageElements[elementId]['parentId'];
			var confirmDialog = confirm("Are you sure you want to delete this element?");
			if (confirmDialog == true) {
				var url = "./functions_elements.php?id=" + editId + "&function=deleteElement&elementId=" + elementId + "&orderNum=" + orderNum + "&parentId=" + parentId + "&containerFile=" + fileName;
				$.get(url, function(data, status){
			    	initElements();
			    	allowElementMoves = true;
				});
			}
		}
	});

	$(".editElementAttrs").click( function () {
		var toggle = $(this).data("toggle");
		var elementId = $(this).data("element-id");
		var divId = $("#element" + elementId);
		var formId = 'addElementAttrForm' + elementId;
		if (toggle == "closed") {
			closeEditDialog();
			var collect = "";
			var attrArray = pageElements[elementId]['attrs'];
			collect += '<form method="POST" id="' + formId + '">';
			collect += '<div class="formElement"><label for="attrName">Name</label>';
			collect += '<input id="attrName" type="text" name="name" placeholder="name">';
			collect += '</div>';
			collect += '<div class="formElement"><label for="attrValue">Value</label>';
			collect += '<input id="attrValue" type="text" name="value" placeholder="value">';
			collect += '</div>';
			collect += '<input type="hidden" name="function" value="addElementAttr">';
			collect += '<input type="hidden" name="elementId" value="' + elementId + '">';
			collect += '<input type="hidden" name="containerFile" value="' + fileName + '">';
			collect += '<br><input type="submit" value="Add">';
			collect += '</form>';
			if (attrArray) {
				collect += '<hr>';
			}
			collect += '<ul class="itemList">';
			for (var attr in attrArray) {
				collect += '<li><strong>' + attr + '</strong> = ' + attrArray[attr];
				collect += ' <a class="button editElementAttr" href="javascript:;" data-attr-name="' + attr + '" data-element-id="' + elementId + '">Edit</a>';
				collect += ' <a class="button deleteElementAttr" href="javascript:;" data-attr-name="' + attr + '" data-element-id="' + elementId + '">Delete</a>';
				collect += '</li>';
			}
			collect += '</ul>';
			openEditDialog(collect,elementId);	
			var form = $('#' + formId);
			form.submit( function(e) {
				e.preventDefault();
				$.post('./functions_elements.php', form.serialize(), function(data, status){
					initElements();
				});
			});
			localStorage[editStorage] = 'editElementAttrs,' + elementId;
			$(this).data("toggle","open");
		} else {
			closeEditDialog();
		}

		$(".editElementAttr").click( function () {
			var elementId = $(this).data("element-id");
			var attrName = $(this).data("attr-name").replace("attr","").toLowerCase();
			var divId = $("#element" + elementId);
			var attrValue = pageElements[elementId]['attrs'][attrName];
			var formId = 'editElementAttrForm' + attrName + elementId;
			collect = "";
			collect += '<form method="POST" id="' + formId + '">';
			collect += '<div class="formElement"><label for="attrName">Name</label>';
			collect += '<input id="attrName" type="text" name="nameFake" value="' + attrName + '" disabled>';
			collect += '</div>';
			collect += '<div class="formElement"><label for="' + elementId + 'attrEditValue">Value</label>';
			collect += '<input type="text" name="value" id="' + elementId + 'attrEditValue" value="">';
			collect += '</div>';
			collect += '<input type="hidden" name="name" value="' + attrName + '">';
			collect += '<input type="hidden" name="function" value="editElementAttr">';
			collect += '<input type="hidden" name="elementId" value="' + elementId + '">';
			collect += '<input type="hidden" name="containerFile" value="' + fileName + '">';
			collect += '<br><input type="submit" value="Save">';
			collect += '</form>';
			openEditDialog(collect,elementId);	
			$("#" + elementId + "attrEditValue").val(attrValue);
			var form = $('#' + formId);
			form.submit( function(e) {
				e.preventDefault();
				$.post('./functions_elements.php', form.serialize(), function(data, status){
					initElements();
				});
			});
		});

		$(".deleteElementAttr").click( function () {
			var elementId = $(this).data("element-id");
			var attrName = $(this).data("attr-name").replace("attr","").toLowerCase();
			var divId = $("#element" + elementId);
			var attrValue = $(divId).data("attr-" + attrName)
			var confirmDialog = confirm("Are you sure you want to delete this element attribute?");
			if (confirmDialog == true) {
				var url = "./functions_elements.php?id=" + editId + "&function=deleteElementAttr&elementId=" + elementId + "&name=" + attrName + "&containerFile=" + fileName;
				$.get(url, function(data, status){
			    	initElements();
				});
			}
		});

	});


	$(".editElementVars").click( function () {
		var toggle = $(this).data("toggle");
		var elementId = $(this).data("element-id");
		var divId = $("#element" + elementId);
		var formId = 'addElementVarsForm' + elementId;
		var elementModuleId = "";
		if (pageElements[elementId]['moduleId']) {
			elementModuleId = pageElements[elementId]['moduleId'];
		}
		if (toggle == "closed") {
			closeEditDialog();
			var collect = "";
			collect += '<form method="POST" id="' + formId + '">';
			if (modules[elementModuleId]) {
				var variables = modules[elementModuleId]['variable_array'];
				for (var variable in variables) {
					var varName = variables[variable]['name'];
					var varValue = "";
					console.log(pageElements[elementId]);
					if (pageElements[elementId]['vars']) {
						if (pageElements[elementId]['vars'][varName]) {
							varValue = pageElements[elementId]['vars'][varName];
						}
					}
					if (variables[variable]['editor'] === "image") {
						var imageFieldId = varName + elementId;
						collect += '<div class="formElement"><label for="' + imageFieldId + '">' + varName + '</label>';
						collect += '<input id="' + imageFieldId + '" type="text" name="' + varName + '" value="' + varValue + '" />';
						collect += '<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="' + imageFieldId +'"><img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images"/></a><br>';
						collect += '</div><br>';
					} else {
						collect += '<div class="formElement"><label for="' + varName + '">' + varName + '</label>';
						collect += '<input type="text" id="' + varName + '" name="' + varName + '" value="' + varValue + '" /><br>';
						collect += '</div><br>';
					}
				}
			}
			collect += '<input type="hidden" name="function" value="updateElementVars">';
			collect += '<input type="hidden" name="elementId" value="' + elementId + '">';
			collect += '<input type="hidden" name="containerFile" value="' + fileName + '">';
			collect += '<br><input type="submit" value="update">';
			collect += '</form>';
			openEditDialog(collect,elementId);	
			var form = $('#' + formId);
			form.submit( function(e) {
				e.preventDefault();
				$.post('./functions_elements.php', form.serialize(), function(data, status){
					closeEditDialog();
					initElements();
				});
			});
			$(this).data("toggle","open");
		} else {
			closeEditDialog();
		}

	});

	//  mobile toggle
	$( ".elementInfoIconsMobile" ).click( function() {
		var toggle = $(this).data("toggle");
		$(".elementInfoIconsInner" ).removeClass( "active");
		$(".elementInfoIconsMobile" ).data("toggle","closed");
		var elementId = $(this).data("element-id");
		var iconsDivId = $("#element" + elementId + " .elementInfoIconsInner").first();
		if (toggle == "closed") {
			iconsDivId.addClass( "active" );
			$(this).data("toggle","open");
		} else {
			$(this).data("toggle","closed");
		}
	});


	// open element editor if it's in local storage
	if (localStorage[editStorage]) {
		var storageArray = localStorage[editStorage].split(",");
		var elementClass = storageArray[0];
		var elementDataId = storageArray[1];
		$('.' + elementClass + '[data-element-id="' + elementDataId + '"]').click();
	}

}


// Init functions

function initElements() {
	var elementWrapper = $("#elementWrapper");

	$.get('./functions_elements.php?function=printElements&editType=' + editType + '&editId=' + editId, function(data, status){
	    elementWrapper.html(data);
	    addElementListeners();
	});

	$('#elementPreviewFrame').attr('src','./functions_preview_publish.php?function=printPreview&editType=' + editType + '&editId=' + editId + '&cacheBuster=' + new Date().getTime());
	$('.previewTab').attr('href','./functions_preview_publish.php?function=printPreview&editType=' + editType + '&editId=' + editId);

}

initElements();

function addDbEntryPreviewListeners() {
	$(".changeDbEntryPreview").click( function () {
		var entryId = $(this).data("entry-id");
		$('#elementPreviewFrame').attr('src','./functions_preview_publish.php?function=printPreview&editType=' + editType + '&editId=' + editId + '&dbEntry=' + entryId + '&cacheBuster=' + new Date().getTime());
		$('.previewTab').attr('href','./functions_preview_publish.php?function=printPreview&editType=' + editType + '&editId=' + editId+ '&dbEntry=' + entryId);
		$(".changeDbEntryPreview").removeClass("active");
		$(this).addClass("active");
	});
}

addDbEntryPreviewListeners();


