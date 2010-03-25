 var Chat = {
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;
            online.css( { opacity: 1 } );
            online = online[ 0 ];
            online.innerHTML = '<li class="selected">Zino</li>';
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                online.innerHTML += '<li>' + name + '</li>';
            }
        }, 'xml' );
     },
     Init: function () {
        $( '.col2 div' ).hide(); 
        $( '.col2' )[ 0 ].innerHTML +=
        '<div style="background-color: white; position: fixed; top: 5px; bottom: 5px; right: 5px; left: 5%; border: 1px solid black; -moz-border-radius: 5px 5px 5px 5px; z-index: 10;">'
            + '<div style="float: right; width: 10%; height: 100%; border-left: 1px solid black;">'
                + '<ol id="onlineusers" style="padding: 5px; margin: 0pt; list-style: none outside none;">'
                + '<li>Κοιτάμε ποιος είναι online...</li>'
                + '</ol>'
            + '</div>'
            + '<div style="height: 100%; margin-right: 10%;">'
                + '<div style="padding: 5px;">Γεια σας παιδιά!</div>'
            + '</div>'
        + '</div>';
        Chat.GetOnline();
     },
 };
