<?php
    function UnitUserProfileEasyuploadadd( tInteger $imageid , tInteger $albumid ) {
        global $libs;
        global $user;

        $libs->Load( 'image/image' );
        $imageid = $imageid->Get();
        $image = New Image( $imageid );
        if ( $image->Userid == $user->Id ) {
            $albumid = $albumid->Get();
            $image->Albumid = $albumid;
            $image->Save();
            if ( $user->Egoalbumid == $albumid ) {
                ?>var newli = document.createElement( 'li' );
                var newlink = document.createElement( 'a' );
                $( newlink ).attr( 'href' , '?p=photo&id=<?php
                echo $imageid;
                ?>' ).html( <?php
                ob_start();
                Element( 'image/view' , $imageid , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , 0 );
                echo w_json_encode( ob_get_clean() );
                ?> );
                $( newli ).append( newlink );
                
                $( 'div#profile div.main div.photos ul.plist li.addphoto' ).after( newli );<?php
            }
            ?>$( 'div#easyphotoupload div.modalcontent div.uploadsuccess div' ).fadeIn( 400 , function() {
                $( this ).fadeOut( 1000, function() {<?php
					if ( $user->Id == 872 || $user->Id == 1 ) {
						?>alert(1);<?php
					}?>
                    $( '#easyphotoupload').jqmHide();
                } );
            } );<?php
        }
    }
?>
