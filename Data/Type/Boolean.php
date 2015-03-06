<?php

    class Data_Type_Boolean 
        extends Data_Interface_Object
    {

        protected $_booleanValue = false;

        public static $trueValues = array( '1', '1.0', 'true', 'yes' );
        public static $falseValues = array( '0', '0.0', 'false', 'no' );

        public function  __construct( $var = null )
        {
            $this->setVar( $var );
        }

        public function setVar( $var = null )
        {
            $this->_var = $var;
            $this->_booleanValue = self::parseBoolean( $var );
        }

        public function booleanValue()
        {
            return $this->_booleanValue;
        }

        public static function parseBoolean( $var = null )
        {

            $booleanValue = false;

            if( !empty( $var ) ) {

                switch ( gettype( $var ) ) {

                    case 'boolean':
                        $booleanValue = $var;
                    break;

                    case 'array':
                    case 'object':
                    case 'resource':
                        $booleanValue = true;
                    break;

                    default:

                        $var = strtolower( $var );

                        if( in_array( $var, self::$trueValues ) || $var > 0 ) {
                            $booleanValue = true;
                        } elseif ( in_array( $var, self::$falseValues ) || $var < 0 ) {
                            $booleanValue = false;
                        }

                    break;

                }
                
            }

            return $booleanValue;

        }

        public function toString()
        {
            return strval( $this->getVar() );
        }

        public function isEmpty()
        {
            return ( null == $this->getVar() ) ? true : false;
        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( $object->getVar() );
        }

    }

?>