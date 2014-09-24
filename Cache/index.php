<?php

/**
 * 
 * Cache demo script
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */

     //---- DEFINE ----//
        define( 'BASE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' . DIRECTORY_SEPARATOR );
            define( 'RESOURCES_PATH', BASE_PATH . 'resources' . DIRECTORY_SEPARATOR  );
            define( 'VIEWS_PATH', BASE_PATH . 'views' . DIRECTORY_SEPARATOR );
     //-----------------//
     
     //---- INCLUDE PATHS ----//  
        set_include_path( 
            get_include_path() . PATH_SEPARATOR . 
            BASE_PATH . PATH_SEPARATOR . 
            LIB_PATH . PATH_SEPARATOR . 
            RESOURCES_PATH . PATH_SEPARATOR . 
            VIEWS_PATH
        ); 
     //-----------------------//
        
     //---- AUTOLOAD ----//
        
        function __autoload( $className ) 
        {//-------------------->> __autoload()
            
            $classPath = str_replace( '_', DIRECTORY_SEPARATOR, $className );
            
            //---- REQUIRE ----//
                require_once "{$classPath}.php";
            //-----------------//
            
        }//-------------------->> End __autoload()
        
    try {//-------------------->> try
        
        //---- Cache System Config ----//
            Cache_File::setCacheDir( RESOURCES_PATH . 'tmp' . DIRECTORY_SEPARATOR );
            Cache_File::setExpTime( 10 );
        //-----------------------------//
        
        //---- Simple Data Example ----//
            $cacheID = 'time';
            $data = date( 'H:i:s' );

            //Retriving cache file content without taking care about expiration time
            $cacheFileData = Cache_File::getCacheFileContent( $cacheID );

            //Retriving cache file content taking care about expiration time
            $cacheData = Cache_File::getCache( $cacheID );

            if ( Cache_File::getCache( $cacheID ) == null ) {//-------------------->> if cache has expired

                $cacheData = $data;
                Cache_File::save( $cacheData, $cacheID );

            }//-------------------->> End if cache has expired
        //----------------------------//
            
        //---- Serialized Data Example ----//
            $cacheID = 'timeArray';

            //Retriving cache file content without taking care about expiration time
            $serializedCacheFileData = Cache_File::getCacheFileContent( $cacheID );

            //Retriving cache file content taking care about expiration time
            $serializedCacheData = Cache_File::getCache( $cacheID );

            if ( Cache_File::getCache( $cacheID ) == null ) {//-------------------->> if cache has expired

                $serializedCacheData = array( 'time' => $data );
                Cache_File::save( $serializedCacheData, $cacheID );

            }//-------------------->> End if cache has expired
        //----------------------------//
        
        //---- Include View ----//
            include 'includeViewExample.php';
        //----------------------//
        
    } catch ( Exception $exc ) {//-------------------->> catch
        echo $exc->getTraceAsString();
    }//-------------------->> End try/catch
