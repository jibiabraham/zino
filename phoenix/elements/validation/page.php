<?php
    class ElementValidationPage extends Element {
        public function Render( tInteger $userid ) {
            global $user;
			$userid = $userid->Get();
            ?><p>Ο λογαριασμός σου δεν έχει ενεργοποιηθεί ακόμη. Θα πρέπει να χρησιμοποιήσεις τον σύνδεσμο στο e-mail σου για να τον ενεργοποιήσεις. Δεν έλαβες κάποιο e-mail? Έλεγξε τον φάκελο junk ή 
			<form action="do/user/revalidate" method="post">
				<input type="submit" value="ζήτησέ μας να σου το ξαναστείλουμε" />
				<input name="userid" type="hidden" value="<?php
					echo $userid;
				?>" />
			</form>
			</p><?php
        }

    }
?>
