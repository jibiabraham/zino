<?php
    class ElementFrontpageNotificationComet extends Element {
        public function Render() {
            global $page;
            global $user;
            
            die( 'comet' );
            ob_start();
            ?>Comet.Subscribe( 'FrontpageNotificationNew<?php
            echo $user->Id;
            ?>' );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
