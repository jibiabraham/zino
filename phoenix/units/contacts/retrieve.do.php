<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        $provider = $provider->Get();
        $username = $username->Get();
        $password = $password->Get();
        
        $libs->Load( 'contacts/contacts' );
        $ret = GetContacts( $username, $password, $provider );
        
        if( !is_array( $ret ) ){
            ?>setTimeout( function(){
                contacts.backToLogin();
            }, 3000 );<?php
            return;
        }
        $contactsInZino = 1;
        $mailfinder = new UserProfileFinder();
        foreach( $ret as $contactMail ){
            ?>contacts.addContactInZino( '<?php
            echo "http://images2.zino.gr/media/3890/140401/140401_100.jpg";
            ?>', '<?php
            echo "ted";
            ?>', '<?php
            echo $contactMail;
            ?>' );
            <?php
            $contactsInZino++;
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                ?> επαφή σου έχει Zino. Πρόσθεσέ την στους φίλους σου...<?php
            }
            else{
                ?> επαφές σου έχουν Zino. Πρόσθεσέ τις στους φίλους σου...<?
            }
        ?>" );
        setTimeout( function(){
                contacts.previwContactsInZino();
            }, 3000 );<?php
    }
?>
