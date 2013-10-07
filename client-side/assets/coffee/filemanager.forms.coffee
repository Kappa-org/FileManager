class FileManager
  width: 960
  height : 600
  init: (@url, @width, @height) ->
    $("input[data-kappa-filemanager]").click( (element) =>
      type = $("##{element.target.id}").attr('data-kappa-filemanager')
      @openWindow(type)
    )
  openWindow: (type) ->
    window.open(@url + '/' + type + '#' + type, 'Správce souborů', "width=#{@width},height=#{@height}");

$fileManager = new FileManager()