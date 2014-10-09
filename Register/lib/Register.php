<?php

/**
 *
 * Register space to avoid the use of global variables.
 * @category    Register
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     1.0
 * @uses        ArrayAccess Implements this interface to allow the instance to have the same behaviour as an Array Object.
 * @uses        IteratorAggregate Implements this interface to allow the instance to be Iterable.
 * @uses        Countable Implements this interface to allow the user to know how many regists are inside the instance.
 */
    class Register 
        implements ArrayAccess, IteratorAggregate, Countable
    {//---------------------------------------->> Class Register
        
        /**
         *
         * Singleton Register Implementation
         * @static
         * @access  private
         * @var     Register
         */
        private static $_instance;
        
        /**
         *
         * Assosiative Array when the "global" variables will be 
         * registered.
         * @access  private
         * @var     array
         */
        private $_register = array();
        
        /**
         * 
         * Private constructor to ensure only static acces.
         * (Singleton Design Pattern Implementation)
         * @access  private
         */
        private function __construct() 
        {//-------------------->> __construct()
            
        }//-------------------->> End __construct()
        
        /**
         * 
         * Retrieves one and only one Register instance.
         * (Singleton Design Pattern Implementation)
         * @static
         * @return  Register
         */
        public static function getInstance()
        {//-------------------->> getInstance()

            if( is_null( self::$_instance ) ) {//---------->> if empty instance
                self::$_instance = new self();
            }//---------->> End if empty instance

            return self::$_instance;

        }//-------------------->> End getInstance()
        
        /**
         * 
         * Method used to validate if the $register[ $offset ] exists supposing that
         * $register is a Register instance.
         * <p>This method is invoked when the instance is used as parameter of the native isset function like array access.</p>
         * @param   mixed $offset Name of the array key to validate its existance.
         * @return  boolean
         * @example isset( $register[ $offset ] );
         */
        public function offsetExists( $offset ) 
        {//-------------------->> offsetExists()
            return isset( $this->_register[ $offset ] );
        }//-------------------->> End offsetExists()

        /**
         * 
         * Method used to get the value inside the $register[ $offset ] position 
         * supposing that $register is a Register instance.
         * <p>This method is invoked when the instance is used like array access-get action.</p>
         * @param   mixed $offset Name of the array key to be retrived.
         * @return  mixed
         * @example $register[ $offset ];
         */
        public function offsetGet( $offset ) 
        {//-------------------->> offsetGet()
            return $this->_register[ $offset ];
        }//-------------------->> End offsetGet()
        
        /**
         * 
         * Method used to get the value inside the $register[ $name ] position 
         * supposing that $register is a Register instance.
         * <p>This method is invoked when the instance is used like object access-get action.</p>
         * @param   mixed $name Name of the array key to be retrived.
         * @return  mixed
         * @example $register->name;
         */
        public function __get( $name ) 
        {//-------------------->> __get()
            return $this->offsetGet( $name );
        }//-------------------->> End __get()

        /**
         * 
         * Method used to set the value inside the $register[ $offset ] position 
         * supposing that $register is a Register instance.
         * <p>
         *      If the position already exists, the current value will be overwritten by the new $value.
         *      If the position doesn't exist, it will be created and the $value will be registered there.
         * </p>
         * <p>This method is invoked when the instance is used like array access-set action.</p>
         * @param   mixed $offset Name of the array key where $value will be registered.
         * @param   mixed $value Value to be registered inside the $register[ $offset ] position.
         * @example $register[ $offset ] = $value;
         */
        public function offsetSet( $offset, $value )
        {//-------------------->> offsetSet()
            $this->_register[ $offset ] = $value;
        }//-------------------->> End offsetSet()
        
        /**
         * 
         * Method used to set the value inside the $register[ $offset ] position 
         * supposing that $register is a Register instance.
         * <p>
         *      If the position already exists, the current value will be overwritten by the new $value.
         *      If the position doesn't exist, it will be created and the $value will be registered there.
         * </p>
         * <p>This method is invoked when the instance is used like object access-set action.</p>
         * @param   mixed $name Name of the array key where $value will be registered.
         * @param   mixed $value Value to be registered inside the $register[ $offset ] position.
         * @example $register->$name = $value;
         */
        public function __set( $name, $value )
        {//-------------------->> __set
            $this->offsetSet( $name, $value );
        }//-------------------->> End __set()
        
        /**
         * 
         * Method used to unset the position and the value inside the $register[ $offset ]
         * supposing that $register is a Register instance.
         * <p>This method is invoked when the instance is used as parameter of the native unset function like array access.</p>
         * @param   mixed $offset Name of the array key to be unsettled.
         * @example unset( $register[ $offset ] );
         */
        public function offsetUnset( $offset ) 
        {//-------------------->> offsetUnset()
            unset( $this->_register[ $offset ] );
        }//-------------------->> End offsetUnset()
        
        /**
         * 
         * Method used to retrive the internal array by reference.
         * <p>This method is invoked when the instance is used inside a foreach process.</p>
         * @return  ArrayIterator
         * @example foreach( $register as $index => $value )
         */
        public function getIterator() 
        {//-------------------->> getIterator()
            return new ArrayIterator( $this->_register );
        }//-------------------->> End getIterator()
        
        /**
         * 
         * Method used to retrive the number of registers into the internal array by reference.
         * <p>This method is invoked when the instance is used as parameter of the native count function.</p>
         * @param   int $mode [optional]
         * <p>
	 *  The optional <i>mode</i> parameter will be set to
	 *  <b>COUNT_NORMAL</b> or <b>COUNT_RECURSIVE</b>, depending
	 *  on what value was passed to the second parameter of <b>count</b>.
	 *  This is particularly useful for counting all the elements of
	 *  a multidimensional array/Countable combination.
	 * </p>
         * @return  int
         * @example count( $register );
         */
        public function count( $mode = 'COUNT_NORMAL' ) 
        {//-------------------->> count()
            return count( $this->_register, $mode );
        }//-------------------->> End count()
        
        /**
         * 
         * Clears the current state of the Register at unsetting all the keys 
         * and values inside of it.
         */
        public function clear()
        {//-------------------->> clear()
            $this->_register = array();
        }//-------------------->> End clear()
        
    }//---------------------------------------->> End Class Register
