<?php
    class ElementDeveloperUserSettingsPersonalRelationship extends Element {
        public function Render( $selected , $gender ) {        
            ?><select><?php
            $statuses = array( '-', 'single', 'relationship', 'casual', 'engaged', 'married' );
            foreach ( $statuses as $status ) {
                ?><option value="<?php
                echo $status;
                ?>"<?php
                if ( $selected == $status ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'developer/user/trivial/relationship' , $status , $gender );
                ?></option><?php
            }
            ?></select><?php
        }
    }
?>
