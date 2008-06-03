<?php
	
	function ElementCommentView( $comment , $indent , $numchildren ) {
		global $water;
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );

		?><div id="comment_<?php
		echo $comment->Id;
		?>" class="comment" style="border-color:#dee;<?php
		if ( $indent > 0 ) {
			?>margin-left:<?php
			echo $indent*20;
			?>px;<?php
		}
		?>">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $comment->Since;
				?></span><?php
				if ( $user->Id == $comment->User->Id || $user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) {
					?><a href="" onclick="return false" title="Διαγραφή"></a><?php
				}
			?></div>
			<div class="who">
				<a href="?p=user&amp;name=<?php
					echo $comment->User->Subdomain;
					?>"><?php
					Element( 'user/avatar' , $comment->User , 100 , 'avatar' , '' , true , 50 , 50 );
					echo $comment->User->Name;
				?></a> είπε:
			</div>
			<div class="text"><?php
				echo $comment->Text;
			?></div><?php
			if ( $indent <= 50 && $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
				?><div class="bottom">
					<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
				</div><?php
			}
			?><div id="<?php
			echo $comment->Id;
			?>_children" style="display:none"><?php
			echo $numchildren;
			?></div>
		</div><?php
	}
?>
