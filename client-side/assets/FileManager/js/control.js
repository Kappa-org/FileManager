/**
 * Autor: <zarubaondra@gmail.com>
 * Date: 8.1.13
 */

$(document).ready(function(el){

	var noImage = "http://placehold.it/200x200";

	var check = function()
	{
		var selected = new Array();
		$("input:checked").each(function() {
			if($(this).attr('data-src'))
				selected.push($(this));
		});
		return selected;
	}

	$("#check-all").change(function(e){
		if($(this).is(':checked'))
		{
			$(".img-checkbox").attr('checked', true);
			$(".form-title").css('display', false);
			var selected = check();
			$("#image-preview").attr('src', selected[selected.length - 1]);

		}
		if(!$(this).is(':checked'))
		{
			$(".img-checkbox").attr('checked', false);
			$(".form-title").css('display', true);
			$("#image-preview").attr('src', noImage);
		}
	});

	$("#insert-form").submit(function(){
		if(tinyMCEPopup.getWindowArg('some_custom_arg') == "tinyMCE editor")
		{
			var selected = check();
			var html = "";
			for(var i = 0; i < selected.length; i++)
			{
				if($("#action").val() == "img" && selected[i].attr('data-type') == "image")
					html += '<img src="'+selected[i].attr('data-src')+'" alt="'+$("#text").val()+'" width="150" height="110">';
				if($("#action").val() == "ahref")
					html += '<a href="'+selected[i].attr('data-src') + '">'+$("#text").val()+'</a>';
			}
			tinyMCEPopup.editor.execCommand('mceInsertContent', false, html);
			window.close();
		}
		else
		{
			var selected = check();
			if(selected[0].attr('data-type') == "image")
			{
				window.opener.$("#imagePreview").remove();
				window.opener.$(".Kappa-SelectImage").val(selected[0].attr('data-src'));
				var imagePreview = '<div id="imagePreview"><img src="'+selected[0].attr('data-src')+'" alt="'+$("#title").val()+'"  width="150" height="110"></div>';
				window.opener.$(".Kappa-SelectImage").after(imagePreview);
				window.close();
				return false;
			}
			else
			{
				window.opener.$(".Kappa-SelectFolder").val(selected[0].attr('data-src'));
				window.close();
				return false;
			}
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
				var selected = check();
				$("#image-preview").attr('src', selected[selected.length - 1]);
			}
		}
		var selected = check();
		if(selected.length == 0)
		{
			var orig = "http://placehold.it/200x200";
			$("#image-preview").attr('src', orig);
		}
	})
});