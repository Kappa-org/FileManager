$.nette.init()
$('a[data-toggle=tooltip]').tooltip()
$(document).ready( ->
  $(".closeModal").click( ->
    id = $(this).attr('data-target');
    $("#" + id).modal('hide');
  );
);