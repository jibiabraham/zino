<?php 

    /*
        Developer: abresas
    */

    class BulkFinder extends Finder {
        protected $mModel = 'Bulk';
        private static $mFetched = array(); // array for caching bulk data in current request
        
        public function FindById( $ids ) {
            $ret = array();
            if ( !is_array( $ids ) ) {
                $ids = array( $ids );
                $was_array = false;
            }
            else {
                $was_array = true;
            }

            foreach ( $ids as $id ) {
                w_assert( is_numeric( $id ) );
            }

            $keyids = array_flip( $ids );
            $already = array_intersect_key( $keyids, self::$mFetched );
            foreach ( $already as $id ) {
                $ret[ $id ] = New Bulk( self::$mFetched[ $id ] );
                unset( $ids[ $keyids[ $id ] ] );
            }

            if ( count( $ids ) ) {
                $ret += $this->Fetch( $ids );
            }

            if ( $was_array ) {
                return $ret;
            }

            w_assert( count( $ret ) );

            return array_shift( $ret );
        }

        private function Fetch( $ids ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    `bulk_id`, `bulk_text`
                FROM
                    :bulk
                WHERE
                    `bulk_id` IN :Ids;
            " );

            $query->BindTable( 'bulk' );
            $query->Bind( 'Ids', $ids );
            
            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[ $row[ "bulk_id" ] ] = New Bulk( $row );
                self::$mFetched[ $row[ "bulk_id" ] ] = $row; // add data to cache
            }

            if ( !count( $ret ) ) {
                echo "ids: ";
                print_r( $ids );
            }

            return $ret;
        }
    }

    final class Bulk extends Satori {
        protected $mDbTableAlias = 'bulk';
    }

?>
