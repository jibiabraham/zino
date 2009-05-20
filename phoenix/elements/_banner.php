<?php
    
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ?>
           <div id="lbanner">
                <h1>
                    <a href="<?php
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>">
                        <img src="http://static.zino.gr/phoenix/logo.png" />
                    </a>
                </h1>
           </div>
           <div id="rbanner">
           </div>
           <div id="mbanner">
                <div><?php
                        if ( $user->Avatar->Id > 0 ) {
                            Element( 'image/view' , $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height , IMAGE_CROPPED_100x100 , 'banneravatar' , $user->Name , '' , true , 50 , 50 , 0 );

                        }
                        else {
                            ?><img src="http://static.zino.gr/phoenix/anonymous100.jpg" alt="<?php
                            echo htmlspecialchars( $user->Name );
                            ?>" title="<?php
                            echo htmlspecialchars( $user->Name );
                            ?>" class="banneravatar" />
                            <?php
                        }
                    ?>                    
                    <ul>
                        <li>
                        <a href="<?php
                        ob_start();
                        Element( 'user/url' , $user->Id , $user->Subdomain );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>" class="bannerinlink">Προφίλ</a>
                        
                        </li>
                        <li>
                            <a href="settings" class="bannerinlink">Ρυθμίσεις</a>
                        </li>
                        <li>
                            <a href="#" class="bannerinlink">Βρες φίλους</a>
                        </li>
                        <li>
                            <form method="post" action="do/user/logout">
                                <a href="#" class="bannerinlink" onclick="this.parentNode.submit();return false;">Έξοδος</a>
                            </form>
                        </li>
                    </ul>
                </div>
           </div><?php
       }
    }
?>
