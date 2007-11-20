<?php
    include 'rabbit/xml.php';

    class XHTMLSanitizer {
        private $mSource;
        private $mAllowedTags;
        
        public function XHTMLSanitizer() {
            $this->mAllowedTags = array();
            $this->mSource = false;
        }
        public function SetSource( $source ) {
            global $water;
            
            if ( $source === true ) {
                $water->Notice( 'XHTMLSanitizer source was a boolean; converting to string' );
                $source = '1';
            }
            else if ( $source === false ) {
                $water->Notice( 'XHTMLSanitizer source was a boolean; converting to string' );
                $source = '';
            }
            else if ( is_array( $source ) ) {
                $water->Warning( 'XHTMLSanitizer source was an array; skipping' );
                $source = '';
            }
            else if ( is_object( $source ) ) {
                $water->Warning( 'XHTMLSanitizer source was an object; skipping' );
                $source = '';
            }
            $source = ( string )$source;
            $this->mSource = $source;
        }
        private function RemoveComments( $htmlsource ) {
            global $water;
            
            if ( preg_match( '#\<\!--(.*?)\<\!--(.*?)--\>#', $htmlsource ) ) {
                $water->Warning( 'XHTMLSanitizer: Call me paranoid, but finding \'<!--\' inside this comment makes me suspicious' );
            }
            return preg_replace( '#\<\!--(.*?)--\>#', '', $htmlsource );
        }
        private function ReduceWhitespace( $htmlsource ) {
            return preg_replace( "#([ \t\n\r]+)#", ' ', $htmlsource );
        }
        public function GetXHTML() {
            global $water;
            
            w_assert( $this->mSource !== false, 'Please SetSource() before calling GetXHTML()' );
            
            $tags = array();
            
            $source = $this->mSource;
            
            $source = $this->RemoveComments( $source );
            
            $descriptors = array(
                0 => array( "pipe", "r" ),
                1 => array( "pipe", "w" ),
            );
            
            $process = proc_open( '/var/www/chit-chat.gr/beta/bin/sanitizer/sanitize', $descriptors, $pipes, '.', array() );
            
            if ( !is_resource( $process ) ) {
                $water->Notice( 'Failed to start the sanitizer' );
                return '';
            }
            
            fwrite( $pipes[ 0 ], $source );
            fclose( $pipes[ 0 ] );
            
            $ret = stream_get_contents( $pipes[ 1 ] );
            
            ob_start();
            var_dump( $ret );
            $tidied = ob_get_clean();
            
            $water->Trace( 'Sanitizer tidied up document', $tidied );
            
            fclose( $pipes[ 1 ] );
            
            $returnvalue = proc_close( $process );
            
            $water->Trace( 'Sanitizer exited with status ' . $returnvalue );
            
            $parser = New XMLParser( '<body>' . trim( $this->ReduceWhitespace( $ret ) ) . '</body>' );
            $body = $parser->Parse();
            if ( $body === false ) {
                return '';
            }
            w_assert( $body->nodeName == 'body' );
            
            $ret = $this->XMLInnerHTML( $body );
            
            return $ret;
        }
        private function XMLOuterHTML( XMLNode $root ) {
            if ( !isset( $this->mAllowedTags[ $root->nodeName ] ) ) {
                return $this->XMLInnerHTML( $root );
            }
            
            $tagrule = $this->mAllowedTags[ $root->nodeName ];
            
            $ret = '<' . $root->nodeName;
            
            $attributes = array();
            foreach ( $root->attributes as $attribute => $value ) {
                if ( $tagrule->AttributeAllowed( $attribute ) ) {
                    $attributes[] = $attribute . '="' . htmlspecialchars( $value ) . '"';
                }
            }
            
            if ( !empty( $attributes ) ) {
                $ret .= ' ' . implode( ' ', $attributes );
            }
            
            if ( empty( $root->childNodes ) ) {
                $ret .= '/>';
            }
            else {
                $ret .= '>';
                $ret .= $this->XMLInnerHTML( $root );
                $ret .= '</' . $root->nodeName . '>';
            }
            
            return $ret;
        }
        private function XMLInnerHTML( XMLNode $root ) {
            $ret = '';
            foreach ( $root->childNodes as $xmlnode ) {
                if ( is_string( $xmlnode ) ) {
                    $ret .= htmlspecialchars( $xmlnode );
                }
                else {
                    $ret .= $this->XMLOuterHTML( $xmlnode );
                }
            }
            return $ret;
        }
        public function AllowTag( XHTMLSaneTag $tag ) {
            $this->mAllowedTags[ $tag->Name() ] = $tag;
        }
    }
    
    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;
        
        public function Name() {
            return $this->mName;
        }
        public function AttributeAllowed( $attributename ) {
            w_assert( is_string( $attributename ) );
            return isset( $this->mAllowedAttributes[ $attributename ] );
        }
        public function XHTMLSaneTag( $tagname ) {
            w_assert( is_string( $tagname ) );
            w_assert( preg_match( '#^[a-z0-9]+$#', $tagname ) );
            $this->mName = $tagname;
        }
        public function AllowAttribute( XHTMLSaneAttribute $attribute ) {
            $this->mAllowedAttributes[ $attribute->Name() ] = $attribute;
        }
    }
    
    class XHTMLSaneAttribute {
        private $mName;
        
        public function Name() {
            return $this->mName;
        }
        public function XHTMLSaneAttribute( $attributename ) {
            w_assert( is_string( $attributename ) );
            w_assert( preg_match( '#^[a-z]+$#', $attributename ) );
            $this->mName = $attributename;
        }
    }
?>
