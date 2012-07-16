/************************************************/
/* IPB3 Javascript								*/
/* -------------------------------------------- */
/* ips.portal.js - Gallery javascript			*/
/* (c) IPS, Inc 2008							*/
/* -------------------------------------------- */
/* Author: Rikki Tissier & Brandon Farber		*/
/************************************************/

var _comments     = window.IPBoard;
var _comments_id  = 0;

ipb.comments.add = function(e)
{
	Event.stop(e);

	var content = ipb.textEditor.getEditor().getText();
	var isRte   = ipb.textEditor.getEditor().isRte();
	
	if( content.blank() )
	{
		alert( ipb.lang['post_empty'] );
		return;
	}
	
	/* Close editor */
	ipb.textEditor.getEditor().minimizeOpenedEditor();
	
	in_use = 0;
	
	var fromApp    = jQuery(this).parents('form').find('input:hidden[name=fromApp]').val();
	var parentId   = jQuery(this).parents('form').find('input:hidden[name=parentId]').val();
	
	var url = ipb.comments.data['ajaxSaveUrl'] ? ipb.comments.data['ajaxSaveUrl'] : ipb.vars['base_url'] + 'app=core&module=ajax&section=comments&do=add&parentId=' + parentId + '&fromApp=' + fromApp;

	new Ajax.Request(	url,
						{
							method: 'post',
							encoding: ipb.vars['charset'],
							parameters: {
								md5check:			ipb.vars['secure_hash'],
								Post:				content.encodeParam(),
								comment_name:		$('comment_name') ? $('comment_name').value : '',
								isRte:				isRte
							},
							onSuccess: function(t)
							{
								if ( t.responseJSON && t.responseJSON['error'] )
								{
									if( t.responseJSON['error'] == 'comment_requires_approval' )
									{
										ipb.global.okDialogue( ipb.lang['comment_requires_approval'] );
									}
									else if( t.responseJSON['error'] == 'NO_COMMENT' )
									{
										ipb.global.errorDialogue( ipb.lang['post_empty'] );
									}
									else
									{
										ipb.global.errorDialogue( ipb.lang['no_permission'] );
									}
								}
								else if ( t.responseText && t.responseText != 'no_permission' )
								{
									/* Are we *NOT* on the last page? */
									if ( ! Object.isUndefined( ipb.comments.data ) && ! Object.isUndefined( ipb.comments.data['counts'] ) )
									{
										if ( ( ipb.comments.data['counts']['commentTotal'] ) && ( ( ipb.comments.data['counts']['commentTotal'] - ipb.comments.data['counts']['curStart'] ) >= ipb.comments.data['counts']['perPage'] ) )
										{ 
											/* http redirect */
											window.location = ipb.comments.data['findLastComment'] ? ipb.comments.data['findLastComment'] : ipb.vars['base_url'] + 'app=core&module=global&section=comments&do=findLastComment&parentId=' +ipb.comments.parentId + '&fromApp=' + ipb.comments.data['fromApp'];
											
											return false;
										}
									}
									
									/* Fetch latest ID */
									latestId = 0;
									m        = t.responseText.match( /<a id='comment_(\d+?)'>/ );
									
									if ( m && m[1] )
									{
										latestId = m[1];
									}
									
									$('comment_wrap').insert( t.responseText );
									//Debug.write( 'inserted data' );
									ipb.comments.data['counts']['thisPageCount']++;
									
									/* animate, exterminate, germinate */
									if ( latestId > 0 && $('comment_id_' + latestId ) )
									{
										/* Add dark BG and fetch RGB value */
										$('comment_id_' + latestId ).addClassName( 'row2' );
										var startColor = $('comment_id_' + latestId ).getStyle( 'background-color' );
										
										/* Add light BG and fetch RGB value */
										$('comment_id_' + latestId ).removeClassName('row2').addClassName( 'row1' );
										var endColor    = $('comment_id_' + latestId ).getStyle( 'background-color' );
										var endBorderColor = $('comment_id_' + latestId ).getStyle( 'border-top-color' );
										
										/* Remove light BG */
										$('comment_id_' + latestId).removeClassName('row1').addClassName('row2');
										
										$('comment_id_' + latestId ).hide();
										new Effect.BlindDown( 'comment_id_' + latestId, { duration: 1.0, queue: 'front' } );
										new Effect.Morph( 'comment_id_' + latestId, { 'style': 'border-top-color:' + endBorderColor, queue: 'end' } );
										new Effect.Morph( 'comment_id_' + latestId, { 'style': 'background-color:' + endColor, queue: 'end', afterFinish: function() { $('comment_id_' + latestId ).removeClassName('row2').addClassName( 'row1' ); } } );
									}
									
									prettyPrint();
								}
							}
						}
					);
}


var _urlParser = window.IPBoard;

_urlParser.prototype.urlparser = (function() {
    // Construct
    function Parser( options )  {
        // set options to the options supplied or an empty object if none are 
        // provided
        options = options || {};
        options.url =  options.url || 'app=portal&module=ajax&section=status&do=urlParser';
        return {
            initEvents: function() {
                jQuery().ready( function()
                {
                     jQuery( '#attach' ).click( function() 
                     {
                            new Ajax.Request( ipb.vars['base_url'] + options.url + '&md5check=' + ipb.vars['secure_hash'],
                            {
                            	method: 'post',
                            	evalJSON: 'force',
                            	parameters: {
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
                            			    jQuery( '#statusContent' ).after( t.responseJSON[ 'html' ] );
                            				/* Re-init events */
                            				ipb.urlparser.init().initEvents();
                            			}
                            			catch(err)
                            			{
                            				Debug.error( 'Logging error: ' + err );
                            			}
                            		}
                            	}
                            });
                     });
                });
            },
        	setup: function() {
        	}
        }
    }
        
	var instance;
	var _static = {
	    // This is a method for getting an instance
	    // It returns a singleton instance of a singleton object
	    init: function(options) {
	        if(instance === undefined) {
		        instance = new Parser( options );
    	        instance.setup();
    	        instance.initEvents();
	        }
	        
	        return instance;
	    }
	};
	return _static;
})();

ipb.urlparser.init();