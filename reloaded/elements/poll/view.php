<?php

    function ElementPollView( tInteger $id, tBoolean $oldcomments ) {
        global $libs;
        global $page;
        global $user;

        if ( !$user->Exists() ) {
            return Redirect( 'register' );
        }

        $libs->Load( 'poll' );
        $libs->Load( 'comment' );

        $page->AttachStylesheet( 'css/poll.css' );
        $page->AttachStylesheet( 'css/pollbox.css' ); 
        $page->AttachScript( 'js/poll.js' );
        $page->SetTitle( $poll->Question );

        $poll = new Poll( $id->Get() );

        ?><div class="pollheader">
			<a href="user/<?php
            echo $poll->User->Username();
			?>" onmouseover="g( 'pollcreatorlink' ).style.textDecoration='underline';" onmouseout="g( 'pollcreatorlink' ).style.textDecoration='none';" class="usericon"><?php

			Element( "user/icon", $poll->User, false, false );

            ?></a><h1><?php
            echo htmlspecialchars( $poll->Question );
            ?></h1><br /><?php

            ?>από <a href="user/<?php
            echo $poll->User->Username();
            ?>" id="pollcreatorlink"><?php
            echo $poll->User->Username();
            ?></a>, <?php
            echo $poll->NumComments;
            ?> σχόλι<?php
            if ( $poll->NumComments == 1 ) {
                ?>o<?php
            }
            else {
                ?>α<?php
            }
            if ( $poll->NumComments > 50 ) {
                ?>&nbsp;<a href="?p=poll&amp;id=<?php
                echo $poll->Id;
                ?>&amp;oldcomments=yes">(προβολή όλων)</a><?php
            }
        ?></div>

        <div class="poll"><?php
            Element( "poll/box", $poll );
        ?></div><?php

        ?><div class="comments" id="comments"><?php	
            $search = New Search_Comments();
            $search->SetFilter( 'typeid', 3 ); // 0: article, 1: userspace
            $search->SetFilter( 'page', $poll->Id ); //show all comments of an article 
            $search->SetFilter( 'delid', 0 ); // do not show deleted comments
            $search->SetSortMethod( 'date', 'DESC' ); //sort by date, newest shown first
            if ( $oldcomments->Get() ) {
                $search->SetLimit( 10000 );
            }
            else {
                $search->SetLimit( 50 );  //show no more than 50 comments
            }
            $comments = $search->GetParented(); //get comments
            
            Element( 'comment/import' );
            Element( 'comment/list' , $comments , 0 , 0 );
            Element( 'comment/reply', $poll, 3 );

        ?></div><?php
    }

?>
