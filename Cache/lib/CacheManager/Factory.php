<?php

/**
 * 
 * Factory of CacheManager concrete instances.
 * @package     CacheManager
 * @category    Cache
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     0.1
 */
    class CacheManager_Factory 
    {//---------------------------------------->> Class CacheManager_Factory
        
        /**
         * 
         * Private Constructor to ensure only static access.
         * @acces   private
         */
        private function __construct() 
        {//-------------------->> __construct()
            
        }//-------------------->> End __construct()
        
        /**
         * 
         * @static
         * @param   array $config Asociative array containing constructor configuration.
         *          <p>The mandatory configuration params will be:</p>
         *          <table border="1">
         *              <tr>
         *                  <th>Key</th>
         *                  <th>Type</th>
         *                  <th>Description</th>
         *              </tr>
         *              <tr>
         *                  <td>type</td>
         *                  <td>string</td>
         *                  <td>
         *                      Type of the concrete CacheManager instance to be retrived.
         *                  </td>
         *              </tr>
         *              <tr>
         *                  <td>consArgs</td>
         *                  <td>array</td>
         *                  <td>
         *                      Asosiative array to be passed as CacheManager constructor param.
         *                  </td>
         *              </tr>
         *          </table>
         * @param   string $type
         * @throws  CacheManager_Exception
         * @return  CacheManager
         */
        public static function getCacheManager( array $config )
        {//-------------------->> getCacheManager()
            
            $type = ucfirst( strtolower( $config[ 'type' ] ) );
            $className = "CacheManager_{$type}";
            
            $requiredClass = new ReflectionClass( $className ); 
            
            if ( !$requiredClass->isSubclassOf( 'CacheManager' ) ) {//---------->> if $requiredClass is not CacheManager subclass 
                throw new CacheManager_Exception( "The class {$className} is not a subclass of CacheManager" );
            }//---------->> End if $requiredClass is not CacheManager subclass
            
            return $requiredClass->newInstanceArgs( array( $config[ 'consArgs' ] ) );
            
        }//-------------------->> End getCacheManager()
        
    }//---------------------------------------->> End Class CacheManager_Factory
