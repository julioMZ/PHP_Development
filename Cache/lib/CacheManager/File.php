<?php

/**
 * 
 * Class to make and manage cache using the file system as persistance layer.
 * @package     CacheManager
 * @category    Cache
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     1.1
 */
    class CacheManager_File
        extends CacheManager
    {//---------------------------------------->> Class CacheManager_File

        /**
         * 
         * Base Path where all the cache files could be located.
         * @var     string $_baseRepoPath
         * @static
         * @access  private
         */
        private static $_baseRepoPath = '';

        /**
         * 
         * Establishes the directory path where the cache files will be located.
         * The directory must exist and have read and write permissions.
         * @static
         * @param   string $cacheDirPath Directory Path expressed as string.
         * @throws  CacheManager_Exception
         */
        public static function setBaseRepositoryPath( $cacheDirPath = '' )
        {//-------------------->> setBaseRepositoryPath()

            if ( !is_dir( $cacheDirPath ) || !is_readable( $cacheDirPath ) || !is_writable( $cacheDirPath ) ) {//---------->> if invalid $cacheDir
                throw new CacheManager_Exception( "Invalid Cache Directory {$cacheDirPath}" );
            }//---------->> End if invalid $cacheDir

            self::$_baseRepoPath = (string) $cacheDirPath;

        }//-------------------->> End setBaseRepositoryPath()

        /**
         * 
         * Retrives the absolute file path from an especific cache file.
         * @static
         * @access  private
         * @param   string $cacheID ID of the cache file.
         * @return  string
         */
        private static function _getCacheFilePath( $cacheID = '' )
        {//-------------------->> _getCacheFilePath()
            return (string) self::$_baseRepoPath . md5( $cacheID );
        }//-------------------->> End _getCacheFilePath()

        /**
         * 
         * 
         * Retrieves the content of a cache file taking care about the expiration 
         * time param.
         * If the cache file doesn't exist or is invalid, the returned value 
         * will be a null value.
         * @param   string $cacheID ID of the cache file to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value.
         * @return  mixed
         */
        public function getCache( $cacheID = '', $unserialize = true )
        {//-------------------->> getCache()

            $fileName = self::_getCacheFilePath( $cacheID );
            $fileTime = ( file_exists( $fileName ) && is_readable( $fileName ) ) ? ( ( fileatime( $fileName ) ) + $this->_expTime ) : 0;

            $cache = ( $fileTime > time() ) ? base64_decode( file_get_contents( $fileName ) ) : null;

            return ( (bool) $unserialize == true ) ? unserialize( $cache ) : $cache;

        }//-------------------->> End getCache()
                
        /**
         * 
         * Retrieves the content form cache without taking care about the 
         * expiration time param. 
         * If the cache file doesn't exist or doesn't have read permisions, 
         * the returned value will be a null value.
         * @param   string $cacheID ID of the cache file to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value. Boolean true 
         *          value by default.
         * @return  mixed
         */
        public function getCacheContent( $cacheID = '', $unserialize = true )
        {//-------------------->> getCacheContent()

            $fileName = self::_getCacheFilePath( $cacheID );
            $cache = ( file_exists( $fileName ) && is_readable( $fileName ) ) ? base64_decode( file_get_contents( $fileName ) ) : null;
            
            return ( (bool) $unserialize == true ) ? unserialize( $cache ) : $cache;

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
            
            $fileName = self::_getCacheFilePath( $cacheID );

            if ( file_exists( $fileName ) ) {//---------->> if file_exist
                unlink( $fileName );
            }//---------->> End if file_exist

            if ( (bool) $serialize == true ) {//---------->> if serialize data
                $data = serialize( $data );
            }//---------->> End if serialize data

            $cacheFile = fopen( $fileName, 'w' );
            fwrite( $cacheFile, base64_encode( $data ) );
            fclose( $cacheFile );

            return file_exists( $fileName );
            
        }//-------------------->> End save()

    }//---------------------------------------->> End Class CacheManager_File