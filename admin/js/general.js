
var mobileMenuIsOpen = false;

$("#mobileMenu").click( function() {
	if ( mobileMenuIsOpen ) {
		mobileMenuIsOpen = false;
		$(".topNavInner").removeClass("open");
	} else {
		mobileMenuIsOpen = true;
		$(".topNavInner").addClass("open");
	}
});


$(".toggleSectionsLink").click( function() {
	var sectionId = $(this).data("section");
	changeToggleSection(sectionId)
});

function openSection(event) {
	var sectionId = $(event).data("section");
	console.log($(event).data("section"));
	changeToggleSection(sectionId);
}

function changeToggleSection(sectionId) {

	$(".toggleSectionsLink").removeClass("active");
	$('[data-section="' + sectionId + '"]').addClass("active");

	$(".toggleSections").removeClass("active");
	$(".toggleSections#" + sectionId).addClass("active");

	if ( mainNavToggleClose ) {
		$(".closeToggleSection").remove();
		var closeLink = '<a class="button closeToggleSection" href="javascript:;" onclick="closeToggleSection()"><img src="icons/cross.svg" alt="close"/></a>';
		$(".toggleSections#" + sectionId).prepend(closeLink);
	}

    location.hash = sectionId;
    window.scrollTo(0,0);

	if ( mobileMenuIsOpen ) {
		$(".topNavInner").removeClass("open");
		mobileMenuIsOpen = false;
	} 

}

function closeToggleSection() {
	$(".toggleSectionsLink").removeClass("active");
	$(".toggleSections").removeClass("active");
	location.hash = '';
}

if ( location.hash ) {
    var hash = location.hash.replace('#','');
    changeToggleSection(hash);
    window.scrollTo(0,0);
}


$("a.nameChangeToggle").click( function() {
	$("form.nameChange").toggle();
});

$("a.typeChangeToggle").click( function() {
	$("form.typeChange").toggle();
});

$("a.descriptionChangeToggle").click( function() {
	$("form.descriptionChange").toggle();
});


$(".noSpacesAllowed").on('input', function(){
    $(this).val(function(_, v){
      	return v.replace(/\s+/g, '');
    });
});

// date picker
// format: 2005-12-30
$( function() {
	$( ".datePicker" ).datepicker({ dateFormat: 'yy-mm-dd', constrainInput: false });
});

// image picker
function loadMoreImages(obj,start) {
	var inputId = $(obj).data("input-id");
	$(obj).parent().parent().hide();
	$.get("./functions_general.php?function=printImageList&start=" + start + "&inputId=" + inputId, function(data, status){		
	    $("#dialog ul.imageList").append(data);
	    lazyLoadInit();
	});
}
function imagePicker(obj) {
	var inputId = $(obj).data("input-id");
	$.get("./functions_general.php?function=printImageList&start=0&inputId=" + inputId, function(data, status){		
		var windowWidth = $(window).width();
		var dialogWidth = windowWidth * 0.8; //this will make the dialog 80% of the window width
	    $("#dialog").dialog({
	      	modal: true,
	      	width: dialogWidth,
	      	resizable: true
	    });
	    var collect = '<div class="imageListWrapper"><ul class="imageList">';
	    collect += data;
	    collect += '</ul></div>';
	    $("#dialog").html(collect);
	    lazyLoadInit();
	});
}
function chooseImage(obj) {
	var inputDest = $(obj).data("input-destination");
	var imageSrc = $(obj).parent().find(".imageSize").val();
	$("#" + inputDest).val(imageSrc);
	$("#dialog").dialog("close");
}


/* lazyload.js (c) Lorenzo Giuliani
 * MIT License (http://www.opensource.org/licenses/mit-license.html)
 *
 * expects a list of:  
 * `<img src="blank.gif" data-src="my_image.png" width="600" height="400" class="lazy">`
 */
function lazyLoadInit() {
	!function(window){
	  var $q = function(q, res){
	        if (document.querySelectorAll) {
	          res = document.querySelectorAll(q);
	        } else {
	          var d=document
	            , a=d.styleSheets[0] || d.createStyleSheet();
	          a.addRule(q,'f:b');
	          for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
	            l[b].currentStyle.f && c.push(l[b]);

	          a.removeRule(0);
	          res = c;
	        }
	        return res;
	      }
	    , addEventListener = function(evt, fn){
	        window.addEventListener
	          ? this.addEventListener(evt, fn, false)
	          : (window.attachEvent)
	            ? this.attachEvent('on' + evt, fn)
	            : this['on' + evt] = fn;
	      }
	    , _has = function(obj, key) {
	        return Object.prototype.hasOwnProperty.call(obj, key);
	      }
	    ;

	  function loadImage (el, fn) {
	    var img = new Image()
	      , src = el.getAttribute('data-src');
	    img.onload = function() {
	      if (!! el.parent)
	        el.parent.replaceChild(img, el)
	      else
	        el.src = src;

	      fn? fn() : null;
	    }
	    img.src = src;
	  }

	  function elementInViewport(el) {
	    var rect = el.getBoundingClientRect()

	    return (
	       rect.top    >= 0
	    && rect.left   >= 0
	    && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
	    )
	  }

	    var images = new Array()
	      , query = $q('img.lazy')
	      , processScroll = function(){
	          for (var i = 0; i < images.length; i++) {
	            if (elementInViewport(images[i])) {
	              loadImage(images[i], function () {
	                images.splice(i, i);
	              });
	            }
	          };
	        }
	      ;
	    // Array.prototype.slice.call is not callable under our lovely IE8 
	    for (var i = 0; i < query.length; i++) {
	      images.push(query[i]);
	    };

	    processScroll();
	    addEventListener('scroll',processScroll);

	}(this);
}

lazyLoadInit();
