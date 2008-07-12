<?php
	class ElementUserSettingsSidebar extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            
            ?><ol id="settingslist">
                <li class="personal"><a href="" onclick="Settings.SwitchSettings( 'personal' );return false;"><span></span>Πληροφορίες</a></li>
                <li class="characteristics"><a href="" onclick="Settings.SwitchSettings( 'characteristics' );return false;"><span></span>Χαρακτηριστικά</a></li>
                <li class="interests"><a href="" onclick="Settings.SwitchSettings( 'interests' );return false;"><span></span>Ενδιαφέροντα</a></li>
                <li class="contact"><a href="" onclick="Settings.SwitchSettings( 'contact' );return false;"><span></span>Επικοινωνία</a></li>
                <li class="settings"><a href="" onclick="Settings.SwitchSettings( 'settings' );return false;"><span></span>Ρυθμίσεις</a></li>
            </ol>
            <div>
                <span class="saving"><img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>ajax-loader.gif" /> Αποθήκευση...
                </span>
                <span class="saved">Οι επιλογές σου αποθηκεύτηκαν αυτόματα</span>
            </div>
            <a class="backtoprofile button" style="padding-top:0;padding-bottom:0;" href="<?php
            Element( 'user/url' , $user );
            ?>">Επιστροφή στο προφίλ<span></span></a><?php
        }
    }
?>
