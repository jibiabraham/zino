<?php

    class TestFriend extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/friend.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


            $this->mUsers = $this->GenerateTestUsers( 2 );
			$userid1 = $this->mUsers[ 0 ][ 'id' ];
			$userid2 = $this->mUsers[ 1 ][ 'id' ];
			$this->mData = array( 
				array( $userid1, $userid2, FRIENDS_A_HAS_B  )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Friend' );
            $this->AssertMethodExists( 'Friend', 'Create' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid1, $userid2, $typeid ) {	
			$res = Friend::Create( $userid1, $userid2, $typeid );
			$this->Assert( $res, "Create should return true" );
			$res2 = Friend::ItemByUserIds( $userid1, $userid2 );
			$this->Assert( $res2[ 'id' ], "Relation id should not be 0" );

			$res = Friend::Create( $userid1, $userid2, $typeid );
			$this->Assert( $res, "Create should return true" );
			$res3 = Friend::ItemByUserIds( $userid1, $userid2 );
			$this->AssertEquals( $res2[ 'id' ], $res3[ 'id' ], "Relation id should be unique" );

			return array( $res2[ 'id' ], $userid1, $userid2 );
        }
		/**
         * @producer TestCreate
         */
        public function TestDelete( $info ) {
			$relid = ( int )$info[ 0 ];
			$userid1 = ( int )$info[ 1 ];
			$userid2 = ( int )$info[ 2 ];
			$success = Friend::Delete( $userid1, $userid2 );
			$this->Assert( $success, 'Friend::Delete failed' );

			$friend = Friend::Item( $relid );
            $this->Called( "Friend::Item" );
            $this->Assert( empty( $friend ), 'Friend::Item should return empty array on deleted item' );
        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestFriend();
?>
