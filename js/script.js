 /************** start: functions. **************/

function loading() {
	jQuery("div.loader").show();
}
function closeloading() {
	jQuery("div.loader").fadeOut('normal');
}
var popupStatus = 0; // set value
function loadPopup(id) {
	if(popupStatus == 0) { // if value is 0, show popup
		closeloading(); // fadeout loading
		jQuery("#"+id).fadeIn(0500); // fadein popup div
		jQuery("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
		jQuery("#backgroundPopup").fadeIn(0001);
		popupStatus = 1; // and set value to 1			
		var scrollTop = parseInt(jQuery(window).scrollTop());
		jQuery("#"+id).css("top", scrollTop);
	}
}
function disablePopup() {
	if(popupStatus == 1) { // if value is 1, close popup
		jQuery(".toPopup").fadeOut("normal");
		jQuery("#backgroundPopup").fadeOut("normal");
		popupStatus = 0;  // and set value to 0
		jQuery('#popup_content').html('Loading....');
	}
}

jQuery( document ).ready(function() {
	jQuery(".load-sage-pay").click(function(event) {
		loadPopup('toPopup1');
		event.preventDefault();
		data = jQuery(this).attr('data-value');
		jQuery.when(send_data(jQuery(this).attr('href'), data)).done(function(a1){	
			 jQuery('#popup_content').html(a1);
		});	
	});

	 jQuery("div.close").click(function() {
	     close_sagepay_popup();
	});


});

function close_sagepay_popup()
{
	 var r = confirm("Do you really want to close the payment popup?");
	  if (r == true)
	  {
		disablePopup();  // function close pop up
	  }
		
}

function send_data(url, data)
{
	return jQuery.ajax({
		type: 'POST',
		url: url,
		data: data,
		beforeSend:function(){
			loading(); 		 		
		},
		success:function(data){
			closeloading();
		},
		error:function(){
			closeloading();
			alert('Oops! Try that again in a few moments.');
		}
	});	
}