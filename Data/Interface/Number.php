<?php
    
    abstract class Data_Interface_Number 
        extends Data_Interface_Object
    {
        
        private $_negative = false;

        protected function _setNegative()
        {

            $var = (string) $this->getVar();
            $firstChar = $var{0};
            $this->_negative = ( $firstChar == '-' ) ? true : false;

        }

        public function isNegative()
        {
            return $this->_negative;
        }

        public function toString()
        {
            return strval( $this->getVar() );
        }

        public function isEmpty()
        {
            return ( empty( $this->_var ) ) ? true : false;
        }

        public function isNaN()
        {
            return ( !is_numeric( $this->getVar() ) ) ? true : false;
        }

        public function intValue( $base = 10 )
        {
            return intval( $this->getVar(), $base );
        }
        
        public function floatValue()
        {
            return floatval( $this->getVar() );
        }
        
    }

?>