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
        options.url =  options.url || 'app=portal&module=ajax&section=status&do=getfriends';
        return {
        	setup: function() {
        		  //Unbind
        		  if(jQuery(options.mentionObj))
        		  	jQuery(options.mentionObj).unbind();
        		  	
				  jQuery(options.mentionObj).mentionsInput({
    				    onDataRequest:function (mode, query, callback) {
                            new Ajax.Request( ipb.vars['base_url'] + options.url + '&md5check=' + ipb.vars['secure_hash'],
                			{
                    				method: 'get',
                    				evalJSON: 'force',
                    				onSuccess: function(t)
                    				{
                    					if( Object.isUndefined( t.responseJSON ) )
                    					{
                    						// Well, this is bad.
                    						Debug.error("Invalid response returned from the server");
                    						return;
                    					}
                    					
                    					if( t.responseJSON['error'] )
                    					{
                    						switch( t.responseJSON['error'] )
                    						{
                    							case 'requestTooShort':
                    								Debug.warn("Server said request was too short, skipping...");
                    							break;
                    							default:
                    								Debug.error("Server returned an error: " + t.responseJSON['error']);
                    								alert("Server returned an error: " + t.responseJSON['error'])
                    							break;
                    						}
                    						
                    						return false;
                    					}
                    					if( t.responseJSON['status'] == 'success' ) {
                        					  try {
                            					  data = t.responseJSON['friends'];
                                   				  data = _.filter(data, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
                                				  callback.call(this, data);
                        					  } catch( err ) {
                            					  Debug.error( err );
                        					  }
                    					}
                    					
                				}
            				});
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