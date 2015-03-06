<?php

    class Data_Type_Collection_Types 
        extends Data_Interface_Collection
    {


        public function setVar( $var = null )
        {

            if( is_array( $var ) ) {

                if( !empty( $var ) ) {

                    foreach( $var as $value ) {

                        if( !$value instanceof Data_Interface_Object ) {
                            parent::_throwTypeException( "Item {$value} in {$var} is not an Data_Interface_Object instance in " .  __METHOD__ );
                        }

                    }

                }

                $this->_var = $var;

            } else {
                parent::_throwTypeException( "Variable {$var} is required as an array in " . __METHOD__ );
            }

        }

        public static function valueOf( Data_Interface_Object $object )
        {
            return ( $object instanceof self ) ? $object : new self( array( $object ) );
        }

        public function add( $item, $index = null )
        {

            if( $item instanceof Data_Interface_Object ) {
                ( !empty( $index ) ) ? $this->_var[ $index ] = $item : array_push( $this->_var, $item );
            } else {
                parent::_throwTypeException( "Item {$item} is not a valid Data_Interface_Object instance in " . __METHOD__ );
            }

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