<?php

    class TestSchool extends Testcase {
        protected $mAppliesTo = 'libs/school/school';
        protected $mUser;
        protected $mFinder;
        protected $mSchool;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Name = 'testschooluser';
            $this->mUser->Subdomain = 'testschooluser';
            $this->mUser->Save();
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'SchoolException' ), 'class SchoolException does not exist' );
            $this->Assert( class_exists( 'SchoolFinder' ), 'class SchoolFinder does not exist' );
            $this->Assert( class_exists( 'School' ), 'class School does not exist' );
        }

        public function TestMethodsExist() {
            $this->Assert( method_exists( 'SchoolFinder', 'Find' ), 'method SchoolFinder->Find does not exist' );
            $this->Assert( method_exists( 'SchoolFinder', 'Count' ), 'method SchoolFinder->Count does not exist' );
        }

        public function TestFind() {
            $this->mFinder = New SchoolFinder();
            $schools = $this->mFinder->Find();
            $this->Assert( is_array( $schools ), 'SchoolFinder->Find does not return an array' );
            foreach ( $schools as $school ) {
                if ( !( $school instanceof School ) ) {
                    $this->Assert( false, 'SchoolFinder->Find does not return School instances' );
                    break;
                }
            }
        }

        public function TestCount() {
            $count = $this->mFinder->Count();
            $this->Assert( is_int( $count ), 'SchoolFinder->Count does not return an integer' );
            $this->Assert( $count >= 0, 'SchoolFinder->Count does not return a positive number' );
        }

        public function TestCreate() {
            $this->mSchool = New School();
            $this->mSchool->Name = '9th Extraordinarily Inappropriate School';
            $this->mSchool->Placeid = 13;
            $thrown = false;
            try {
                $this->mSchool->Typeid = 9;
            }
            catch ( SchoolException $e ) {
                $thrown = true;
            }
            $this->Assert( $thrown, 'When an invalid type id is set, a SchoolException must be thrown' );
            $this->mSchool->Typeid = 2;
            $this->mSchool->Save();
            $id = $this->mSchool->Id;
            $school = New School( $id );
            $this->Assert( $school->Exists(), 'School creation failed' );
        }

        public function TestApprove() {
            $this->AssertEquals( 0, $this->mSchool->Approved, 'Schools must not be approved by default' ); 
            $this->mSchool->Approved = 1;
            $this->mSchool->Save();
            $this->AssertEquals( 1, $this->mSchool->Approved, 'School cannot be approved' );
            $this->mSchool->Approved = 0;
            $this->mSchool->Save();
            $this->AssertEquals( 0, $this->mSchool->Approved, 'School cannot be disapproved' );
        }

        public function TestEdit() {
            $this->mSchool->Name = '9th Infinitely and Extraordinarily Inappropriate School';
            $this->mSchool->Placeid = 4;
            $this->mSchool->Save();
            $this->AssertEquals( '9th Infinitely and Extraordinarily Inappropriate School', $this->mSchool->Name, 'School cannot be renamed' );
            $this->AssertEquals( 4, $this->mSchool->Placeid, 'School cannot be replaced' );
        }

        public function TestAssign() {
            $this->mSchool->Approved = 1;
            $this->mSchool->Save();
            $this->mUser->Profile->School = $this->mSchool;
            $this->Assert(
                $this->mUser->Profile->School instanceof School &&
                is_string( $this->mUser->Profile->School->Name ) &&
                is_int( $this->mUser->Profile->School->Placeid ) &&
                is_int( $this->mUser->Profile->School->Typeid ),
                'School cannot be assigned to user'
            );
            $this->AssertEquals( 1, $this->mUser->Profile->School->Approved, 'School cannot be assigned to user unless it is approved' );
        }

        public function TestDelete() {
            $id = $this->mSchool->Id;
            $this->mSchool->Delete();
            $school = New School( $id );
            $this->Assert( !$school->Exists(), 'School deletion failed' );
        }

        public function TearDown() {
            $this->mUser->Delete();
        }
    }

    return New TestSchool();

?>
