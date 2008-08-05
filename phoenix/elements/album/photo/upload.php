<?php
    
    class ElementAlbumPhotoUpload extends Element {
        public function Render( tInteger $albumid , tInteger $typeid ) {
            global $water;
            global $user;
            global $rabbit_settings;
            global $page;
            
            $page->SetTitle( 'Ανέβασε μια εικόνα' );
            $water->Disable();
            $page->SetWaterDump( false );
            //typeid is 0 for album photo uploads and 1 for avatar uploads at settings
            $album = New Album( $albumid->Get() );
            if ( $typeid->Get() == 2 && UserBrowser() == "MSIE" ) {
                $page->AttachInlineScript( "document.body.style.color = '#ffdf80';" );
            }
            if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                ?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
                        <input type="hidden" name="albumid" value="<?php
                        echo $album->Id;
                        ?>" />
                        <input type="hidden" name="typeid" value="<?php
                        echo $typeid->Get();
                        ?>" />
                        <div class="colorlink">
                            Νέα φωτογραφία
                        </div>
                        <input type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
                        <input type="submit" value="upload" style="display:none" />
                    </form>
                    <div id="uploadingwait">
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" />
                        Παρακαλώ περιμένετε                
                    </div><?php    
            }
            return array( 'tiny' => true );
        }
    }
?>
