<?php

/**
 * 
 * Session Scope App Objects Container.
 * @package     DI
 * @subpackage  Container
 * @category    Objects Container
 * @version     1.0
 * @uses        DI_Container
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DI_Container_SessionContainer
    extends DI_Container
{//---------------------------------------->> Class SessionContainer
    
    /**
     * 
     * Constant Variable to define the App
     * Container ID on the shared memory.
     */
    const CONTAINER_ID = 'DI_SESSION_CONTAINER';
    
    /**
     * 
     * Object Constructor
     */
    public function __construct() 
    {//-------------------->> __construct()
        session_start();
        $this->_container = ( isset( $_SESSION[ self::CONTAINER_ID ] ) ) ? (array) $_SESSION[ self::CONTAINER_ID ] : array();
    }//-------------------->> End __construct()
    
    /**
     * 
     * Persist the actual container state in the session cookie.
     */
    public function persistContainer()
    {//-------------------->> _persistContainer()
        $_SESSION[ self::CONTAINER_ID ] = $this->_container;
    }//-------------------->> End _persistContainer()
    
    /**
     * 
     * Application Container Destructor Method to persist the Objects
     * State with posible changes in the shared memory.
     */
    public function __destruct() 
    {//-------------------->> __destruct()
        $this->persistContainer();
    }//-------------------->> End __destruct()
    
}//---------------------------------------->> End Class SessionContainer