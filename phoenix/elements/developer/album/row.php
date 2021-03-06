<?php

    class ElementDeveloperAlbumRow extends Element {
        public function Render( $album ) {
            global $rabbit_settings;

            if ( $album->Id == $album->Owner->Egoalbumid ) {
                $albumname = 'Εγώ';
            }
            else {
                $albumname = $album->Name;
            }
            ?><li id="<?php
            echo $album->Id;
            ?>"><?php
                    if ( $album->Mainimage->Exists() && $album->Mainimageid !== 0) {
                        Element( 'image/view', $album->Mainimageid , $album->Mainimage->Userid , $album->Mainimage->Width , $album->Mainimage->Height , IMAGE_CROPPED_100x100 , '' , $albumname , '' , true , 50 , 50 , 0 ); 
                    }
                    else {
                        ?><span class="imageview"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>anonymous100.jpg" alt="<?php
                        echo htmlspecialchars( $albumname );
                        ?>" title="<?php
                        echo htmlspecialchars( $albumname );
                        ?>" style="width:50px;height:50px" /></span><?php
                    }
                ?><span class="albumname">
                    <h3><?php
                    echo htmlspecialchars( $albumname );
                    ?></h3>
                </span>
                <div class="fade"/>
            </li><?php
        }
    }
?>
