<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email, tInteger $step ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            $libs->Load( 'user/profile' );
            $libs->Load( 'relation/relation' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $email = $email->Get();
            $email = urldecode( $email );
            $step = $step->Get();            
            if ( $step != 1 && $step != 2 ) {
                $step = 0;
            }
            
            $finder = new ContactFinder();
            $contactsLoaded = $finder->FindByUseridAndMail( $user->Id, $email );
            
            if( count( $contactsLoaded ) == 0 ) {
                ?><p>Παρουσιάστηκε κάποιο πρόβλημα.</p><?php
                return;
            }
            
            $all_emails = array();
            foreach ( $contactsLoaded as $sample ) {
                $all_emails[] = $sample->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $zino_emails = $mailfinder->FindAllUsersByEmails( $all_emails );
            
            if ( $step == 1 ) { //step 1:send invites to user that are already in zino
            
                $relationfinder = new FriendRelationFinder();//find already zino friends
                $userRelations = $relationfinder->FindByUser( $user );
                $real_friendsN = 0;
                $zino_friends = array();
                foreach ( $res as $relation ) {
                    $zino_friends[ $relation->Friend->Id ] = true;
                }
                
                foreach ( $contactsLoaded as $sampe ) {
                    if ( $zino_friends[ $sampe->Mail ] == true ) {
                        $real_friendsN++;
                    }
                }
                echo $real_friendsN . " " . $friendsN;
            
                $friendsN = count( $zino_emails );
                if ( ( $friendsN - $real_friendsN ) <= 0 ) {
                    $step = 2;                    
                    /*?><h3>Κανένας φίλος σου δεν είναι μέλος στο zino.Πήγαινε στο επόμενο βήμα για να τους προσκαλέσεις!</h3><?php
                    ?><input type="submit" value="Επόμενο βήμα" /><?php*/
                }
                else {            
                    ?><h3>Αυτοί οι φίλοι σου είναι ήδη στο zino!</h3><?php
                    ?><form method="post" action="do/contacts/addfriends"><?php
                    //echo count( $zino_emails );
                    foreach ( $zino_emails as $key=>$val ) {
                        $friend = new User( $val ); 
                        if ( $zino_friends[ $friend->Id ] == NULL ) {
                            ?><p><?php
                            ?><input type="checkbox" name="approved[]" value="<?php echo $friend->Id; ?>" /> <?php 
                            echo $friend->Name . " - " . $friend->Profile->Email;                    
                            ?></p><?php
                        }
                    }
                    ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                      <input type="hidden" name="email" value="<?php echo $email; ?>" />
                      </form>
                    <?php
                }
            }
            
            if ( $step == 2 ) { //step 2 - send invites to non zino users      
                ?><h3>Στείλε προσκλήσεις στους φίλους σου που δεν είναι μέλη στο Ζινο.</h3><?php
                ?><form method="post" action="do/contacts/invite"><?php
                //echo count( $res );
                foreach ( $contactsLoaded as $sample ) {
                    if ( $zino_emails[ $sample->Mail ] == NULL  ) {
                        ?><p><?php
                        ?><input type="checkbox" name="approved[]" value="<?php echo $sample->Mail; ?>" /> <?php 
                        echo $sample->Mail;                        
                        ?></p><?php
                    }
                }
                ?><input type="submit" value="Στείλε τις προσκλήσεις!" />
                  <input type="hidden" name="email" value="<?php echo $email; ?>" />
                  </form><?php
            }
            return;
        }
    }
?>
