<?php
    class Song {
        public static function Item( $userid ) {
            return
                array_shift( db_array(
                     'SELECT
                        song_id AS id, song_songid AS songid
                     FROM
                        song
                     WHERE
                        song_userid = :userid
                     ORDER BY
                        `song_id` DESC
                    LIMIT 1', compact( 'userid' )
                ) );
        }
    }
?>
