(function() {  
    tinymce.create('tinymce.plugins.jamiesocial', {  
        init : function(ed, url) {  
            ed.addButton('jamiesocial', {  
                title : 'Add Jamie Social Icons',  
                image : url+'/images/share-balloon.png',  
                onclick : function() {  
                     ed.selection.setContent('[jamiesocial]');  
  
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        },  
    });  
    tinymce.PluginManager.add('jamiesocial', tinymce.plugins.jamiesocial);  
})(); 