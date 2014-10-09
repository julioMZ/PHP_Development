<?php

/**
 * 
 * Class to make and manage cache using Data Bases as persistance layer.
 * @package     CacheManager
 * @category    Cache
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     0.1
 */
    class CacheManager_Db
        extends CacheManager
    {//---------------------------------------->> Class CacheManager_Db
        
        /**
         *
         * PDO Instance to allow access to the DB Persistance layer.
         * @var     PDO $_pdoInstance
         * @acces   private
         */
        private $_pdoInstance;
        
        /**
         *
         * Name of the table where the cache data will be saved.
         * @var     string $_tableName
         * @acces   private
         */
        private $_tableName;
        
        /**
         * 
         * 
         * Data Base Cache Manager Constructor
         * @param   array $config Asociative array containing initial configuration.
         *          <p>The mandatory configuration params will be:</p>
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
         *              <tr>
         *                  <td>pdoInstance</td>
         *                  <td>PDO</td>
         *                  <td>PDO Object instance to acces into the DB persistance layer.</td>
         *              </tr>
         *              <tr>
         *                  <td>tableName</td>
         *                  <td>string</td>
         *                  <td>Name of the data base table where the cache data is available.</td>
         *              </tr>
         *          </table>
         * @throws  CacheManager_Exception
         * @see     CacheManager::__construct()
         * @see     CacheManager_Db::setPdoInstance()
         * @see     CacheManager_Db::setTableName()
         */
        public function __construct( array $config )
        {//-------------------->> __construct()
            
            parent::__construct( $config );
            $this->setPdoInstance( $config[ 'pdoInstance' ] );
            $this->setTableName( $config[ 'tableName' ] );
            
        }//-------------------->> End __construct()
        
        /**
         * 
         * Get the internal PDO Instance. 
         * @return  PDO
         */
        public function getPdoInstance() 
        {//-------------------->> getPdoInstance()
            return $this->_pdoInstance;
        }//-------------------->> End getPdoInstance()
        
        /**
         * 
         * Get the name of the Table where the cache data will be saved and
         * retrived.
         * @return  string
         */
        public function getTableName()
        {//-------------------->> getTableName()
            return $this->_tableName;
        }//-------------------->> End getTableName()
        
        /**
         * 
         * PDO Dependency Inyection by setter method.
         * @param PDO $_pdoInstance
         */
        public function setPdoInstance( PDO $_pdoInstance ) 
        {//-------------------->> setPdoInstance()
            $this->_pdoInstance = $_pdoInstance;
        }//-------------------->> End setPdoInstance()

        /**
         * 
         * Tries to stablish the name of the table where the cahce data will be saved and
         * retrived.
         * An CacheManager_Exception will be thrown if the recived param is an empty string.
         * @param   string $tableName Name of the table where the cache data will 
         *          be saved and retrived.
         * @throws  CacheManager_Exception
         */
        public function setTableName( $tableName = '' ) 
        {//-------------------->> setTableName()
            
            $tableName = trim( $tableName );
            
            if ( strlen( $tableName ) == 0  ) {//---------->> if empty $tableName
                throw new CacheManager_Exception( "{$tableName} is not a valid DB Table Name" );
            }//---------->> End if empty $tableName
            
            $this->_tableName = $tableName;
            
        }//-------------------->> End setTableName()
        
        /**
         * 
         * Builds and executes dynamically an SELECT SQL query to the Data Base
         * Table.
         * @param   array $fields String array with the name of the fields to
         *          be included on the SELECT SQL query.
         * @param   string $cacheID Cache ID to be searched.
         * @return  array|false
         */
        private function _getCacheData( array $fields, $cacheID = '' )
        {//-------------------->> _getCacheData()
            
            $queryFields = implode( ',', $fields );
            $sqlQuery = "SELECT {$queryFields} FROM {$this->_tableName} WHERE id = :id";
            $statement = $this->_pdoInstance->prepare( $sqlQuery );
            $statement->bindParam( ':id', md5( $cacheID ), PDO::PARAM_STR );
            $statement->execute();
            
            return $statement->fetch( PDO::FETCH_ASSOC );
            
        }//-------------------->> End _getCacheData()
        
        /**
         * 
         * Process the Cache Data according to the user requirements.
         * @param   array $cacheData
         * @param   boolean $unserialize
         * @throws  CacheManager_Exception
         * @return  mixed
         */
        private function _processCacheData( array $cacheData, $unserialize = true )
        {//-------------------->> _processCacheData()
            
            if ( !isset( $cacheData[ 'content' ] ) ) {//---------->> if not content
                throw new CacheManager_Exception( 'No Cache Data Content to Process' );
            }//---------->> End if not content
            
            $cacheData = base64_decode( $cacheData[ 'content' ] );
            return ( $unserialize == true )? unserialize( $cacheData ) : $cacheData;
            
        }//-------------------->> End _processCacheData()
            
        /**
         * 
         * 
         * Retrieves the content of a cache file taking care about the expiration 
         * time param.
         * If the cache doesn't exist or is invalid, the returned value 
         * will be a null value.
         * @param   string $cacheID ID of the cache file to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value.
         * @return  mixed
         */
        public function getCache( $cacheID = '', $unserialize = true ) 
        {//-------------------->> getCache()
            
            $cacheData = $this->_getCacheData( array( 'content', 'updated' ), $cacheID );
            
            if ( $cacheData == false ) {//---------->> if cache doesn't exist
                return null;
            }//---------->> if cache doesn't exist
            
            $lastUpdated = strtotime( $cacheData[ 'updated' ] ) + $this->_expTime;
            
            return ( $lastUpdated > time() ) ? $this->_processCacheData( $cacheData, $unserialize ) : null;
            
        }//-------------------->> End getCache()

        /**
         * 
         * Retrieves the content form cache without taking care about the 
         * expiration time param. 
         * If the cache doesn't exis, the returned value will be null.
         * @param   string $cacheID ID of the cache to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value. Boolean true 
         *          value by default.
         * @return  mixed
         */
        public function getCacheContent( $cacheID = '', $unserialize = true ) 
        {//-------------------->> getCacheContent()
            
            $cacheData = $this->_getCacheData( array( 'content' ), $cacheID );
            
            if ( $cacheData == false ) {//---------->> if cache doesn't exist
                return null;
            }//---------->> if cache doesn't exist
            
            return $this->_processCacheData( $cacheData, $unserialize );
            
        }//-------------------->> End getCacheContent()
        
        /**
         * 
         * Saves the received data inside an especific cache file.
         * If the cache file doesn't exist, this method will try to create it. Otherwise, 
         * the file content will be replaced with the new data definition. 
         * If the data was successfully saved, the returned value will be a true boolean value.
         * @param   mixed $data The data to be keept inside an specific cache file.
         * @param   string $cacheID ID of the cache file where the data will 
         *          be placed.
         * @param   boolean $serialize Flag to define if the data will be 
         *          serialized and converted to string. Boolean true value by default.
         * @return  boolean
         */
        public function save( $data = null, $cacheID = '', $serialize = true )
        {//-------------------->> save()
            
            if ( (bool) $serialize == true ) {//---------->> if serialize data
                $data = serialize( $data );
            }//---------->> End if serialize data
            
            $cacheData = $this->_getCacheData( array( 'COUNT(id)' ), $cacheID );
            $exists = ( (int) $cacheData[ 'COUNT(id)' ] > 0 );
            
            $sqlQuery = '';
            
            if ( $exists ) {//---------->> if cache exists
                $sqlQuery = "UPDATE {$this->_tableName} SET content = :content WHERE id = :id";
            } else {//---------->> else if cache doesn't exist
                $sqlQuery = "INSERT INTO {$this->_tableName} ( id, content ) VALUES ( :id, :content )";
            }//---------->> End if cache exists
            
            $statement = $this->_pdoInstance->prepare( $sqlQuery );
            $statement->bindParam( ':id', md5( $cacheID ), PDO::PARAM_STR );
            $statement->bindParam( ':content', base64_encode( $data ), PDO::PARAM_STR );
            
            if ( !$statement->execute() ) {//---------->> if error saving data
                
                $errorData = $statement->errorInfo();
                throw new CacheManager_Exception( "Error Saving Cache Data: {$errorData[ 2 ]}" );
                
            }//---------->> End if error saving data
            
            return ( $statement->rowCount() > 0 );
            
        }//-------------------->> End save()

    }//---------------------------------------->> Class End CacheManager_Db
