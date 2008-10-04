<?php
    /*
        Developer:Pagio
    */
    
    class Ban {    
        public function Revoke( $userid ) {
            global $libs;
                        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );    
            $libs->Load( 'user/user' );     
            
            $bannedUserFinder = new BanneduserFinder();//delete banneduser
            $bannedUsers = $bannedUserFinder->FindByUserId( $userid );
            
            if ( count( $bannedUsers ) == 1 ) {
                $cur_user = current( $bannedUsers );
                $user_d = new BannedUser( $cur_user->Id );
                $rights = $user_d->Rights;
                $user_d->Delete();                
            }
            else {
                return;
            }
                
            $userFinder = new UserFinder();//restore user rights
            $user = $userFinder->FindById( $userid );
            $user->Rights = $rights;
            $user->Save();   
            
            $ipFinder = new BannedIpFinder();//delete related ips
            $ips = $ipFinder->FindByUserId( $userid );            
            
            foreach( $ips as $ip ) {
                $ip_d = new BannedIp( $ip->Id );
                $ip_d->Delete();
            }
                          
            return;
        }
    
        public function isBannedIp( $ip ) {
            global $libs;     
               
            $libs->Load( 'adminpanel/bannedips' );
               
            $ipFinder = new BannedIpFinder();
            $res = $ipFinder->FindActiveByIp( $ip );
               
            if ( empty( $res ) ) {               
                 return false;
            }
            else { 
                return true;
            }
        }
        
        public function isBannedUser( $userid ) {
            global $libs;
            
            $libs->Load( 'adminpanel/bannedusers' );
            
            $userFinder = new BannedUserFinder();
            $res = $userFinder->FindByUserId( $userid );
            
            if ( !$res ) {
                return false;
            }
            else {
                $user = current( $res );
                $diff = strtotime( NowDate() ) - strtotime( $user->Expire );
                
                if ( $diff > 0 ) {
                    $this->Revoke( $user->Userid );
                    return false;
                }
                else {
                    return true;
                }
            }
        }

        public function BanIp( $ip, $time_banned ) {//time in seconds
            w_assert( is_int( $time_banned ), "Time to be banned is not an integer." );
            w_assert( ( $time_banned > 0 ), "Time to be banned is negative." );
            $this->addBannedIps( array( $ip ), -1 , $time_banned );
        }
    
        
        public function BanUser( $user_name, $reason ) {
            global $libs;
            
            $libs->Load( 'user/user' );        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );
            $libs->Load( 'loginattempt' );
            
            
            //check if the user doesn't exist or if he is already banned
            $userFinder = new UserFinder();
            $b_user = $userFinder->FindByName( $user_name );
            
            if ( !$b_user ) {
                return false;
            }
            
            $bannedUserFinder = new BannedUserFinder();
            $exists = $bannedUserFinder->FindByUserId( $b_user->Id );
            
            if ( $exists ) {
                return false;
            }
            //
            
            //trace relevant ips from login attempts            
            $loginAttemptFinder = new LoginAttemptFinder();
            $res = $loginAttemptFinder->FindByUserName( $user_name );
            
            $logs = array();
            foreach ( $res as $logginattempt) {
                $logs[ $logginattempt->Ip ] = $logginattempt->Ip;
            }
            //
            
            //ban this ips and ban user with this username
            $this->addBannedIps( $logs, $b_user->Id );            
            $this->addBannedUser( $b_user, $reason );

            $b_user->Rights=0;
            $b_user->Save();
            //

            return true;
        }
        
        protected function addBannedUser( $b_user, $reason ) {
            global $libs;
            
            $libs->Load( 'adminpanel/bannedusers' );
            
            $banneduser = new BannedUser();
            $banneduser->Userid = $b_user->Id;
            $banneduser->Rights = $b_user->Rights;
            $banneduser->Started = date( 'Y-m-d H:i:s', time() );
            $banneduser->Expire = date( 'Y-m-d H:i:s', time() + 20*24*60*60 );
            $banneduser->Delalbums = 0;            
            $banneduser->Reason = $reason;
            $banneduser->Save();            
            return;        
        }
        
        protected function addBannedIps( $ips, $user_id, $time_banned =  1728000 ) {//1728000=20*24*60*60 sec
            global $libs;
            
            $libs->Load( 'adminpanel/bannedips' );
        
            $started = date( 'Y-m-d H:i:s', time() );
            $expire = date( 'Y-m-d H:i:s', time() + $time_banned );
            foreach( $ips as $ip ) {
                $banip = new BannedIp();
                $banip->Ip = $ip;
                $banip->Userid = $user_id;
                $banip->Started = $started;
                $banip->Expire = $expire;
                $banip->Save();
            }
            return;
        }
    }
?>
