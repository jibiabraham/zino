<?php
    class ElementFrontpageImageComet extends Element {
        public function Render() {
            global $page;
            die( 'calling comet element' );
            ob_start();
            
            ?>Comet.Subscribe( 'FrontpageImageNew' );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
