(function(){tinymce.PluginManager.requireLangPack("filemanager");tinymce.create("tinymce.plugins.ExamplePlugin",{init:function(a,b){a.addCommand("mceExample",function(){a.windowManager.open({file:"FileManager",width:970,height:550},{plugin_url:b,some_custom_arg:"custom arg"})});a.addButton("filemanager",{title:"Kappa file manager",cmd:"mceExample",image:b+"/img/filemanager.gif"});a.onNodeChange.add(function(d,c,e){c.setActive("filemanager",e.nodeName=="IMG")})},createControl:function(b,a){return null},getInfo:function(){return{longname:"Kappa file manager",author:"Ondřej Záruba",authorurl:"https://github.com/Kappa-org/FileManager",infourl:"https://github.com/Kappa-org/FileManager",version:"1.0"}}});tinymce.PluginManager.add("filemanager",tinymce.plugins.ExamplePlugin)})();