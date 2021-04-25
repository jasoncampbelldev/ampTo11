
var entryStorage = "curEntry" + databaseInfo['id'];

function changeEntry(entryId) {

	$(".entriesNav a").removeClass("active");
	$('[data-entry="' + entryId + '"]').addClass("active");

	$(".entryWrapper").removeClass("active");
	$(".entryWrapper#entry" + entryId).addClass("active");

    localStorage[entryStorage] = entryId;
    window.scrollTo(0,0);

}


// basic form function
$(".basicForm").submit( function(e) {
	e.preventDefault();
	$.post('./functions_database.php', $(this).serialize(), function(data, status){
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

$( ".richTextEditor" ).each(function( index ) {
	var editorId = $( this ).attr('id');
 	var editor = new Quill("#" + editorId, {
		theme: 'snow',
		modules: {
			toolbar: toolbarOptions
		}
	});
});
	
//$('#' + formId + ' .content').val(editor.root.innerHTML);

$("#newEntryForm").submit( function(e) {
	e.preventDefault();
	$(".richTextEditor").each(function( index ) {
  		var editorId = $( this ).attr('id');
  		$('#' + editorId + '-content').val( $( "#" + editorId + " .ql-editor").html() );
	});
	$.post('./functions_database.php', $("#newEntryForm").serialize(), function(data, status){
		if (data.indexOf("Error:") >= 0) {
			var formElement = $(e.target);
			formElement.find(".error").text(data);
		} else {
			//refresh page
			location.reload(); 
		}
	});
});


function addFieldListeners() {

	$(".deleteField").click( function() {
	    var fieldId = $(this).data("id");
	    var fieldName = $(this).data("name");
	    var databaseFile = databaseInfo.file;
	    console.log("js test");
	    var r = confirm("Are you sure you want to delete " + fieldName + "?");
	    if (r == true) {
			$.get('./functions_database.php?function=deleteDatabaseField&fieldId=' + fieldId + '&fieldName=' + fieldName + '&databaseFile=' + databaseFile, function(data, status){
				//refresh page
				location.reload(); 
			});
	    }
	});

}

function addEntryListeners() {

	$(".entriesNav a").click( function() {
		var entryId = $(this).data("entry");
		changeEntry(entryId)
	});

	if (localStorage[entryStorage]) {
		changeEntry(localStorage[entryStorage])
	}

	$(".editEntry").click( function() {
		window.scrollTo(0,0);
		$("#databaseWrapper").hide();
	    var entryId = $(this).data("id");
	    var entryFields = databaseEntries[entryId];
	    var wrapper = $("#entryEditWrapper");
		var collect = '<h3>Edit Entry</h3>';
		collect += '<form method="POST" id="entryEditForm">';
		function getFieldValue(fieldName) {
			for (var entryField in entryFields) {
				if (entryField.replace("_"," ") === fieldName.replace("_"," ")) {
					return entryFields[entryField];
				}
			}
			return "";
		}
		if (databaseFields) {
			for (var databaseField in databaseFields) {
				var fieldEditor = databaseFields[databaseField]['editor']; 
				var fieldName = databaseFields[databaseField]['name'];
				var fieldValue = getFieldValue(fieldName);
				if (fieldEditor === "text" ) {
					collect += '<div class="formElement">';
					collect += '<label for="' + fieldName  + '">' + fieldName.replace("_"," ")  + '</label>';
					collect += '<input type="text" id="' + fieldName  + '" name="' + fieldName  + '" value="' + fieldValue  + '" ';
					if (fieldName == 'url') {
						collect += 'class="noSpacesAllowed" required ';
					}
					collect += '/>';
					collect += '</div><br>';
				} else if (fieldEditor === "date") {
					collect += '<div class="formElement">';
					collect += '<label for="' + fieldName  + '">' + fieldName.replace("_"," ")  + '</label>';
					collect += '<input class="datePicker" type="text" id="' + fieldName  + '" name="' + fieldName  + '" value="' + fieldValue  + '"/>';
					collect += '</div><br>';
				} else if (fieldEditor === "text_area") {
					collect += '<div class="formElement">';
					collect += '<label for="' + fieldName  + '">' + fieldName.replace("_"," ")  + '</label>';
					collect += '<textarea id="' + fieldName  + '" name="' + fieldName  + '">' + fieldValue  + '</textarea>';
					collect += '</div><br>';				
				} else if (fieldEditor === "rich_text") {
					collect += '<div class="formElement">';
					collect += '<label for="' + fieldName  + '-content">' + fieldName.replace("_"," ")  + '</label>';
					collect += '<input type="hidden" id="' + fieldName  + '-content" name="' + fieldName + '"/>';
					collect += '<div class="richTextEditor2" id="' + fieldName + '"></div>';
					collect += '</div><br>';				
				} else if (fieldEditor === "image") {
					collect += '<div class="formElement">';
					collect += '<label for="' + fieldName  + '">' + fieldName.replace("_"," ")  + '</label>';
					collect += '<input type="text" id="entryField-' + fieldName  + '" name="' + fieldName + '" value="' + fieldValue  + '"/> ';
					collect += '<a href="javascript:;" onclick="imagePicker(this)" class="imagePicker" data-input-id="entryField-' + fieldName + '">';
					collect += '<img class="imageIcon" src="icons/images-dark.svg" alt="images" title="images"/>';
					collect += '</a>';
					collect += '</div><br>';				
				}
			}

		}
		collect += '<input type="hidden" name="function" value="editDatabaseEntry">';
		collect += '<input type="hidden" name="entryId" value="' + entryId + '">';
		collect += '<input type="hidden" name="databaseFile" value="' + databaseInfo.file + '">';
		collect += '<input type="submit" value="update">';
		collect += '<a class="button cancelEntryEdit" href="javascript:;">Cancel</a>';
		collect += '<p class="error"></p>';
		collect += '</form>';
		wrapper.html(collect);
		wrapper.fadeIn();

		// date picker
		$( ".datePicker" ).datepicker({ dateFormat: 'mm/dd/yy', constrainInput: false });

		$(".cancelEntryEdit").click( function() {
			wrapper.html("");
			$("#databaseWrapper").fadeIn();
		});

		$(".richTextEditor2").each(function( index ) {
			var editorId = $(this).attr('id');
 			var editor = new Quill("#" + editorId, {
				theme: 'snow',
				modules: {
					toolbar: toolbarOptions
				}
			});
			//editor.root.innerHTML = "test";
			var currentContent = databaseEntries[entryId][editorId].replace(new RegExp("\\\\", "g"), "");
			$("#" + editorId + " .ql-editor").html(currentContent);
			//console.log(currentContent);
		});

		$(".noSpacesAllowed").on('input', function(){
    		$(this).val(function(_, v){
      			return v.replace(/\s+/g, '');
    		});
		});

		//editor.root.innerHTML = elementContent;
		var form = $("#entryEditForm");
		form.submit( function(e) {
			e.preventDefault();
			$(".richTextEditor2").each( function(index) {
		  		var editorId = $( this ).attr('id');
		  		$('#' + editorId + '-content').val( $( "#" + editorId + " .ql-editor").html() );
		  		console.log("#" + editorId + " .ql-editor");
			});
			$.post('./functions_database.php', form.serialize(), function(data, status){
				if (data.indexOf("Error:") >= 0) {
					var formElement = $(e.target);
					formElement.find(".error").text(data);
				} else {
					initEntryList();
					$("#entryEditWrapper").hide().html("");
					$("#databaseWrapper").fadeIn();
				}
			});
		});
	});		    


	$(".deleteEntry").click( function() {
	    var fieldId = $(this).data("id");
	    var databaseFile = databaseInfo.file;
	    var r = confirm("Are you sure you want to delete " + fieldId + "?");
	    if (r == true) {
			$.get('./functions_database.php?function=deleteDatabaseEntry&fieldId=' + fieldId + '&databaseFile=' + databaseFile, function(data, status){
				localStorage[entryStorage] = 1;
				initEntryList();
			});
	    }
	});


}

function initFieldList() {
	var fieldListWrapper = $("#fieldListWrapper");

	$.get("./functions_database.php?function=printDatabaseFields&databaseId=" + databaseInfo.id, function(data, status){
	    fieldListWrapper.html(data);
	    addFieldListeners();
	});

}

initFieldList();

function initEntryList() {
	var databaseWrapper = $("#databaseWrapper");

	$.get("./functions_database.php?function=printDatabaseEntries&databaseId=" + databaseInfo.id, function(data, status){
	    databaseWrapper.html(data);
	    addEntryListeners();
	});

}

initEntryList();

