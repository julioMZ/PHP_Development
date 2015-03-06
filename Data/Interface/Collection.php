<?php

    abstract class Data_Interface_Collection 
        extends Data_Interface_Object 
        implements IteratorAggregate, ArrayAccess, Countable
    {
        
        final public function  __construct( $var = null )
        {
           $this->setVar( ( !empty( $var ) ) ? $var : array() );
        }

        abstract public function add( $item, $index = null );
        abstract public function remove( $index = null );

        public function count()
        {
            return count( $this->getVar() );
        }

        public function offsetExists( $offset )
        {
             return isset( $this->_var[ $offset ] );
        }

        public function offsetGet( $offset )
        {
            return $this->_var[ $offset ];
        }

        public function offsetSet( $offset, $value )
        {
            $this->add( $value, $offset );
        }

        public function offsetUnset( $offset )
        {
             $this->remove( $offset );
        }
        
        public function clear()
        {
            $this->_var = array();
        }

        public function getKeys()
        {
            return array_keys( $this->_var );
        }

        public function getIterator()
        {
            return new ArrayIterator( $this->_var );
        }

        public function isEmpty()
        {
            return empty( $this->_var );
        }

        public function toString()
        {

            $arrayDescription = null;

            ob_start();//<<----------------------- buffering
                var_dump( $this->getVar() );
                $arrayDescription = ob_get_contents();
            ob_end_clean();//<<------------------- End buffering

            return $arrayDescription;

        }


    }

?>