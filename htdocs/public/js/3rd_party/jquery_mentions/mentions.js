//No conflict jQuery
if (jQuery) {
    jQuery.noConflict();
}else {
    Debug.error( 'jQuery not loaded' );
}

var _mentions = window.IPBoard;

_mentions.prototype.mentions = (function() {
    // Construct
    function Mentions( options )  {
        // set options to the options supplied or an empty object if none are 
        // provided
        options = options || {};
        options.mentionObj = options.mentionObj || 'textarea.mention';
        return {
        	setup: function() {
        		  //Unbind
        		  if(jQuery(options.mentionObj))
        		  	jQuery(options.mentionObj).unbind();
        		  	
				  jQuery(options.mentionObj).mentionsInput({
				    onDataRequest:function (mode, query, callback) {
				      var data = [
				        { id:1, name:'Kenneth Auchenberg', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:2, name:'Jon Froda', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:3, name:'Anders Pollas', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:4, name:'Kasper Hulthin', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:5, name:'Andreas Haugstrup', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:6, name:'Pete Lacey', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:7, name:'kenneth@auchenberg.dk', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:8, name:'Pete Awesome Lacey', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' },
				        { id:9, name:'Kenneth Hulthin', 'avatar':'http://cdn0.4dots.com/i/customavatars/avatar7112_1.gif', 'type':'contact' }
				      ];
				
				      data = _.filter(data, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
				
				      callback.call(this, data);
				    }
				  });
        	},
        	post: function() {
	        	
        	}
        }
    }
        
	var instance;
	var _static = {
	    // This is a method for getting an instance
	    // It returns a singleton instance of a singleton object
	    init: function(options) {
	        if(instance === undefined) {
		        instance = new Mentions(  );
	        }
	        
	        instance.setup();
	        
	        return instance;
	    }
	};
	return _static;
})();

jQuery().ready(function(){
    ipb.mentions.init();
});