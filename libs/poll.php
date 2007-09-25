<?php
    
    function Poll_GetByUser( $theuser, $limit = 0 ) {
        global $polls;
        global $votes; // for finding if the current user has voted or not
        global $user;
        global $db;

        $sql = "SELECT
                    *
                FROM
                    `$polls` LEFT JOIN `$votes`
                        ON `poll_id` = `vote_pollid` AND `vote_userid` = '" . $user->Id() . "'
                WHERE
                    `poll_userid` = '" . $theuser->Id() . "' AND
                    `poll_delid` = '0'
                ";
        
        if ( $limit > 0 ) {
            $sql .= "LIMIT $limit";
        }

        $res = $db->Query( $sql );
        
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $poll = new Poll( $row );
            $poll->UserShouldHadVoted( $user );
        }

        return $ret;
    }

    class PollOption extends Satori {
        protected $mId;
        protected $mText;
        protected $mPollId;
        protected $mPoll;
        protected $mNumVotes;
        protected $mPercentage;
        protected $mDelId;

        protected function SetPoll( $poll ) {
            $this->mPoll = $poll;
        }
        public function GetPoll() {
            if ( $this->mPoll === false ) {
                $this->mPoll = new Poll( $this->PollId );
            }

            return $this->mPoll;
        }
        public function SetPollId( $value ) {
            $this->mPollId = $value;
            $this->Poll = new Poll( $this->PollId );
        }
        protected function SetPercentage( $percentage ) {
            $this->mPercentage = $percentage;
        }
        public function GetPercentage() {
            return $this->mPercentage;
        }
        protected function LoadDefaults() {
            $this->NumVotes = 0;
        }
        public function Delete() {
            $this->mDelId = 1;
            $this->Save();
            
            // remove this option's votes from poll votes
            $this->Poll->NumVotes -= $this->NumVotes;
            $this->Poll->Save();
        }
        public function UndoDelete() {
            $this->mDelId = 0;
            $this->Save();

            // add this option's votes to poll votes
            $this->Poll->NumVotes += $this->NumVotes;
            $this->Poll->Save();
        }
        public function PollOption( $construct = false, $poll = false ) {
            global $db;
            global $polloptions;

            $this->mDb      = $db;
            $this->mDbTable = $polloptions;

            $this->SetFields( array(
                'polloption_id'         => 'Id',
                'polloption_text'       => 'Text',
                'polloption_pollid'     => 'PollId',
                'polloption_numvotes'   => 'NumVotes',
                'polloption_delid'      => 'DelId'
            ) );

            $this->Satori( $construct );

            $this->Poll         = $poll;
            $this->Percentage   = ( $this->Poll->NumVotes > 0 ) ? ( $this->NumVotes / $this->Poll->NumVotes * 100 ) : 0;
        }
    }

    class PollVote extends Satori {
        protected $mId;
        protected $mUserId;
        protected $mOptionId; // not in the db!
        protected $mPollId;
        protected $mDate;

        public function GetOptionId() {
            return $this->mOptionId;
        }
        public function SetOptionId( $value ) {
            $this->mOptionId = $value;
        }
        public function LoadDefaults() {
            $this->Date = NowDate();
        }
        public function Save() {
            $isnew = !$this->Exists();
            $change = parent::Save();

            if ( $change->Impact() && $isnew ) {
                $option = new PollOption( $this->OptionId );
                ++$option->NumVotes;
                $option->Save();

                $poll = $option->Poll;
                ++$poll->NumVotes;
                $poll->Save();
            }
            
            return $change->Impact();
        }
        public function PollVote( $construct = false ) {
            global $db;
            global $votes;

            w_assert( $construct === false || is_array( $construct ), $construct . " is not a valid PollVote constructor" );
        
            $this->mDb      = $db;
            $this->mDbTable = $votes;
            
            $this->SetFields( array(
                'vote_userid'   => 'UserId',
                'vote_pollid' => 'PollId',
                'vote_date'     => 'Date'
            ) );

            $this->Satori( $construct );

            $this->OptionId = false;
        }
    }

    class Poll extends Satori {
        protected   $mId;
        protected   $mQuestion;
        protected   $mUserId;
        protected   $mExpireDate;
        protected   $mCreated;
        protected   $mDelId;
        protected   $mNumVotes;
        private     $mOptions;
        private     $mTextOptions;
        private     $mHasVoted;

        public function UserShouldHadVoted( $userid ) {
            // this is a tricky one :P

            // the sql query had joined the votes table
            // and then the constructor of the poll checked if
            // a vote_userid field was found on the construct.

            // in case he had voted, the mHasVoted[ userid ] is set to true
            // but we can't know whether he didn't vote or the sql query didn't join the votes table
            // so the function doing the query calls this function when we had joined the tables
            // to set the mHasVoted[ userid ] to false

            // By doing this, the UserHasVoted( userid ) function won't have to run any other sql queries! :-)

            if ( !isset( $this->mHasVoted[ $user->Id() ] ) ) {
                $this->mHasVoted[ $user->Id() ] = false;
            }
        }
        public function UserHasVoted( $user ) {
            global $polloptions;
            global $votes;

            if ( !isset( $this->mHasVoted[ $user->Id() ] ) ) {
                $sql = "SELECT
                            *
                        FROM
                            `$votes`
                        WHERE
                            `vote_userid`       = '" . $user->Id() . "' AND
                            `vote_pollid`       = '" . $this->Id . "'
                        LIMIT 1;";
                
                $this->mHasVoted[ $user->Id() ] = $this->mDb->Query( $sql )->Results();
            }

            return $this->mHasVoted[ $user->Id() ];
        }
        public function HasExpired() {
            if ( $this->ExpireDate == '0000-00-00 00:00:00' ) {
                return false;
            }

            $sql = "SELECT
                        `poll_expire` > NOW()
                    FROM
                        `" . $this->mDbTable . "`
                    WHERE
                        `poll_id` = '" . $this->Id . "'
                    LIMIT 1;";

            $fetched = $db->Query( $sql )->FetchArray();

            return $fetced[ 0 ];
        }
        public function Stop() {
            $this->ExpireDate   = NowDate();
            return $this->Save();
        }
        public function Vote( $userid, $optionid ) {
            $vote           = new PollVote();
            $vote->PollId   = $this->Id;
            $vote->OptionId = $optionid;
            $vote->UserId   = $userid;
            $vote->Save();

            $this->mHasVoted[ $userid ] = true;
        }
        public function GetOptions() {
            global $polloptions;
            global $votes;

            if ( $this->mOptions === false ) {
                $sql = "SELECT
                            *
                        FROM
                            `$polloptions`
                        WHERE
                            `polloption_pollid` = '" . $this->Id . "' AND
                            `polloption_delid` = '0'
                        ;";

                $res = $this->mDb->Query( $sql );
                $this->mOptions = array();
                while ( $row = $res->FetchArray() ) {
                    $this->mOptions[]   = new PollOption( $row, $this );
                }
            }

            return $this->mOptions;
        }
        public function SetTextOptions( $options ) {
            w_assert( is_array( $options ) );

            $this->mTextOptions = $options;
        }
        public function Delete() {
            $this->DelId = 1;

            return $this->Save();
        }
        public function Save() {
            $isnew = !$this->Exists();

            parent::Save();

            if ( $isnew && $this->mTextOptions !== false ) {
                foreach ( $this->mTextOptions as $optiontext ) {
                    $option         = new PollOption();
                    $option->PollId = $this->Id;
                    $option->Text   = $optiontext;
                    $option->Save();

                    $this->mOptions[] = $option;
                }
            }
        }
        public function LoadDefaults() {
            $this->mTextOptions = false;
            $this->Created      = NowDate();
            $this->ExpireDate   = '0000-00-00 00:00:00'; // default is never expire
            $this->NumVotes     = 0;
        }
        public function Poll( $construct = false ) {
            global $db;
            global $polls;
            global $water;

            $this->mDb      = $db;
            $this->mDbTable = $polls;

            $this->SetFields( array(
                'poll_id'       => 'Id',
                'poll_question' => 'Question',
                'poll_userid'   => 'UserId',
                'poll_expire'   => 'ExpireDate',
                'poll_created'  => 'Created',
                'poll_delid'    => 'DelId',
                'poll_numvotes' => 'NumVotes'
            ) );

            $this->Satori( $construct );
            
            $this->mOptions     = false;
            $this->mHasVoted    = array();

            if ( isset( $construct[ 'vote_userid' ] ) && isset( $construct[ 'vote_pollid' ] ) && $construct[ 'vote_pollid' ] == $this->Id ) {
                $this->mHasVoted[ $construct[ 'vote_userid' ] ] = true;
                $water->Trace( "The user " . $construct[ 'vote_userid' ] . " has voted on the poll " . $this->Id );
            }
        }
    }

?>
