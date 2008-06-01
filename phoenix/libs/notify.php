<?php

    function Notification_Types() {
        // array( name, field )
        // field: settings_notify_profile -> profile
        return array(
            1 => array( 'NOTIFY_COMMENT_PROFILE', 'profile' ),
            2 => array( 'NOTIFY_COMMENT_PHOTO', 'photos' ),
            3 => array( 'NOTIFY_COMMENT_JOURNAL', 'journals' ),
            4 => array( 'NOTIFY_COMMENT_REPLY', 'replies' ),
            5 => array( 'NOTIFY_FRIEND_ADDED', 'friends' )
        );
    }

    $types = Notification_Types();
    foreach ( $types as $key => $type ) {
        define( $type[ 0 ], $key );
    }

    function Notification_FieldByType( $type ) {
        $types = Notification_Types();

        return $types[ $type ][ 1 ];
    }

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function FindByUser( $user, $offset = 0, $limit = 20 ) {
            $notif = New Notification();
            $notif->Touserid = $user->Id;

            return $this->FindByPrototype( $notif, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function Email() {
            // send an email

        }
        public function OnCreate() {
            $attribute = 'Email' . Notification_FieldByType( $this->Typeid );
            if ( $this->ToUser->Settings->$attribute == 'yes' && !empty( $this->ToUser->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
        }
        public function Relations() {
            if ( $this->Exists() ) {
                switch ( $this->Typeid ) {
                    case NOTIFY_COMMENT_PROFILE:
                        $class = 'User';
                        break;
                    case NOTIFY_COMMENT_IMAGE:
                        $class = 'Image';
                        break;
                    case NOTIFY_COMMENT_JOURNAL:
                        $class = 'Journal';
                        break;
                    case NOTIFY_COMMENT_REPLY:
                        $class = 'Comment';
                        break;
                    case NOTIFY_FRIEND_ADDED:
                        break;
                    default:
                        throw New Exception( 'Unkown typeid on notification' );
                }
                $this->Item = $this->HasOne( $class, 'Itemid' );
            }

            $this->User = $this->HasOne( 'User', 'Fromuserid' );
            $this->ToUser = $this->HasOne( 'User', 'Touserid' );
        }
        public function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
    }

?>
