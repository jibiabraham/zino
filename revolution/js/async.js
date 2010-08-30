var Async = {
    Go: function( href ){
        var link = href.length ? href : './';
        if( typeof( User ) != 'string' ){
            window.location = href;
        }
        $( '#world' ).stop( 1 ).fadeTo( 100, 0.5 );
        axslt( $.get( link ), 'call:html', function(){
            $( '#world' ).stop( 1 ).fadeTo( 0, 1 );
            //Close Chat
            if( Chat.Visible ){
                Chat.Toggle();
            }

            // Run Unload Function from previous Master Template
            if( typeof( Routing[ window.MasterTemplate ].Unload ) == 'function' ){
                Routing[ window.MasterTemplate ].Unload();
            }
            var world = $( this ).find( '#world' ).andSelf().filter( '#world' );
            var title = $( this ).find( 'title' ).text();
            var MasterTemplate = world.attr( 'class' ).split( '-' );
            MasterTemplate = MasterTemplate[ 1 ] + '.' + MasterTemplate[ 2 ];

            // Replace the world
            $( '#world' ).empty().removeClass().addClass( world.attr( 'class' ) );
            world.children().appendTo( '#world' );
            //set new Title, html id and hash
            $( 'title' ).text( title );
            $( 'html' ).attr( 'id', MasterTemplate.split( '.' ).join( '-' ) );
            window.location.hash = href;
            Async.hash = window.location.hash.substr( 1 );
            //set new Master Template and run
            window.MasterTemplate = MasterTemplate;
            Routing[ MasterTemplate ].Init();
            Chat.BindClick();
            $( '.time:not(.processed)' ).load();
        } );
    },
    Init: function(){
        Async.hash = window.location.hash.substr( 1 );
        setInterval( function(){
            if( window.location.hash.substr( 1 ) != Async.hash ){
                Async.hash = window.location.hash.substr( 1 );
                Async.Go( Async.hash );
            }
        }, 100 );
        
        if( window.location.hash.length ){
            Async.Go( window.location.hash.substr( 1 ) );
        }
        $( 'a:not(:data(events)):not([href^=http])' ).live( 'click', function( e ){
            if( typeof( $( this )[ 0 ].onclick ) == 'function' ){
                return;
            }
            if( e.ctrlKey || e.shiftKey ){
                return;
            }
            var path = window.location.href.split( '#' )[ 0 ];
            if( path[ path.length - 1 ] == '/' ){
                path = path.substr( 0, path.length - 1 );
            }
            if( path != Generator ){
                window.location = Generator + '#' + $( this ).attr( 'href' );
            }
            Async.Go( $( this ).attr( 'href' ) );
            return false;
        });
    }
};