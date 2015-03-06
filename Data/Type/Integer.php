<?php

    class Data_Type_Integer 
        extends Data_Interface_Number
    {

        public function  __construct( $var = 0, $base = 10 )
        {
            $this->setVar( $var, $base );
        }

        public function setVar( $var = 0, $base = 10 )
        {
            
            if( !empty( $var ) && ( is_int( $var ) || is_numeric( $var ) ) ) {
                $this->_var = intval( (string) $var, $base );
                $this->_setNegative();
            }

            return parent::isNaN();
            
        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( $object->getVar() );
        }

        public static function parseInt( $var = null, $base = 10 )
        {
            return intval( $var, $base );
        }

       /**
        * @access   public
        * @desc     Convert the integer into binary format.
        * @return   String Integer value in binary format.
        */
        public function toBinaryString()
        {
            return base_convert( (string) $this->_var, 10, 2 );
        }

       /**
        * @access   public
        * @desc     Convert the integer into hex format.
        * @return   String Integer value in hex format.
        */
        public function toHexString()
        {
            return strtoupper( base_convert( (string) $this->_var, 10, 16 ) );
        }

       /**
        * @access   public
        * @desc     Convert the integer into octal format.
        * @return   String Integer value in octal format.
        */
        public function toOctalString()
        {
            return base_convert( (string) $this->_var, 10, 8 );
        }

       /**
        * @access   public
        * @desc     Convert the integer into decimal format.
        * @return   String Integer value in decimal format.
        */
        public function toDecimalString()
        {
            return base_convert( (string) $this->_var, 10, 10 );
        }

    }

?>