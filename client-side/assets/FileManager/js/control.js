/**
 * Autor: <zarubaondra@gmail.com>
 * Date: 8.1.13
 */

$(document).ready(function(){
	$("#check-all").change(function(e){
		if($(this).is(':checked'))
		{
			$(".img-checkbox").attr('checked', true);
			$(".form-title").css('display', 'none');
		}
		if(!$(this).is(':checked'))
		{
			$(".img-checkbox").attr('checked', false);
			$(".form-title").css('display', 'block');
		}
	});

	$("#insert-form").submit(function(){
		if(tinyMCEPopup.getWindowArg('some_custom_arg') == "tinyMCE editor")
		{
			var selected = new Array();
			$("input:checked").each(function() {
				if($(this).attr('data-src'))
					selected.push($(this).attr('data-src'));
			});
			var html = "";
			for(var i = 0; i < selected.length; i++)
			{
				if($("#action").val() == "img")
					html += '<img src="'+selected[i]+'" alt="'+$("#text").val()+'" width="150" height="110">';
				if($("#action").val() == "ahref")
					html += '<a href="'+selected[i] + '">'+$("#text").val()+'</a>';
			}
			tinyMCEPopup.editor.execCommand('mceInsertContent', false, html);
		}
		else
		{
			var selected = new Array();
			$("input:checked").each(function() {
				if($(this).attr('data-src'))
					selected.push($(this).attr('data-src'));
			});
			window.opener.$("#imagePreview").remove();
			window.opener.$(".Kappa-ImagePreview").val(selected[0]);
			var imagePreview = '<div id="imagePreview"><img src="'+selected[0]+'" alt="'+$("#title").val()+'"  width="150" height="110"></div>';
			window.opener.$(".Kappa-ImagePreview").after(imagePreview);
			window.close();
			return false;
		}
		window.close();
	});

	$('.img-checkbox').change(function(){
		if($(this).attr("data-type") == "image")
		{
			if($(this).is(":checked"))
			{
				$("#image-preview").attr('src', $(this).attr('data-src'));
			}
			else
			{
				var selected = new Array();
				$("input:checked").each(function() {
					if($(this).attr('data-src'))
						selected.push($(this).attr('data-src'));
				});
				$("#image-preview").attr('src', selected[selected.length - 1]);
			}
		}
		var selected = new Array();
		$("input:checked").each(function() {
			if($(this).attr('data-src'))
				selected.push($(this).attr('data-src'));
		});
		if(selected.length == 0)
		{
			var orig = "http://placehold.it/200x200";
			$("#image-preview").attr('src', orig);
		}
	})
});