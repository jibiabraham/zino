var Suggest = {
	allowHover : true, // Allow onmouseover to select a li
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds the suggestions that we have already received from the server
    list : {
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds all the requests we have done to the server
    requested : { 
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
    },
    type2int : function( type ) {
		switch( type ) {
			// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
			case 'hobbies':
				return 1;
			case 'movies':
				return 2;
			case 'books':
				return 3;
			case 'songs':
				return 4;
			case 'artists':
				return 5;
			case 'games':
				return 6;
			case 'shows':
				return 7;
			default:
				return -1;
		}
	},
    inputMove : function( event, type ) {
        var ul = $( 'div.' + type + ' ul' );
        if ( ul.css( "display" ) == "none" ) {
			return;
		}
        var lis = ul.find( 'li.selected' );
		var text = $( 'div.' + type + ' input' ).val();
        if ( event.keyCode == 40 ) { // down
            if ( lis.length == 0 ) {
                ul.find( 'li:first' ).addClass( 'selected' );
                return;
            }
            lis.removeClass( 'selected' ).next().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( "Suggest.allowHover = true", 15 );
        }
        else if ( event.keyCode == 38 ) { // up
            if ( lis.length == 0 ) {
                ul.find( 'li:last' ).addClass( 'selected' );
                return;
            }
            ul.find( 'li.selected' ).removeClass( 'selected' ).prev().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( "Suggest.allowHover = true", 15 );
        }
		else if ( event.keyCode == 33 ) { // PageUp
		}
		else if ( event.keyCode == 34 ) { // PageDown
		}
        else if ( event.keyCode == 27 ) { // escape
            ul.hide();
        }
        else if ( event.keyCode == 13 ) { // enter
            if ( lis.length == 0 ) {
                ul.css( 'display', 'none' );
                return;
            }
            $( 'div.' + type + ' input' ).attr( 'value', lis.text() );
            ul.hide();
        }
		else {
			var suggestions = $.grep( Suggest.list[ type ], function( item, index ) {
		                return( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
			Suggest.suggestCallback( type, suggestions, false );
			
			if ( suggestions.length > 40 || $.inArray( text, Suggest.requested[ type ] ) !== -1 ) {
				return;
			}

			Coala.Cold( 'user/settings/tags/suggest', { 'text' : text,
														'type' : type,
														'callback' : Suggest.suggestCallback
		                                           } );
			Suggest.requested[ type ].push( text );
		}
    },
	suggestCallback : function( type, suggestions, callbacked ) {
		/*if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}*/
		
		if ( !callbacked ) {
			$( 'div.' + type + ' ul li' ).remove();
		}

		// Marks duplicate entries
		var sugLength = suggestions.length;
		for( var i=0;i<suggestions.length;++i ) {
		    if ( $.inArray( suggestions[i], Suggest.list[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[i] );
		    }
		    else if ( callbacked ) { // If callbacked is set to true, then the current suggestion always exists in the options. It was added the first time when callbacked was false
		        suggestions[i] = '';
		        --sugLength;
		    }
		}
		
		$( 'div.' + type + ' ul' ).show();
		
		var text = $( 'div.' + type + ' input' ).val();
		for( var i in suggestions ) {
		    if ( suggestions[i] !== '' ) {
				var li = document.createElement( 'li' );
				li.onmousedown = function( i ) {
					return function() {
						$( 'div.' + type + ' input' ).attr( 'value', suggestions[ i ] );
						ul.css( 'display', 'none' );
					}
				}( i );
				li.onmousemove = function() {
					if ( !Suggest.allowHover ) {
						return;
					}
					$( 'div.' + type + ' ul li.selected' ).removeClass( 'selected' );
					this.className = "selected";
				};

				var b = document.createElement( 'b' );
				b.appendChild( document.createTextNode( suggestions[ i ].substr( 0, text.length ) ) );
				li.appendChild( b );
				li.appendChild( document.createTextNode( suggestions[ i ].substr( text.length ) ) );
			
				$( 'div.' + type + ' ul' ).append( li );
			}
		}
	}
};

$( function() {
    $( 'div.add input' ).unbind(); // prevent onkeydown event from settings.js
    $( 'div.add ul li' ).remove();
} );