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
            initEvents: function() {
                if($('statusPostForm'))
        			$('statusPostForm').on( 'submit', ipb.mentions.init().post.bindAsEventListener( this, 'statusContent' ) );
            },
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
        	post: function( e, field ) {
	        	Event.stop(e);
        		
        		if ( $( field ).value.length < 2 || $( field ).value == ipb.lang['prof_update_default'] )
        		{
        			return false;
        		}
        		
        		var su_Twitter  = $('su_Twitter') && $('su_Twitter').checked ? 1  : 0;
        		var su_Facebook = $('su_Facebook') && $('su_Facebook').checked ? 1 : 0;
        		var su_Tags;
        		jQuery('#statusContent').mentionsInput('getMentions', function(data) {
        		          su_Tags = JSON.stringify(data);
        		});
        		new Ajax.Request( ipb.vars['base_url'] + "app=portal&section=status&module=ajax&do=new&md5check=" + ipb.vars['secure_hash'] + '&smallSpace=' + ipb.status.smallSpace + '&skin_group=' + ipb.status.skin_group + '&forMemberId=' + ipb.status.forMemberId,
        						{
        							method: 'post',
        							evalJSON: 'force',
        							parameters: {
        								content: $( field ).value.encodeParam(),
        								su_Twitter: su_Twitter,
        								su_Facebook: su_Facebook,
        								su_Tags: su_Tags
        							},
        							onSuccess: function(t)
        							{
        								if( Object.isUndefined( t.responseJSON ) )
        								{
        									alert( ipb.lang['action_failed'] );
        									return;
        								}
        								
        								if( t.responseJSON['error'] )
        								{
        									alert( t.responseJSON['error'] );
        								}
        								else
        								{
        									try {
        										$('status_wrapper').innerHTML = t.responseJSON['html'] + $('status_wrapper').innerHTML;
        										
        										/* Showing latest only? */
        										if ( ipb.status.myLatest )
        										{
        											if ( $('statusWrap-' + ipb.status.myLatest ) )
        											{
        												$('statusWrap-' + ipb.status.myLatest ).hide();
        											}
        										}
        										
        										/* Need to blur out of field
        											@link	http://community.invisionpower.com/tracker/issue-21358-small-input-field-behavior-issue-after-updating-status/
        										*/
        										$( field ).blur();
        										$( field ).value = '';
        										
        										ipb.menus.closeAll(e);
        										
        										/* Re-init events */
        										ipb.status.initEvents();
        									}
        									catch(err)
        									{
        										Debug.error( 'Logging error: ' + err );
        									}
        								}
        							}
        						});
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
    	        instance.setup();
    	        instance.initEvents();
	        }
	        
	        return instance;
	    }
	};
	return _static;
})();

jQuery().ready(function(){
    ipb.mentions.init();
});