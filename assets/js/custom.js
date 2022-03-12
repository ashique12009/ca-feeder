// JS Document
jQuery(document).ready(function($){
  	var current_title = $(document).attr('title');
	$("input#page_title").val(current_title);

	var current_href = $(location).attr('href');
	$("input#page_url").val(current_href);

	// form detail
	$("button.btn-shortcodeGenerator").click(function(){
		$("p.shortcodeGenerator").html("Detail form codes or popup the form code and the shortcode");
	});

	// checkbox checked count
	// $("input.take-fields[type=checkbox]").on("click", function(){
	//   $(".checkedInput").text(n + (n === 1 ? " is" : " are") + " checked!");
	// });

	// Click on generate form button
	$('#btn-shortcodeGenerator').click(function() {
		$(".spinner").addClass("is-active");
		// Collect field_collection from checkboxes
		field_collection_object = collect_field();
		field_collection = field_collection_object.field_collection;
		// Collect field_name_collection from checkboxes
		field_name_collection = field_collection_object.field_name_collection;
		// set ajax data
		var data = {
			'action': 'cld_save_form',
			'app_id': $('#appid').val(),
			'form_id': $('#formid').val(),
			'form_name': $('#formname').val(),
			'user_defined_word': $('#user-defined-word').val() != '' ? $('#user-defined-word').val().split(' ').join('-').toLowerCase() : 'na',
			'field_collection': field_collection,
			'field_name_collection': field_name_collection
		};

		$.post(settings.ajaxurl, data, function(response) {
			$(".spinner").removeClass("is-active");
		});
	});

	function collect_field() {
		$('#customForm table').empty();
		var field_collection = [];
		var field_name_collection = [];
		$('input.take-fields[type=checkbox]').each(function(i, obj) {
			var n = $("input:checked").length;
			var field_title = '';

			if ($(this).is(":checked")) {
				console.log('HERE');
				field_title = $(this).val();
				field_name = $(this).attr('name');
				field_collection.push(field_title);
				field_name_collection.push(field_name);
				// Append field to customForm		
				$('#customForm table').append('<tr><th>'+field_title+'</th><td><div class="formFieldWrap"><input class="custom-form-control regular-text code" type="text" name="'+field_name+'" placeholder="'+field_title+'" value="" /></div></td></tr>'); //add input box
			}
		});

		return {field_collection: field_collection, field_name_collection: field_name_collection}
	}

	// Shortcode remove
	$('.sc-remove').click(function() {
		$(".spinner").addClass("is-active");
		var sc = $(this).prev('.oname').text();
		var data = {
			'action': 'cld_remove_sc',
			'sc_value': sc
		};
		$.post(settings.ajaxurl, data, function(response) {
			$(".spinner").removeClass("is-active");
			location.reload();
		});
	});
});