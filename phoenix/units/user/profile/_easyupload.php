<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> ).css( 'padding' , '0' );
        $( 'div#easyphotoupload div.modalcontent div ul li' ).click( function() {
            if ( !previousSelection ) {
                var previousSelection = $( 'div#easyphotoupload div.modalcontent div ul li' );
            }
            $( previousSelection ).removeClass( 'selected' );
            $( this ).addClass( 'selected' );
            previousSelection = $( this )[ 0 ];
            var albumname = $( this ).find( 'span img' ).attr( 'alt' );
            var username = GetUsername();
            if ( albumname.toLowerCase() == username.toLowerCase() ) {
                albumname = 'Εγώ';
            }
            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( albumname ) );
            $( 'div#easyphotoupload div.modalcontent div.uploaddiv' ).children().attr( '<?php
                if ( UserBrowser() == 'MSIE' ) {
                    $attr = "src";
                }
                else {
                    $attr = "data";
                }
                echo $attr;
            ?>', function() {
                var arr = $( this ).<?php
                    echo $attr;
                ?>;
                alert( arr );
            } );
            
        } );<?php
    }
?>
