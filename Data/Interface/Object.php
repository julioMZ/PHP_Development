<?php

    abstract class Data_Interface_Object 
        implements Data_Interface_Comparable, Serializable
    {

        protected $_var = null;

        public function getVar()
        {
            return $this->_var;
        }

        public function getClass()
        {
            return get_class( $this );
        }

        public function getClasses()
        {
            return class_parents( $this );
        }

        public function equals( Data_Interface_Object $object )
        {
            return ( $this->getVar() === $object->getVar() ) ? true : false;
        }

        public function contentEquals( $content = '' )
        {
            return ( $this->getVar() === $content ) ? true : false;
        }

        public function getInterfaces()
        {
            return class_implements( $this );
        }

        public function getProperties()
        {
            return get_class_vars( $this );
        }

        public function getMethods()
        {
            return get_class_methods( $this );
        }

        protected static function _throwTypeException( $message = '' )
        {
            throw new Data_Exception_Type( $message );
        }

        public function serialize()
        {
            return serialize( $this->getVar() );
        }

        public function unserialize( $data )
        {
            $this->setVar( unserialize( $data ) );
        }

        public function  __toString()
        {
            return $this->toString();
        }

        abstract public function setVar( $var = null );
        abstract public function toString();
        abstract public function isEmpty();
        abstract public static function valueOf( Data_Interface_Object $object );
        
    }

?>