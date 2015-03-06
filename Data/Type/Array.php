<?php


    class Data_Type_Array 
        extends Data_Interface_Collection
    {

        public function setVar( $var = null )
        {
            $this->_var = (array) $var;
        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( $object->getVar() );
        }

        public function add( $item, $index = null )
        {
             ( !empty( $index ) ) ? $this->_var[ $index ] = $item : array_push( $this->_var, $item );
             return $this;
        }

        public function remove( $index = null )
        {

            if( isset( $this->_var[ $index ] ) ) {
                unset( $this->_var[ $index ] );
            }
            
            return $this;
            
        }

    }

?>