<?php
    class Tinyurl {
        public static function SearchSong( $term ) {
            $url = "http://tinysong.com/s/" . urlencode( $term ) . "?format=json&limit=32&key=12747305fb33dc121853ca62536ec6ad";

            $handler = curl_init();        
            curl_setopt( $handler, CURLOPT_URL, $url );
            curl_setopt( $handler, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt( $handler, CURLOPT_CONNECTTIMEOUT, 2);
            $result = curl_exec( $handler );
            curl_close( $handler );

            return Tinyurl::ProccessJson( $result );
        }

        private static function ProccessJson( $info ) {
            $res = json_decode( $info, true );
            return $res;
        }
    }
?>
