var PhotoView = {
    Title: {
        Empty: 'Γράψε τίτλο για τη φωτογραφία',
        Rename: function( id, title ){
            $.post( 'index.php?resource=photo&method=update', {
                id: id,
                title: title
            });     
        },
        CorrectWidth: function(){
            $( '.title span' ).text( $( '.title input' ).val() );
            var width = $( '.title span' ).width();
            if( width < 300 ){
                $( '.title input' ).width( 300 );
            }
            else{
                $( '.title input' ).width( width + 30 );
            }
        },
        Init: function() {
            if( typeof( User ) != 'string' || $( '.contentitem .details a.username' ).text() != User ){
                return;
            }
            PhotoView.Title.Title = $( '.title input' ).val();
            if ( PhotoView.Title.Title === '' ){
                $( '.title input' ).addClass( 'empty' ).val( PhotoView.Title.Empty );
            }
            PhotoView.Id = $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ];
            PhotoView.Title.CorrectWidth();
            $( '.title input' ).mouseover( function(){
                if( !$( this ).hasClass( 'focus' ) ){
                    $( this ).addClass( 'hover' );
                }
            }).mouseout( function(){
                $( this ).removeClass( 'hover' );
            }).focus( function(){
                if( $( this ).hasClass( 'empty' ) ){
                    PhotoView.Title.Title = '';
                    $( this ).val( '' );
                }
                else{
                    PhotoView.Title.Title = $( this ).val();
                }
                $( this ).removeClass( 'hover' ).removeClass( 'empty' ).addClass( 'focus' )[ 0 ].select();
                PhotoView.Title.Selected = false;
            }).blur( function(){
                $( this ).removeClass( 'focus' );
                PhotoView.Title.Title = $( this ).val();
                if( PhotoView.Title.Title === '' ) {
                    $( this ).addClass( 'empty' ).val( PhotoView.Title.Empty );
                }
                else{
                    $( this ).removeClass( 'empty' );
                }
                PhotoView.Title.Rename( PhotoView.Id, PhotoView.Title.Title );
            }).mouseup( function(){
                if( PhotoView.Title.Selected ){
                    return true;
                }
                PhotoView.Title.Selected = true;
                return false;
            }).keyup( function( event ){
                if( event.which == 13 ){
                    $( this ).blur();
                }
                if( event.which == 27 ){
                    $( this ).val( PhotoView.Title.Title );
                    $( this ).blur();
                }
                PhotoView.Title.CorrectWidth();
            });
        }
    },
    Remove: { 
        Remove: function( id ) {
            $.post( 'index.php?resource=photo&method=delete', {
                id: id
            }, function(){
                window.location = 'photos/' + User;
            });     
        },
        Init: function(){
            if( typeof( User ) != 'string' || $( '.contentitem .details a.username' ).text() != User ){
                return;
            }
            $( '#deletebutton' ).click( function(){
                if ( confirm( 'Θέλεις να διαγράψεις την εικόνα;' ) ) {
                    PhotoView.Remove.Remove( $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ] );
                }
            });
        }
    },
    Init: function(){
        PhotoView.Title.Init();
        PhotoView.Remove.Init();
		ItemView.Init( 2 );
        $( document ).bind( 'keydown', { combi: 'left', disableInInput: true }, PhotoView.LoadPrevious );
        $( document ).bind( 'keydown', { combi: 'right', disableInInput: true }, PhotoView.LoadNext );
        $( '.image img.maincontent' ).click( function(){ // Photo tagging guy, check that ( --ted )
            PhotoView.LoadNext();
        });
        PhotoView.Tag.Init();
    },
    Tag: {
        Init: function(){
            $( '.image .tag .imagecontainer' ).hover( function(){
                $( this ).parent().fadeTo( 0, 1 ).siblings( '.tag' ).hide().end();
                $( '.image img.maincontent' ).stop( 1 ).fadeTo( 100, 0.4 );
            }, function( e ){
                $( '.tag' ).show().fadeTo( 0, 0 );
                $( '.image img.maincontent ' ).stop( 1 ).fadeTo( 100, 1 );
            }).click( function(){
                window.open( 'users/' + $( this ).siblings( '.namecontainer' ).children( '.name' ).text() );
            });
            $( '.image .tag .namecontainer.inside' ).hover( function(){
                $( this ).siblings( '.imagecontainer' ).mouseover();
            }, function(){
                $( this ).siblings( '.imagecontainer' ).mouseout();
            });
        }
    },
    LoadNext: function( evt ) {
        var $next = $( '.navigation .nextid' );
        if ( $next.length ) {
            $( '.breadcrumb .nav.next' ).css( { opacity: 1 } );
            Kamibu.Go( 'photos/' + $next.text() );
        }
        return false;
    },
    LoadPrevious: function() {
        var $previous = $( '.navigation .previousid' );
        if ( $previous.length ) {
            $( '.breadcrumb .nav.prev' ).css( { opacity: 1 } );
            Kamibu.Go( 'photos/' + $previous.text() );
        }
        return false;
    }
};
