<?php

/**
 *
 * Main behaviour definition of CacheManager Managers
 * @category    CacheManager
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     1.0
 */
abstract class CacheManager
{//---------------------------------------->> Class CacheManager
    
    /**
     * 
     * Time expressed in seconds to define the expiration time
     * of the cache storage.
     * @var     int $_expTime
     * @access  protected
     */
    protected $_expTime = 0;
    
    /**
     * 
     * CacheManager constructor
     * @param   array $config Asociative array containing initial configuration.
     *          <p>The mandatory configuration param will be:</p>
     *          <table border="1">
     *              <tr>
     *                  <th>Key</th>
     *                  <th>Type</th>
     *                  <th>Description</th>
     *              </tr>
     *              <tr>
     *                  <td>expTime</td>
     *                  <td>int</td>
     *                  <td>
     *                      Period of time, expressed in seconds, in which the 
     *                      cache content will be valid after its creation or 
     *                      modification.
     *                  </td>
     *              </tr>
     *          </table>
     * @throws  CacheManager_Exception
     */
    public function __construct( array $config ) 
    {//-------------------->> __construct()
        $this->setExpTime( (int) $config[ 'expTime' ] );
    }//-------------------->> End __construct()
    
    /**
     * 
     * Establishes the expiration time (in seconds) of cache storage.
     * An Exception will be thrown if time is less or equal to 0.
     * @param   int $expTime Time expressed in seconds.
     * @throws  CacheManager_Exception
     */
    public function setExpTime( $expTime = 0 )
    {//-------------------->> setExpTime()

        if ( (int) $expTime <= 0 ) {//---------->> if invalid $expTime
            throw new CacheManager_Exception( "Expiration Time ({$expTime}) must be greater than 0" );
        }//---------->> End if invalid $expTime

        $this->_expTime = (int) $expTime;

    }//-------------------->> End setExpTime()
    
    /**
     * 
     * Retrieves a content form cache without taking care about the expiration 
     * time param.
     * If the cache doesn't exist, the returned value will be null.
     * @param   string $cacheID ID of the cache to be read.
     * @param   boolean $unserialize Flag to define if the data will be 
     *          unserialized to retrieve its original value. Boolean true 
     *          value by default.
     * @throws  CacheManager_Exception
     * @return  mixed
     */
    abstract public function getCacheContent( $cacheID = '', $unserialize = true );
    
    /**
     * 
     * Retrieves a content from cache taking care about the expiration 
     * time param.
     * If the cache doesn't exist or is invalid, the returned value 
     * will be a null value.
     * @static
     * @param   string $cacheID ID of the cache to be read.
     * @param   boolean $unserialize Flag to define if the data will be 
     *          unserialized to retrieve its original value.
     * @throws  CacheManager_Exception
     * @return  mixed
     */
    abstract public function getCache( $cacheID = '', $unserialize = true ); 
    
    /**
     * 
     * Saves the received data inside an especific cache persistance layer.
     * If the cache with the ID doesn't exist, this method will try to create 
     * it. Otherwise, the file content will be replaced with the new data 
     * definition. 
     * If the data was successfully saved, the returned value will be a true boolean value.
     * @param   mixed $data The data to be saved inside an specific cache ID.
     * @param   string $cacheID ID of the cache where the data will be placed.
     * @param   boolean $serialize Flag to define if the data will be 
     *          serialized and converted to string. Boolean true value by default.
     * @throws  CacheManager_Exception
     * @return  boolean
     */
    abstract public function save( $data = null, $cacheID = '', $serialize = true );
    
}//---------------------------------------->> End Class CacheManager