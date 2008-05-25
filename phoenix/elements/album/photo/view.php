<?php
	
	function ElementAlbumPhotoView( tInteger $id ) {
		global $user;
		global $page;
		global $water;
		
		$id = $id->Get();
		$image = New Image( $id );
		
		if( !$image->Exists() ) {
			?>Η φωτογραφία δεν υπάρχει<?php
		}
		else {
			Element( 'user/sections', 'album' , $image->User );
			if ( $image->IsDeleted() ) {
				?>Η φωτογραφία έχει διαγραφεί<?php
			}
			else {
				if ( $image->Name != "" ) {
					$title = htmlspecialchars( $image->Name );
				}
				else {
					$title = htmlspecialchars( $image->Album->Name );
				}
				$page->SetTitle( $title );
				$size = $image->ProportionalSize( 700  , 600 );
				?><div id="photoview">
					<h2><?php
					echo htmlspecialchars( $image->Name );
					?></h2>
					<span>στο album</span> <a href="?p=album&amp;id=<?php
					echo $image->Album->Id;
					?>"><?php
					if ( $image->Album->Id == $image->User->Egoalbumid ) {
						?>Φωτογραφίες μου<?php
					}
					else {
						echo htmlspecialchars( $image->Album->Name );
					}
					?></a>
					<dl><?php
						if ( $image->Numcomments > 0 ) {
							?><dd class="commentsnum"><?php
							echo $image->Numcomments;
							?> σχόλι<?php
							if ( $image->Numcomments == 1 ) {
								?>ο<?php
							}
							else {
								?>α<?php
							}
							?></dd><?php
						}
						?><dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd>
					</dl><?php
					if ( $image->User->Id == $user->Id || $user->HasPermission( PERMISSION_IMAGE_DELETE_ALL ) ) {
						?><div class="owner">
							<div class="edit"><a href="" onclick="PhotoView.Rename( '<?php
							echo $image->Id;
							?>' , <?php
							echo htmlspecialchars( w_json_encode( $image->Album->Name ) );
							?> );return false;"><?php
							if ( $image->Name == '' ) {
								?>Όρισε όνομα<?php
							}
							else {
								?>Μετονομασία<?php
							}
							?></a></div>
							<div class="delete"><a href="" onclick="PhotoView.Delete( '<?php
							echo $image->Id;
							?>' );return false;">Διαγραφή</a></div><?php
							if ( $image->Album->Mainimage != $image->Id ) {
								?><div class="mainimage"><a href="" onclick="PhotoView.MainImage( '<?php
								echo $image->Id;
								?>' );return false;">
								Ορισμός προεπιλεγμένης</a>
								</div><?php
							}
						?></div><?php
					}
					?><div class="eof"></div>
					<div class="thephoto"><?php
						Element( 'image' , $image , $size[ 0 ] , $size[ 1 ] , '' , '' , $title , $title );
					?></div>
					<div class="photothumbs"><?php
						$finder = New ImageFinder();
						$photos = $finder->FindAround( $image , 7 );
						$water->Trace( 'numphotos is: ' . count( $photos ) );
						$pivot = $i = 0;
						foreach ( $photos as $photo ) {
							if ( $photo->Id == $image->Id ) {
								$pivot = $i;
								break;
							}
							++$i;
						}
				        ?><div class="left arrow">
				            <a href="" class="nav"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" onmouseover="Hover( this );"  onmouseout="Unhover( this );" /></a>
				        </div>
				        <div class="right arrow">
				            <a href="" class="nav"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" onmouseover="Hover( this );" onmouseout="Unhover( this );" /></a>
				        </div>
				        <ul><?php	
							$water->Trace( 'pivot is: ' . $pivot );
							//die( 'pivot is ' . $pivot );
							if ( $pivot > 0 ) {
								for ( $i = $pivot - 1; $i >= 0 ; --$i ) {
									$size = $photos[ $i ]->ProportionalSize( 150 , 150 );
									?><li><span><a href="?p=photo&amp;id=<?php
									echo $photos[ $i ]->Id;
									?>"><?php
									Element( 'image' , $photos[ $i ] , $size[ 0 ] , $size[ 1 ] , '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' );
									?></a></span></li><?php
								}
							}
							?><li class="selected" style="width:150px">
								<a href="?p=photo&amp;id=<?php
								echo $photos[ $pivot ]->Id;
								?>"><?php
								$size = $photos[ $pivot ]->ProportionalSize( 150 , 150 );
								Element( 'image' , $photos[ $pivot ] , $size[ 0 ] , $size[ 1 ] , '' , $photos[ $pivot ]->Name , $photos[ $pivot ]->Name , '' );
								?></a>
							</li><?php
							if ( $pivot < 7 && $pivot != 0 ) {						
								for ( $i = $pivot + 1; $i < 7; ++$i ) {
									$size = $photos[ $i ]->ProportionalSize( 150 , 150 );
									?><li><span><a href="?p=photo&amp;id=<?php
									echo $photos[ $i ]->Id;
									?>"><?php
									Element( 'image' , $photos[ $i ] , $size[ 0 ] , $size[ 1 ] , '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' );
									?></a></span></li><?php
								}
							}
						?></ul>
					</div>
					<div class="comments"><?php
						Element( 'comment/list' );
					?></div>
				</div><?php
			}
		}
		?><div class="eof"></div><?php
	}
?>
