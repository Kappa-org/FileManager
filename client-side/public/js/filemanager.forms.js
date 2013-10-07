var $fileManager, FileManager;

FileManager = (function() {
  function FileManager() {}

  FileManager.prototype.width = 960;

  FileManager.prototype.height = 600;

  FileManager.prototype.init = function(url, width, height) {
    var _this = this;
    this.url = url;
    this.width = width;
    this.height = height;
    return $("input[data-kappa-filemanager]").click(function(element) {
      var type;
      type = $("#" + element.target.id).attr('data-kappa-filemanager');
      return _this.openWindow(type);
    });
  };

  FileManager.prototype.openWindow = function(type) {
    return window.open(this.url + '/' + type + '#' + type, 'Správce souborů', "width=" + this.width + ",height=" + this.height);
  };

  return FileManager;

})();

$fileManager = new FileManager();
