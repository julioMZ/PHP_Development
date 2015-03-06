<?php

    class Data_Type_String 
        extends Data_Interface_Object 
        implements Data_Interface_Countable
    {

        public function __construct( $var = null )
        {
            $this->setVar( $var );
        }

        public function setVar( $var = null )
        {
            if( !empty( $var ) ) {
                $this->_var = strval( $var );
            }

        }

        public function equalsIgnoreCase( $content = '' )
        {
            return ( $this->toLowerCase() === strtolower( $content ) ) ? true : false;
        }

        public function startsWith( $charSecuence = '' )
        {
            return ( $this->indexOf( $charSecuence ) === 0 ) ? true : false;
        }

        public function endsWith( $charSecuence = '' )
        {
            $charSecuenceLength = ( strlen( $charSecuence ) == 1 ) ? 1 : strlen( $charSecuence ) - 1;
            return ( substr( $this->getVar(), ( $this->realLength() - $charSecuenceLength ) ) === $charSecuence ) ? true : false;
        }

        public function indexOf( $charSecuence = '' )
        {
            return stripos( $this->getVar(), $charSecuence );
        }

        public function lastIndexOf( $charSecuence = '' )
        {
            return strrpos( $this->getVar(), $charSecuence );
        }

        public function subString( $start = 0, $end = null )
        {
            return substr( $this->getVar(), $start, $end );
        }

        public function concat( $charSecuence = '' )
        {
            $this->setVar( "{$this->getVar()}{$charSecuence}" );
        }

        public function replace( $search = '', $replace = '' )
        {
            $this->setVar( str_replace( $search, $replace, $this->getVar() ) );
        }

        public function matches( $pattern = '' )
        {
            $matches = preg_match( $pattern, $this->getVar() );
            return ( !empty( $matches ) ) ? true : false;
        }

        public function replaceFirst( $pattern = '', $replace = '' )
        {
            $this->setVar( preg_replace( $pattern, $replace, $this->getVar(), 1 ) );
        }

        public function replaceAll( $pattern = '', $replace = '' )
        {
            $this->setVar( preg_replace( $pattern, $replace, $this->getVar() ) );
        }

        public function split( $delimeter = '' )
        {
            return explode( $delimeter, $this->getVar() );
        }

        public function toLowerCase()
        {
            return strtolower( $this->getVar() );
        }

        public function toUpperCase()
        {
            return strtoupper( $this->getVar() );
        }

        public function length()
        {
            return strlen( $this->getVar() );
        }

        public function realLength()
        {
            return ( $this->length() - 1 );
        }

        public function trim()
        {
            $this->setVar( trim( $this->getVar() ) );
        }

        public function isEmpty()
        {
            return ( empty( $this->_var ) || $this->length() == 0 );
        }
        
        public function toString()
        {
            return $this->getVar();
        }

        public function toCharArray()
        {
            return str_split( $this->getVar() );
        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( $object->getVar() );
        }

        public static function stringValue( $var = '' )
        {
            return strval( $var );
        }
        
    }

?>