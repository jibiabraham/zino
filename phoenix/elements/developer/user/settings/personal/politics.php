<?php

    class ElementDeveloperUserSettingsPersonalPolitics extends Element {
        public function Render( $selected , $gender ) {
            ?><select><?php
                $politics = array( '-' , 'right' , 'left' , 'center' , 'radical right' , 'radical left' , 'center left', 'center right', 'nothing', 'anarchism', 'communism', 'socialism', 'liberalism', 'green' );
                foreach ( $politics as $politic ) {
                    ?><option value="<?php
                    echo $politic;
                    ?>"<?php
                    if ( $selected == $politic ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'developer/user/trivial/politics' , $politic , $gender );
                    ?></option><?php
                }
                ?>
            </select><?php
        }
    }
?>
