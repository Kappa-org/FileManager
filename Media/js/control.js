$(document).live('ready', function(){
    $("#actionForm").live('submit', function(){
        try
        {
            if(tinyMCEPopup.getWindowArg('some_custom_arg') == "tinyMCE editor")
            {
                var selected = new Array();
                $('.nahravac_slozky_a_soubory input:checked').each(function() {
                    selected.push($(this).attr('id'));
                });
                var html = "";
                for(var i = 0; i < selected.length; i++)
                {
                    if($("#action").val() == "img")
                        html += '<img src="'+ $("#"+selected[i]).attr('data-path')+'" alt="'+$("#text").val()+'" width="150" height="110">';
                    if($("#action").val() == "ahref")
                        html += '<a href="'+ $("#"+selected[i]).attr('data-path') + '">'+$("#text").val()+'</a>';
                    if($("#action").val() == "big")
                        html += '<img src="'+ $("#"+selected[i]).attr('data-path')+'" alt="'+$("#text").val()+'"';
                }
                tinyMCEPopup.editor.execCommand('mceInsertContent', false, html);
            }
            else
            {
                var selected = new Array();
                $('.nahravac_slozky_a_soubory input:checked').each(function() {
                    selected.push($(this).attr('id'));
                });
                html = $("#"+selected[selected.length-1]).attr('data-path');
                window.opener.$("#imagePreview").remove();
                window.opener.$(".Kappa-ImagePreview").val(html);
                var imagePreview = '<div id="imagePreview"><img src="' + html + '" width="150" height="110"></div>';
                window.opener.$(".Kappa-ImagePreview").after(imagePreview);
            }
        }
        catch(err)
        {
            var selected = new Array();
            $('.nahravac_slozky_a_soubory input:checked').each(function() {
                selected.push($(this).attr('id'));
            });
            html = $("#"+selected[selected.length-1]).attr('data-path');
            window.opener.$("#imagePreview").remove();
            window.opener.$(".Kappa-ImagePreview").val(html);
            var imagePreview = '<div id="imagePreview"><img src="' + html + '" width="150" height="110"></div>';
            window.opener.$(".Kappa-ImagePreview").after(imagePreview);
        }
        return false;
    });

    $('input:checkbox').live('change', function(){
        if($(this).attr("data-type") == "image")
        {
            if($(this).attr("checked") == "checked")
            {
                $(".nahravac_nahled").attr('src', $(this).attr('data-path'));
            }
            else
            {
                var selected = new Array();
                $('.nahravac_slozky_a_soubory input:checked').each(function() {
                    selected.push($(this).attr('id'));
                });
                // TODO: Zjistit proč to bere první a né poslední zaškrtnutý obrázek
                $(".nahravac_nahled").attr('src', $("#"+selected[selected.length - 1]).attr('data-path'))
            }
        }
        var selected = new Array();
        $('.nahravac_slozky_a_soubory input:checked').each(function() {
            selected.push($(this).attr('id'));
        });
        if(selected.length == 0)
        {
            var orig = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABuCAIAAABKon3BAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpEMzYxM0NDRkYwRkZFMDExQTBBMjhEMkE4OENENjRDOSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpEMEFFNjdEM0Y1QkExMUUxQjA0RkEzQTJEMEJDM0JFOSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpEMEFFNjdEMkY1QkExMUUxQjA0RkEzQTJEMEJDM0JFOSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCOTNFOTU0M0I1RUZFMTExODM4QUQ3MUQxMjI2MjVDRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpEMzYxM0NDRkYwRkZFMDExQTBBMjhEMkE4OENENjRDOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnlmVoQAAANsSURBVHja7JtBj6IwGECXUUnwwomriYlXExMT/60/ycTEEycTrl70KGJmiGQblkItUFuYfS+ZCZZisY+2H9B68/n88Xj8gXEym81ef4BCQCGgEIWAQkAhoBCFgEJAIaAQhYBCQCGgEIWAQkAhoBCFgEJAIaAQhYBCQCGgEIWAQkAhoBCFgEJAIRhW+P1CJ1GRrkPnA9uWYqegMqvVqig3y7IgCBy0wsrPVtRC5wqyVrNOFOZcr9ei6N1uZ1Dhl8G60PlYTvz+l0pDlHfJRWsW0WSu1Rn2r+7j8VhsrNdrg1fG1+eudM/zhJLiv/ei0mF6fxEf1fmb9qoP6X+GBhVut1sHCt/+WpFYzv/p/rBP/Vo4w3G3QiODUFN+UfuuRrVunE6nYmOz2UwmE6NRacsKrR1gNPdqDqWabsoD1dtyeyb2x/f9+/1efGceoDoIZyqjlCJRPRT1b4siRb8zrL2ALLfCNE3jOBYNcegdaV655VoWphVVL18NTflr96oT5RI7nGF/DoeDcYVTUyGAHMjU1rt+iroSFReB/ol1OENTEY0zhU19kf3obqSIiMZkUMozUpuEYShCsCiK3D+dgbbcbrckScw2RBTaxnhEg0LbGI9oUOjyGQ3hzChZLBbixaHv+wb0odA+4sVh/1cWRKSOIxojQSkKR3+Dj0I3N/jiNhGFo0TEoqI5EpGOifJbw+VySUQ6PvIotPCXx6VGWiAdqW1ECCMe0zAWjnUgRCEKCWccYfDRDOGMA8TKijwo7f+AlHDGZSwTx3GapoyFDIQo/BUKtWawVaZWqif+VrKVEyuzb8szOWuLkI8VOeWjmrZ/vcIuS0R1JtJrrgqrnVFf++VyTp2FAIMiiiKz09dahDOKFUxyS3177cvfoJ6sLe+qPWpEsUySJJfLZUBjoWKdUW2KTu0Ptic00ouaeUHRaixs20x7rjL8lf7KCsVbe6utUBEmvF1KUhnkPu1msCsOP9QKW68v7L/y/e2iwA45FcHUQAiCIMsys4sLC30tOlKdZU3qRP2lTN1y2lwm3pYwDPf7fb7xfD7P57ObVggDhMfcKAQUAgpRiEIUAgoBhShEIQoBhYBCFKIQhYBCQCEKAYWAQkAhCgGFgEJAIQoBhYBCQCEKAYWAQkDhf6nQC4J5lj2oi5Eync5+BBgAzdHmrfmno/IAAAAASUVORK5CYII=";
            $(".nahravac_nahled").attr('src', orig);
        }
    })
    $('#allCheck').live('change', function(){
        if($(this).attr('checked') == "checked")
        {
            $('.nahravac_slozky_a_soubory input:checkbox').each(function(){
                $(this).attr('checked', "checked");
            });
        }
        else
        {
            $('.nahravac_slozky_a_soubory input:checkbox').each(function(){
                $(this).attr('checked', "");
            });
        }

    });
});