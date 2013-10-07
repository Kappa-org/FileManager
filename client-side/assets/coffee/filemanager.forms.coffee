$(document).ready( ->
  openWindow = (url, type) ->
    width = $(document).width() - 100
    height = $(document).height() - 100
    window.open(url + '/' + type, 'Správce souborů', 'width=' + width + ',height=' + height);
  $("input[data-kappa-filemanager]").click( ->
    openWindow('/file', $(this).attr('data-kappa-filemanager'));
  )
)