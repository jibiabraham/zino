<?php

    class ElementUserSettingsPersonalAvatar extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            Element( 'user/avatar' , $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height , $user->Name , 150 , 'avie' , '' , false , 0 , 0 );
            ?><div class="changeavatar">
				<a href="">Αλλαγή εικόνας</a>
            </div>
            <div id="avatarlist">
				<div class="title"><h3>Επέλεξε μια φωτογραφία...</h3></div>
                <div class="uploaddiv">
                    <?php
                    if ( UserBrowser() == 'MSIE' ) {
                        ?>
                        <iframe frameborder="0" style="height:50px" src="?p=upload&amp;albumid=<?php
                        echo $user->Egoalbumid;
                        ?>&amp;typeid=1" class="uploadframe" id="uploadframe">
                        </iframe>
                        <?php
                    }
                    else {
                        ?>
                        <object style="height:50px" data="?p=upload&amp;albumid=<?php
                        echo $user->Egoalbumid;
                        ?>&amp;typeid=1" class="uploadframe" id="uploadframe" type="text/html">
                        </object>
                        <?php
                    }
                    ?>
                </div><?php
                $egoalbum = New Album( $user->Egoalbumid );
                $finder = New ImageFinder();
                ?><ul><?php    
                if ( $egoalbum->Numphotos > 0 ) {
                    $images = $finder->FindByAlbum( $egoalbum , 0 , $egoalbum->Numphotos );
                    foreach ( $images as $image ) {    
                        ?><li><?php
                        Element( 'user/settings/personal/photosmall' , $image );
                        ?></li><?php
                    }
                }
                ?></ul>
            </div><?php
        }
    }
?>
