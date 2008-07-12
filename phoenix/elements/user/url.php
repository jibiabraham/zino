<?php
    class ElementUserURL extends Element {
        public function Render( $theuser ) {
            global $xc_settings;
            
            if ( !is_object( $theuser ) ) {
                return;
            }
            if ( !( $theuser instanceof User ) ) {
                return;
            }
            
            echo str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] );
        }
    }
?>
