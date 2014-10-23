<?php

/**
 * 
 * Application Scope App Objects Container.
 * @package     DI
 * @subpackage  Container    
 * @category    Objects Container
 * @version     1.0
 * @uses        DI_Container
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DI_Container_AppContainer 
    extends DI_Container
{//---------------------------------------->> Class AppContainer
    
    /**
     * 
     * Constant Variable to define the App
     * Container ID on the shared memory.
     */
    const CONTAINER_ID = 1;
    
    /**
     *
     * Semaphore ID.
     * @var     resource
     * @access  private
     */
    private $_semID;
    
    /**
     *
     * Shared Memory ID.
     * @var     resource
     * @access  private
     */
    private $_shMemID;
    
    /**
     * 
     * Object Constructor
     * @throws DI_Exception_ContainerException
     */
    public function __construct() 
    {//-------------------->> __construct()
        
        if ( !function_exists( 'sem_get' ) || 
             !function_exists( 'shm_attach' ) ) {//---------->> if not shared memory functions available
            throw new DI_Exception_ContainerException( 'The DI_Container_AppContainer can\'t be used because Shared Memory - Semaphore functions are not available' );
        }//---------->> if not shared memory functions available
        
        $this->_semID = sem_get( 0xee3, 1, 0666 );
        $this->_shMemID = shm_attach( 50, 10000, 0666 );

        if ( empty( $this->_shMemID ) ) {//---------->> if invalid id
            throw new DI_Exception_ContainerException( 'Could not Access Shared Memory' );
        }//---------->> End if invalid id
        
        $this->_container = ( shm_has_var( $this->_shMemID, self::CONTAINER_ID ) ) ? shm_get_var( $this->_shMemID, self::CONTAINER_ID ) : array();
        
    }//-------------------->> End __construct()
    
    /**
     * 
     * Persist the actual container state in the shared memory.
     */
    public function persistContainer()
    {//-------------------->> _persistContainer()
        
        if ( !function_exists( 'sem_acquire' ) || 
             !function_exists( 'sem_release' ) || 
             !function_exists( 'shm_put_var' ) ) {//---------->> if not shared memory functions available
            throw new DI_Exception_ContainerException( 'The DI_Container_AppContainer can\'t be used because Shared Memory - Semaphore functions are not available' );
        }//---------->> if not shared memory functions available
        
        sem_acquire( $this->_semID );
        
            shm_put_var( $this->_shMemID, self::CONTAINER_ID, $this->_container );
            
        sem_release( $this->_semID );
        
    }//-------------------->> End _persistContainer()
    
    /**
     * 
     * Application Container Destructor Method to persist the Objects
     * State with posible changes in the shared memory.
     */
    public function __destruct() 
    {//-------------------->> __destruct()
        $this->persistContainer();
        shm_detach( $this->_shMemID );
    }//-------------------->> End __destruct()
    
}//---------------------------------------->> End Class AppContainer