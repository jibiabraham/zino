var InterestTag = {
	onedit: false,
/*    Submit: function( val ) {
        
        Coala.Warm( 'interesttag/new', { 'text': val, 'callback' : InterestTag.SubmitCallback } );
    },
    SubmitCallback : function( val ) {
    	var inp = g( 'newinteresttag' );
    	inp.parentNode.insertBefore( d.createTextNode( val + " " ), inp );
        inp.value = '';
        inp.focus();
    },*/
    Create : function() {
    	var div = d.createElement( 'div' );
    	var ul = d.createElement( 'ul' );
    	ul.style.listStyleType = "none";
    	ul.style.textAlign = "left";
    	
    	var close = document.createElement( 'a' );
		close.onclick = function() {
					Modals.Destroy();
				};
		close.className = "close";
		close.onmouseover = function() {
						document.body.style.cursor = "pointer";
					};
		close.onmouseout = function () {
						document.body.style.cursor = "default";
					};
		
		var closeimg = document.createElement( 'img' );
		closeimg.src = "http://static.chit-chat.gr/images/colorpicker/close.png";
		closeimg.alt = "Κλείσιμο";
		closeimg.title = "Κλείσιμο";
		
		var count = 0;
    	
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterests.split( " " );
    	for ( var i in allinterests ) {
    		if ( allinterests[i] === "" ) {
    			continue;
    		}
    		
    		var li = d.createElement( 'li' );
    		li.appendChild( d.createTextNode( allinterests[i] ) ); 
    		li.onmouseover = ( function( id ) {
    				return function () {
	    				InterestTag.showLinks( id );
	    			};
    			} )(count);
    		li.onmouseout = ( function( id ) {
    				return function() {
    					InterestTag.hideLinks( id );
    				};
    			} )(count);
    		
    		var editimage = d.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var deleteimage = d.createElement( 'img' );
			deleteimage.src = "http://static.chit-chat.gr/images/icons/delete.png";
			
			var edit = d.createElement( 'a' );
			edit.id = "interedit_" + count;
			edit.style.cursor = "pointer";
			edit.style.display = "none";
			edit.alt = "Επεξεργασία";
			edit.title = "Επεξεργασία";
			edit.onclick = ( function( li ) {
					return function() {
						InterestTag.Edit( li );
					}
				} )(li);
	
			var del = d.createElement( 'a' );
			del.id = "interdel_" + count;
			del.style.cursor = "pointer";
			del.style.display = "none";
			del.alt = "Διαγραφή";
			del.title = "Διαγραφή";
			del.onclick = (function( li ) {
					return function() {
						InterestTag.Delete( li );
					};
				})(li);
			
			edit.appendChild( editimage );
			del.appendChild( deleteimage );
			li.appendChild( d.createTextNode( ' ' ) );
			li.appendChild( edit );
			li.appendChild( d.createTextNode( ' ' ) );
			li.appendChild( del );
			ul.appendChild( li );
			
			++count;
		}
		close.appendChild( closeimg );
		div.appendChild( closeimg );
		div.appendChild( ul );
		Modals.Create( div, 400, 270 );
    },
    is_valid : function( text ) {
    	if ( val.length === 0 || val.indexOf( ',' ) != -1 || val.indexOf( ' ' ) != -1 ) {
        	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον ή να χρησιμοποιήσεις κόμμα (,) ή κενά" );
        	return false;
        }
        return true;
    },
    showLinks : function( id ) {
		if ( !InterestTag.onedit ) {
			g( 'interedit_' + id ).style.display = 'inline';
			g( 'interdel_' + id ).style.display = 'inline';
		}
	},
	hideLinks : function( id ) {
		g( 'interedit_' + id ).style.display = 'none';
		g( 'interdel_' + id ).style.display = 'none';
	},
	Delete : function( li ) {
		alert( li.firstChild.nodeValue );
		Coala.Warm( 'interesttag/delete', { 'text' : li.firstChild.nodeValue } );
		li.parentNode.removeChild( li );
	},
	Edit : function( li ) {
		if ( InterestTag.onedit ) {
			return;
		}
		var text = li.firstChild.nodeValue;
		
		InterestTag.onedit = true;
		li.style.display = "none";

		var form = d.createElement( 'form' );
		form.onsubmit = ( function( prevtext ) {
				return function() {
					var text = input.value;
					if ( !InterestTag.is_valid( text ) ) {
						return;
					}
					//Coala.Warm( 'interesttag/edit', { 'old' : prevtext, 'new' : text } );
					form.parentNode.removeChild( form );
					li.style.display = "inline";
					li.firstChild.nodeValue = text;
					InterestTag.onedit = false;
				};
			} )( text );
				
		var input = d.createElement( 'input' );
		input.type = "text";
		input.value = text;
		input.className = "bigtext";
		
		var imageaccept = document.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var imagecancel = document.createElement( 'img' );
		imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
		
		var editsubmit = d.createElement( 'a' );
		editsubmit.style.cursor = 'pointer';
		editsubmit.onclick = (function( myform ) {
					return function() { 
						myform.onsubmit();
						return false; 
					}
				})( form );
		editsubmit.alt = 'Επεξεργασία';
		editsubmit.title = 'Επεξεργασία';
		editsubmit.appendChild( imageaccept );
		
		var editcancel = d.createElement( 'a' );
		editcancel.style.cursor = 'pointer';
		editcancel.onclick = ( function ( li ) {
				return function() {
					li.parentNode.removeChild( form );
					li.style.display = "inline";
					InterestTag.onedit = false;
				}
			} )( li );
		editcancel.alt = 'Ακύρωση';
		editcancel.title = 'Ακύρωση';
		editcancel.appendChild( imagecancel );
		
		form.appendChild( input );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editsubmit );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editcancel );
		form.appendChild( d.createElement( 'br' ) );
		li.parentNode.insertBefore( form, li );
	}
};
