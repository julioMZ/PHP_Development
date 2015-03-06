<?php

    class Data_Type_Float 
        extends Data_Interface_Number
    {

        public function  __construct( $var = 0.0 )
        {
            $this->setVar( $var );
        }

        public function setVar( $var = 0.0 )
        {

            if( !empty( $var ) && ( is_float( $var ) || is_numeric( $var ) ) ) {
                $this->_var = floatval( $var );
                parent::_setNegative();
            }
            
        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( $object->getVar() );
        }

        public static function parseFloat( $value = null )
        {
            return floatval( $value );
        }

    }

?>