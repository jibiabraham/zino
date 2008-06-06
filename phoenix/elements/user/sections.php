<?php

	function ElementUserSections( $section , $theuser ) {
		?><div class="usersections">
			<a href="?p=user&amp;subdomain=<?php
				echo $theuser->Subdomain;
				?>"><?php
				Element( 'user/avatar' , $theuser , 150 , '' , '' );
				?><span class="name"><?php
				echo $theuser->Name;
				?></span>
			</a>
			<ul>
				<li<?php
				if ( $section == 'album' ) {
					?> class="selected"<?php
				}
				?>><a href="<?php
				Element( 'user/url' , $theuser );
				?>albums">Albums</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'poll' ) {
					?> class="selected"<?php
				}
				?>><a href="<?php
				Element( 'user/url' , $theuser );
				?>polls">Δημοσκοπήσεις</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'journal' ) {
					?> class="selected"<?php
				}
				?>><a href="<?php
				Element( 'user/url' , $theuser );
				?>journals">Ημερολόγιο</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'space' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=space&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Χώρος</a></li>
				<li>·</li>
				<li<?php
				if ( $section == 'relations' ) {
					?> class="selected"<?php
				}
				?>><a href="?p=relations&amp;username=<?php
				Element( 'user/subdomain' , $theuser );
				?>">Φίλοι</a></li>
			</ul>
		</div><?php
	}
?>
