<?php
    
    function ElementPmFolderLink( PMFolder $folder ) {
        global $user;

        $unreadCount = $user->Count->Unreadpms;

        if ( $folder->Typeid == PMFOLDER_INBOX ) {
            ?><div class="activefolder" alt="Εισερχόμενα" title="Εισερχόμενα" onload="pms.activefolder = this;return false;" id="firstfolder"><a href="" class="folderlinksactive" onclick="pms.ShowFolderPm( this.parentNode, <?php
                echo $folder->Id;
            ?> );return false;">Εισερχόμενα<?php
            if ( $unreadCount ) {
                ?> (<?php
                echo $unreadCount;
                ?>)<?php
            }
            ?></a></div><?php
        }
        else if ( $folder->Typeid == PMFOLDER_OUTBOX ) {
            ?><div class="folder top" alt="Απεσταλμένα" title="Απεσταλμένα" id="sentfolder"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode,<?php
            echo $folder->Id;
            ?> );return false;">Απεσταλμένα</a></div><?php
        }
        else {
            ?><div class="createdfolder folder top" id="folder_<?php
            echo $folder->Id;
            ?>" alt="<?php
            echo htmlspecialchars( $folder->Name );
            ?>" title="<?php
            echo htmlspecialchars( $folder->Name );
            ?>"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '<?php
            echo $folder->Id;
            ?>' );return false;"><?php
            echo htmlspecialchars( $folder->Name );
            ?></a></div><?php
        }
    }

?>
