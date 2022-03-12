// JS Document
jQuery(document).ready(function($){
    var current_title = $(document).attr('title');
	$("input#page_title").val(current_title);

	var current_href = $(location).attr('href');
	$("input#page_url").val(current_href);
});