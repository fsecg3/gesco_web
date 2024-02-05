$(document).ready(function(){
	var urlPath = window.location.pathname,
    urlPathArray = urlPath.split('.'),
    tabId = urlPathArray[0].split('/').pop();
	$('#departement, #user, #dep').removeClass('active');	
	$('#'+tabId).addClass('active');
});