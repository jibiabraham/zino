<?php
    class ControllerComment {
        public static function Create( $text, $typeid, $itemid, $parentid ) {
            if ( strlen( trim( $text ) ) == 0 ) {
                throw new Exception( 'Cannot post empty comment' );
            }
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to post a comment' ); // must be logged in to leave comment
            clude( 'models/types.php' );
            clude( 'models/db.php' );
            switch ( $typeid ) {
                case TYPE_POLL:
                    clude( 'models/poll.php' );
                    $target = Poll::Item( $itemid );
                    break;
                case TYPE_PHOTO:
                    clude( 'models/photo.php' );
                    $target = Photo::Item( $itemid );
                    break;
                case TYPE_USERPROFILE:
                    clude( 'models/user.php' );
                    $target = User::Item( $itemid );
                    break;
                case TYPE_JOURNAL:
                    clude( 'models/journal.php' );
                    $target = Journal::Item( $itemid );
                    break;
                case TYPE_SCHOOL:
                    // clude( 'models/school.php' );
                    // break;
                default:
                    // comment somewhere unknown
            }            
            $target !== false or die(); // cannot leave a comment on non-existing object
            clude( 'models/comment.php' );
            $comment = Comment::Create( $_SESSION[ 'user' ][ 'id' ], $text, $typeid, $itemid, $parentid );
            clude( 'models/user.php' );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/comment/view.php';
        }
        public static function View( $commentid ) {
            clude( 'models/types.php' );
            clude( 'models/db.php' );
            clude( 'models/comment.php' );
            $comment = Comment::Item( $commentid );
            $comment[ 'id' ] = $commentid;
            $user = $comment[ 'user' ];
            include 'views/comment/view.php';
        }
        public static function Listing( $typeid, $itemid, $page ) {
            clude( 'models/db.php' );
            clude( 'models/comment.php' );
            $page = ( int )$page;
            $page >= 1 or die;
            $commentdata = Comment::ListByPage( $typeid, $itemid, $page );
            if ( $commentdata !== false ) {
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
            }
            include 'views/comment/listing.php';
        }
    }
?>
