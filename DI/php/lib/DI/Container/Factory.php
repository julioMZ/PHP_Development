<?php

/**
 * 
 * Factory of DI_Containers concrete instances.
 * @package     DI
 * @subpackage  Container
 * @category    Containers Factory
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     0.1
 */
    class DI_Container_Factory 
    {//---------------------------------------->> Class CacheManager_Factory
            
        /**
         * 
         * Application Container ID
         */
        const APP_CONTAINER = 1;

        /**
         * 
         * Request Container ID
         */
        const REQUEST_CONTAINER = 2;

        /**
         * 
         * Session Container ID
         */
        const SESSION_CONTAINER = 3;
        
        /**
         *
         * Singleton Instance
         * @var     DI_Container_Factory
         * @static
         * @access  private
         */
        private static $_instance;
        
        /**
         *
         * Containers Collection.
         * @static
         * @var     array
         * @access  private
         */
        private static $_containers = array();
        
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
         * Retrieves one and only one DI_Container_Factory instance.
         * (Singleton Design Pattern Implementation)
         * @static
         * @return  DI_Container_Factory
         */
        public static function getInstance()
        {//-------------------->> getInstance()

            if ( is_null( self::$_instance ) ) {//---------->> if empty instance
                self::$_instance = new self();
            }//---------->> End if empty instance

            return self::$_instance;

        }//-------------------->> End getInstance()
        
        /**
         * 
         * @param   int $containerType 
         * @throws  DI_Exception_ContainerException
         * @return  DI_Container
         */
        public function getContainer( $containerType = 0 )
        {//-------------------->> getCacheManager()
            
            settype( $containerType, 'int' );
            
            if ( !isset( self::$_containers[ $containerType ] ) ) {//---------->> if container doesn't exist
                self::$_containers[ $containerType ] = self::getContainerInstance( $containerType );
            }//---------->> End if container doesn't exist
        
            return self::$_containers[ $containerType ];
            
        }//-------------------->> End getCacheManager()
        
        /**
         * 
         * @param   int $containerType
         * @return  DI_Container
         * @throws  DI_Exception_ContainerException
         */
        private function getContainerInstance( $containerType = 0 )
        {//-------------------->> getContainerInstance()
            
            $requiredClassName = 'DI_Container_';
            
            switch ( $containerType ) {//---------->> switch scope type

                case self::APP_CONTAINER:
                    $requiredClassName = "{$requiredClassName}AppContainer";
                break;

                case self::REQUEST_CONTAINER:
                    $requiredClassName = "{$requiredClassName}RequestContainer";
                break;

                case self::SESSION_CONTAINER:
                    $requiredClassName = "{$requiredClassName}SessionContainer";
                break;

                default :
                    throw new DI_Exception_ContainerException( "Invalid Scope ID {$containerType}" );

            }//---------->> End switch scope type
            
            $requiredClass = new ReflectionClass( $requiredClassName ); 
            
            if ( !$requiredClass->isSubclassOf( 'DI_Container' ) ) {//---------->> if $requiredClass is not CacheManager subclass 
                throw new DI_Exception_ContainerException( "The class {$requiredClassName} is not a subclass of DI_Container" );
            }//---------->> End if $requiredClass is not CacheManager subclass
            
            return $requiredClass->newInstance();
            
        }//-------------------->> End getContainerInstance()
        
            
        /**
         *
         * Retrieves a concrete DI_Container instance since a string definition.
         * @static
         * @param   array $scopeName  Name of the scope.
         * @return  object
         * @uses    DI_Container_Factory::getContainer()
         */
        public function getContainerByString( $scopeName = '' )
        {//-------------------->> getContainerByString()

            switch ( strtoupper( $scopeName ) ) {//---------->> switch $scopeName

                case 'APP':
                case 'APPLICATION':
                case self::APP_CONTAINER:
                    return self::getContainer( self::APP_CONTAINER );

                case 'SESSION':
                case self::SESSION_CONTAINER:
                    return self::getContainer( self::SESSION_CONTAINER );

                case 'REQUEST':
                default:
                    return self::getContainer( self::REQUEST_CONTAINER );

            }//---------->> End switch $scopeName

        }//-------------------->> End getContainerByString()
        
        /**
         * 
         * Invokes the DI_Container::clear() method on each register inside the
         * factory.
         */
        public function clearContainers()
        {//-------------------->> clearContainers()
            
            foreach ( self::$_containers as $container ) {//---------->> foreach $container
                $container->clear();
            }//---------->> End foreach $container
            
        }//-------------------->> End clearContainers()
        
        /**
         * 
         * Returns the array with the DI_Container concrete instances made until
         * its invocation.
         * @return  array
         */
        public function getCurrentContainers()
        {//-------------------->> getCurrentContainers()
            return self::$_containers;
        }//-------------------->> End getCurrentContainers()
        
        /**
         * 
         * Private __clone action.
         * @acces   private
         */
        private function __clone() 
        {//-------------------->> __clone()
            
        }//-------------------->> End __clone()
        
    }//---------------------------------------->> End Class CacheManager_Factory