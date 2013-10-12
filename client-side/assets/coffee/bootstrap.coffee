$(document).ready(->
  $.nette.init()
  $('a[data-toggle=tooltip]').tooltip()
  checked = []
  $('.forInsert').change( ->
    if $(this).prop('checked')
      checked.push($(this).attr('data-src'))
    if $(this).prop('checked') == false
      index = checked.indexOf($(this).attr('data-src'))
      checked.splice(index, 1)
    view = checked[checked.length - 1]?
    if view && $(this).attr('data-type') == "image"
      $("#image-preview").attr('src', checked[checked.length - 1])
    else
      imageDefault = "data:image/gif;base64,R0lGODdhyADIAOMAAMzMzJaWlrGxsZycnKOjo6qqqre3t76+vsXFxQAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAyADIAAAE/hDISau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NGZAgMBAwIWBtXX2dvYKtTW3xTa4t3mhQQB6+sFFALs6+MA8PHzJerx7hP17PP98ggZCFjvgAQE7QAUWIfgYMKFARqaGBgAW0GHAdxBlIgwo0KG/oO07QMQkN66CSXroax4QuTKbyolpDwpk+Whjt/UEZigU0JPnwF2XmBXMwAHnECFAvj5c2lQRPkkVhu5cICEqROqYmBngKLRDVGvepSgFQBWstYO1RtZ0uTXtjEvLCwwl8Pal/xowqUpcJ1VvBJi7v16oSNIDRT/1hwn2KbbQgf8Slyc963juHITaohsbTLJy3pBExZULYBBCmc//k1dFgNFAxtKn57AOq3ZsaoJ1YNdoSnTp0k1lFZ8YbcF38CRKwXUceQ7vjMJt80WVB1vC82LQ7cZd/qfep4nZN+IUePhC9W62rYAvvDDw+PPAyodbzRAx279bSX6eTTt+nzlfzfdfff8QV88FYTDTYLeZMDOPhChB6B/ChZYoTQYZqjhhhx26OGHIIYo4ogklmjiiSimqOKKLLbo4oswxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGQ6EgEAOw=="
      $("#image-preview").attr('src', imageDefault)
  )
  $("#insertFile").click( ->
    unless window.opener?
      FileBrowserDialogue =
        init: ->
        mySubmit: ->
          args = top.tinymce.activeEditor.windowManager.getParams()
          if checked.length - 1 > -1
            URL = checked[checked.length - 1]
          else
            alert("Musíte vybrat soubor!")
            return false
          win = args.window
          win.document.getElementById(args.input).value = URL
          unless typeof (win.ImageDialog) is "undefined"
            win.ImageDialog.getImageData()  if win.ImageDialog.getImageData
            win.ImageDialog.showPreviewImage URL  if win.ImageDialog.showPreviewImage
          top.tinymce.activeEditor.windowManager.close()
      tinymce.PluginManager.add FileBrowserDialogue.init, FileBrowserDialogue
      FileBrowserDialogue.mySubmit()
    else
      if checked.length - 1 > -1
        URL = checked[checked.length - 1]
      else
        alert("Musíte vybrat soubor!")
        return false
      hash = window.location.hash.substring(1)
      window.opener.$("input[data-kappa-filemanager="+hash+"]").val(URL)
      window.opener.$("input[data-kappa-filemanager="+hash+"]").after('<div class="insertedImage"><img src="' + URL + '"></div>')
      window.close()
    return false;
  )
)