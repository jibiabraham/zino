<?php
    function UnitPmExpandpm( tInteger $pmid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'pm' );
    	$pmid = $pmid->Get();
    	$pm = new PM( $pmid );
		?>alert( 'pmid is : <?php echo $pm->Id; ?> and its delid is <?php echo $pm->DelId; ?>' );<?php
    	if ( !$pm->IsRead() ) {	
			?>alert( 'making read' );<?php
    		$pm->DelId = 1;	
    		$pm->Save();
    	}
    }
?>
