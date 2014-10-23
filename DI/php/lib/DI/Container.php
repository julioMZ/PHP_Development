<?php

/**
 * 
 * Objects Container Interface.
 * @package     DI    
 * @category    Container
 * @version     1.0
 * @uses        ArrayAccess
 * @uses        IteratorAggregate
 * @author      Julio Mora <julio.mora.zamora@gmail.com>    
 */
abstract class DI_Container 
    implements ArrayAccess, IteratorAggregate
{//---------------------------------------->> Interface Container
    
    /**
     *
     * Map of Objects
     * @var     array
     * @access  protected
     */
    protected $_container = array();
    
    /**
     *
     * Determines if there is an index with the same $offset value inside the container.
     * @param   mixed $offset ID of some Object.
     * @return  boolean
     */
    public function offsetExists( $offset ) 
    {//-------------------->> offsetExists()
        return isset( $this->_container[ $offset ] );
    }//-------------------->> End offsetExists()

    /**
     *
     * Retrieves the Object Instance located on the index with the same
     * $offset value.
     * @param   mixed $offset ID of the Object to be retrieved.
     * @return  Object
     * @throws  DI_Exception_ContainerException
     */
    public function offsetGet( $offset ) 
    {//-------------------->> offsetGet()
        
        if ( !$this->offsetExists( $offset ) ) {//---------->> if index doesn't exist
            throw new DI_Exception_ContainerException( "No Object with the {$offset} ID was found" );
        }//---------->> End if index doesn't exist
        
        return $this->_container[ $offset ];
        
    }//-------------------->> End offsetGet()
    
    /**
     *
     * Puts the Object instance inside the Map.
     * @param   mixed $offset ID of the object in the context.
     * @param   object $value Object to be placed on the ID's index of the map.
     * @throws  DI_Exception_ContainerException
     */
    public function offsetSet( $offset, $value ) 
    {//-------------------->> offsetSet()
        
        if ( !is_object( $value ) ) {//---------->> if is not object
            throw new DI_Exception_ContainerException( 'The provided value must be an object' );
        }//---------->> End if is not object
        
        $this->_container[ $offset ] = $value;
        $this->persistContainer();
        
    }//-------------------->> End offsetSet()
    
    /**
     *
     * Unsets an Object instance from the Map by its ID.
     * @param   mixed $offset ID of the object to be erased.  
     */
    public function offsetUnset( $offset ) 
    {//-------------------->> offsetUnset()
        
        if ( $this->offsetExists( $offset ) ) {//---------->> if index exists
            unset( $this->_container[ $offset ] );
            $this->persistContainer();
        }//---------->> End if index exists
        
    }//-------------------->> offsetUnset()
    
    /**
     *
     * Retrives the internal container instance.
     * @return  ArrayIterator
     */
    public function getIterator() 
    {//-------------------->> getIterator()
        return new ArrayIterator( $this->_container );
    }//-------------------->> End getIterator()
    
    /**
     * 
     * Removes all Objects Instances from the Container.
     */
        
    public function clear() 
    {//-------------------->> clear()
        $this->_container = array();
        $this->persistContainer();
    }//-------------------->> End clear()
    
    /**
     * 
     * Method to persist the contaniner state according to its scope.
     */
    public abstract function persistContainer();
    
}//---------------------------------------->> End Interface Container