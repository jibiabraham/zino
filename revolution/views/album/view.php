<? if ( $album === false || $album[ 'delid' ] == 1 ): ?>
<album id="<?= $album[ 'id' ] ?>" deleted="yes">
<? else: ?>
<album id="<?= $album[ 'id' ] ?>"
<? if ( !empty( $album[ 'egoalbum' ] ) ): ?>
 egoalbum="yes"
<? endif; ?>
>
    <author id="<?= $user[ 'id' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
    </author>
    <? if ( isset( $photos ) ): 
       include 'views/photo/listing.php';
       endif;
   endif; ?>
</album>
