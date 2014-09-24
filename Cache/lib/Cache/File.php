<?php

/**
 * 
 * Class to make and manage cache using the file system.
 * @package     Cache
 * @category    Cache
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     1.0
 */
    class Cache_File
    {//---------------------------------------->> Class Cache_File

        /**
         * 
         * Path where the cache files will be located.
         * @var     string $_cacheDirPath
         * @static
         * @access  private
         */
        private static $_cacheDirPath = '';

        /**
         * 
         * Time expressed in seconds to define the expiration time
         * of the cache files.
         * @var     int $_expTime
         * @static
         * @access  private
         */
        private static $_expTime = 60;

        /**
         * 
         * Private constructor method to ensure only static access.
         * @access  private
         */
        private function __construct()
        {//-------------------->> __construct()

        }//-------------------->> End __construct()

        /**
         * 
         * Establishes the directory path where the cache files will be located.
         * The directory must exist and have write permissions.
         * @static
         * @param   string $cacheDirPath Directory Path expressed as string.
         * @throws  Cache_Exception
         */
        public static function setCacheDir( $cacheDirPath = '' )
        {//-------------------->> setCacheDir()

            if ( !is_dir( $cacheDirPath ) || !is_writable( $cacheDirPath ) ) {//---------->> if invalid $cacheDir
                self::_throwCacheException( "Invalid Cache Directory {$cacheDirPath}" );
            }//---------->> End if invalid $cacheDir

            self::$_cacheDirPath = (string) $cacheDirPath;

        }//-------------------->> End setCacheDir()

        /**
         * 
         * Establishes the expiration time (in seconds) of cache files.
         * An Exception will be thrown if time is less or equal to 0.
         * @static
         * @param   int $expTime Time expressed in seconds.
         * @throws  Cache_Exception
         */
        public static function setExpTime( $expTime = 0 )
        {//-------------------->> setExpTime()

            if ( (int) $expTime <= 0 ) {//---------->> if invalid $expTime
                self::_throwCacheException( "Invalid Expiration Time {$expTime}" );
            }//---------->> End if invalid $expTime

            self::$_expTime = (int) $expTime;

        }//-------------------->> End setExpTime()

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
            return (string) self::$_cacheDirPath . md5( $cacheID );
        }//-------------------->> End _getCacheFilePath()

        /**
         * 
         * Retrieves the content of a cache file without taking care about the 
         * expiration time param. 
         * If the cache file doesn't exist or doesn't have read permisions, 
         * the returned value will be an empty string.
         * @static
         * @param   string $cacheID ID of the cache file to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value. Boolean true 
         *          value by default.
         * @return  mixed
         */
        public static function getCacheFileContent( $cacheID = '', $unserialize = true )
        {//-------------------->> getCacheFileContent()

            $fileName = self::_getCacheFilePath( $cacheID );
            $cache = ( file_exists( $fileName ) && is_readable( $fileName ) ) ? base64_decode( file_get_contents( $fileName ) ) : '';
            
            return ( (bool) $unserialize == true ) ? unserialize( $cache ) : $cache;

        }//-------------------->> End getCacheFileContent()

        /**
         * 
         * 
         * Retrieves the content of a cache file taking care about the expiration 
         * time param.
         * If the cache file doesn't exist or is invalid, the returned value 
         * will be a null value.
         * @static
         * @param   string $cacheID ID of the cache file to be read.
         * @param   boolean $unserialize Flag to define if the data will be 
         *          unserialized to retrieve its original value.
         * @return  mixed
         */
        public static function getCache( $cacheID = '', $unserialize = true )
        {//-------------------->> getCache()

            $fileName = self::_getCacheFilePath( $cacheID );
            $fileTime = ( file_exists( $fileName ) && is_readable( $fileName ) ) ? ( ( fileatime( $fileName ) ) + self::$_expTime ) : 0;

            $cache = ( $fileTime > time() ) ? base64_decode( file_get_contents( $fileName ) ) : null;

            return ( (bool) $unserialize == true ) ? unserialize( $cache ) : $cache;

        }//-------------------->> End getCache()

        /**
         * 
         * Saves the received data inside an especific cache file.
         * If the cache file doesn't exist, this method will try to create it. Otherwise, 
         * the file content will be replaced with the new data definition. 
         * If the data was successfully saved, the returned value will be a true boolean value.
         * @static
         * @param   mixed $data The data to be keept inside an specific cache file.
         * @param   string $cacheID ID of the cache file where the data will 
         *          be placed.
         * @param   boolean $serialize Flag to define if the data will be 
         *          serialized and converted to string. Boolean true value by default.
         * @return  boolean
         */
        public static function save( $data = null, $cacheID = '', $serialize = true )
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

        /**
         * 
         * 
         * Throws a new Cache_Exception with the especified message to 
         * express some cache process error.
         * @static
         * @access  private
         * @param   string $message Message to be used on Exception Managment
         *          System.
         * @throws  Cache_Exception
         */
        private static function _throwCacheException( $message = '' )
        {//-------------------->> _throwCacheException()

            //---- REQUIRE ----//
                require_once dirname( __FILE__ ) . '/Exception.php';
            //-----------------//
                
            throw new Cache_Exception( (string) $message );

        }//-------------------->> End _throwCacheException()


    }//---------------------------------------->> End Class Cache_File