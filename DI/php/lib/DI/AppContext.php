<?php

/**
 * 
 * Dependency Inyection Application Context.
 * @package     DI 
 * @category    Application Context
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DI_AppContext
    implements ArrayAccess
{//---------------------------------------->> Class AppContext
    
    /**
     *
     * Application Context's Config File Parser.
     * @var     DI_ConfigFileParser
     * @access  private
     */
    private $_parser;
    
    /**
     *
     * Application Context's Config.
     * @var     array
     * @access  private   
     */
    private $_contextConfig;
    
    /**
     *
     * Application Context's Objects Factory.
     * @var     DI_ObjectFactory
     * @access  private 
     */
    private $_objFactory;
    
    /**
     *
     * Application Context entry point.
     * @param   DI_ConfigFileParser $parser Config File Parser.
     * @param   DI_ObjectFactory $objFactory Objects Factory.
     * @throws  DI_Exception_AppContextException
     */
    public function __construct( DI_ConfigFileParser $parser, DI_ObjectFactory $objFactory ) 
    {//-------------------->> __construct()
        
        $this->_parser = $parser;
        $this->_objFactory = $objFactory;
        
        $config = $this->_parser->parse();
        
        if ( !isset( $config[ 'DI_Config' ] ) ) {//---------->> if no DI_Config found
            throw new DI_Exception_AppContextException( 'No DI_Config found' );
        }//---------->> End if no DI_Config found
        
        $this->_contextConfig = $config[ 'DI_Config' ];
        
        if ( !isset (  $this->_contextConfig[ 'paths' ] ) || empty(  $this->_contextConfig[ 'paths' ] ) ) {//---------->> if no paths defined
            throw new DI_Exception_AutoloadException( 'No DI Paths Config found' );
        }//---------->> End if no paths defined
        
        DI_Autoloader::setIncludePaths( $this->_contextConfig[ 'paths' ] );
        
    }//-------------------->> End __construct()
    
    /**
     * 
     * Retrives the Context Configuration before the parsing process.
     * @return  array
     */
    function getContextConfig() 
    {//-------------------->> getContextConfig()
        return $this->_contextConfig;
    }//-------------------->> End getContextConfig()
    
    /**
     * 
     * Clears all the App Context's containers.
     * @see     DI_AppContext::getContainer()
     * @see     DI_Container::clear()
     */
    public function clear()
    {//-------------------->> clear()        
        DI_Container_Factory::getInstance()->clearContainers();
    }//-------------------->> End clear()
    
    /**
     *
     * Retrieves the instance of the internal DI_ConfigFileParser
     * @return  DI_ConfigFileParser
     */
    public function getParser() 
    {//-------------------->> getParser()
        return $this->_parser;
    }//-------------------->> End getParser()
    
    /**
     * 
     * Retrives the instance of the internal DI_ObjectFactory
     * @return  DI_ObjectFactory
     */
    function getObjFactory() 
    {//-------------------->> getObjFactory()
        return $this->_objFactory;
    }//-------------------->> End getObjFactory()
    
    /**
     *
     * Tries to get an object from its correspondig container.
     * If it doesn't exist, this method will try to build it and to
     * put it inside its corresponding container according on its configuration.
     * @param   string $objectID ID of the object in the App Context.
     * @throws  DI_Exception_AppContextException, 
     * @throws  ReflectionException
     * @throws  DI_Exception_ObjectFactoryException
     * @throws  DI_Exception_ContainerException
     * @see     DI_ObjectFactory::getObject()
     * @see     DI_ObjectFactory::setProperty()
     * @return  object
     */
    public function get( $objectID ) 
    {//-------------------->> get()
        
        $objConfig = $this->_contextConfig[ 'objects' ][ $objectID ];
        
        if ( empty( $objConfig ) ) {//---------->> if empty object config
            throw new DI_Exception_AppContextException( "There is no configuration for an object with the ID {$objectID}" );
        }//---------->> End  if empty object config
        
        $container = DI_Container_Factory::getInstance()->getContainerByString( $objConfig[ 'scope' ] );
        
        if ( $container->offsetExists( $objectID ) ) {//---------->> if Object exists
            return $container->offsetGet( $objectID );
        }//---------->> End if Object exists
        
        $className = isset( $objConfig[ 'className' ] ) ? $objConfig[ 'className' ] : '';
        
        $object = $this->_objFactory->getObject( $objConfig[ 'classPath' ] , $className, $this->_getConstArgs( $objConfig ) );
        
        $this->_objFactory->setProperties( $object, $this->_getProperties( $objConfig ) );
        
        $container->offsetSet( $objectID, $object );
        
        return $object;
        
    }//-------------------->> End get()
    
    /**
     * 
     * Verifies if the Object with one ID equal to $offset value is setted in any
     * of the current containers.
     * <p>
     *  If an object has not been created by DI_AppContext::get() or by recursive 
     *  call of the same method before this method invocation, the result will be 
     *  a boolean false value.
     * </p>
     * @param   mixed $offset ID of the Object to be verified on its existance.
     * @example $appContext->offsetExists( 'objectID' ); //$appContext is a DI_AppContext instance
     * @example isset( $appContext[ 'objectID' ] ); //$appContext is a DI_AppContext instance
     * @return  bool
     */
    public function offsetExists( $offset )
    {//-------------------->> offsetExists()
        
        $currentContainers = DI_Container_Factory::getInstance()->getCurrentContainers();
        
        foreach ( $currentContainers as $container ) {//---------->> foreach $container
            
            if ( $container->offsetExist( $offset ) ) {//---------->> if offset exists in container
                return true;
            }//---------->> End if offset exists in container
            
        }//---------->> End foreach $container
        
        return false;
        
    }//-------------------->> End offsetExists()

    /**
     * 
     * Removes the Object with the ID  equal to $offset value from one container if
     * it has been created before.
     * <p>
     *  This method is very useful when you wanna restart some Object with a 
     *  Session or Application scope.
     * </p>
     * @param   mixed $offset ID of the Object to be unsetted.
     * @example $appContext->offsetUnset( 'objectID' ); //$appContext is a DI_AppContext instance
     * @example unset( $appContext[ 'objectID' ] ); //$appContext is a DI_AppContext instance
     */
    public function offsetUnset( $offset )
    {//-------------------->> offsetUnset()
        
        $currentContainers = DI_Container_Factory::getInstance()->getCurrentContainers();
        
        foreach ( $currentContainers as $container ) {//---------->> foreach $container
            
            if ( $container->offsetExist( $offset ) ) {//---------->> if offset exists in container
                $container->offsetUnset( $offset );
            }//---------->> End if offset exists in container
            
        }//---------->> End foreach $container
        
    }//-------------------->> End offsetUnset()
    
    /**
     * 
     * Alias of DI_AppContext::get() to allow Objects access as array access.
     * @param   mixed $offset ID of the object in the App Context.
     * @return  object
     * @see     DI_AppContext::get()
     * @example $appContext->offsetGet( 'objectID' ); //$appContext is a DI_AppContext instance
     * @example $appContext[ 'objectID' ]; //$appContext is a DI_AppContext instance
     */
    public function offsetGet( $offset )
    {//-------------------->> offsetGet()
        return $this->get( $offset );
    }//-------------------->> End offsetGet()
    
    /**
     * 
     * Alias of DI_AppContext::get() to allow Object access as public properties.
     * @param   string $name ID of the object in the App Context.
     * @return  object
     * @see     DI_AppContext::get()
     * @example $appContext->__get( 'objectID' ); //$appContext is a DI_AppContext instance
     * @example $appContext->objectID; //$appContext is a DI_AppContext instance
     */
    public function __get( $name ) 
    {//-------------------->> __get()
        return $this->get( $name );
    }//-------------------->> End __get()
    
    /**
     * 
     * This method denies the action to set an Object by array access.
     * @param   mixed $offset ID of the object to be setted.
     * @param   mixed $value Object instance.
     * @throws  DI_Exception_AppContextException
     * @example $appContext->offsetSet( 'objectID', $objectInstance ); //$appContext is a DI_AppContext instance
     * @example $appContext[ 'objectID' ] = $objectInstance; //$appContext is a DI_AppContext instance
     */
    public function offsetSet( $offset, $value )
    {//-------------------->> offsetSet()
        throw new DI_Exception_AppContextException( "The {$offset} Object could not be setted by the AppContext. Try to define it on config file" );
    }//-------------------->> End offsetSet()
    
     /**
     * 
     * This method denies the action to set an Object by object access.
     * @param   string $name ID of the object to be setted.
     * @param   mixed $value Object instance.
     * @throws  DI_Exception_AppContextException
     * @example $appContext->__set( 'objectID', $objectInstance ); //$appContext is a DI_AppContext instance
     * @example $appContext->objectID = $objectInstance; //$appContext is a DI_AppContext instance
     */
    public function __set( $name, $value ) 
    {//-------------------->> __set()
        $this->offsetSet( $name, $value );
    }//-------------------->> End __set()
    
    /**
     *
     * Builds the Constructor Arguments array for the dinamic constructor
     * invocation.
     * @access  private
     * @param   array $objConfig Object Configuration Array.
     * @return  array
     * @throws  ReflectionException
     * @throws  DI_Exception_ObjectFactoryException
     */
    private function _getConstArgs( array $objConfig )
    {//-------------------->> _getConstArgs()
        
        $constArgs = ( isset( $objConfig[ 'constArg' ] ) ) ? (array) $objConfig[ 'constArg' ] : array();
        $this->_setReferenceProperties( $constArgs );
        
        return $constArgs;
        
    }//-------------------->> End _getConstArgs()
    
    /**
     *
     * Retrives the properties names and values of an object since 
     * its configuration.
     * @access  private
     * @param   array $objConfig 
     * @return  array
     * @throws  ReflectionException
     * @throws  DI_Exception_ObjectFactoryException
     */
    private function _getProperties( array $objConfig )
    {//-------------------->> _getProperties()
        
        $setProp = ( isset( $objConfig[ 'setProperty' ] ) ) ? (array) $objConfig[ 'setProperty' ] : array();
        $this->_setReferenceProperties( $setProp );
        
        return $setProp;
        
    }//-------------------->> End _getProperties()
    
    /**
     *
     * Tries to change, by reference, the values of the properties who point 
     * to another object in the Application Context for the corresponding 
     * instance in order to execute the Dependency Inyection by constructor 
     * or setter method invocation.
     * @access  private
     * @param   array $properties Associative array of constructor's arguments or
     *          object's properties.
     * @throws  ReflectionException
     * @throws  DI_Exception_ObjectFactoryException
     */
    private function _setReferenceProperties( array &$properties ) 
    {//-------------------->> _setReferenceProperties()
        
        foreach ( $properties as $property => $value ) {//---------->> for each object's property
            
            if ( is_array( $value ) ) {//---------->> if value is an array
                
                if ( !isset( $value[ 'ref' ] ) ) {//---------->> if value is a reference
                    
                    $this->_setReferenceProperties( $value );
                    $properties[ $property ] = $value;
                    
                } else {//---------->> else if value is not a reference
                    $properties[ $property ] = $this->get( $value[ 'ref' ] );
                }//---------->> End else if value is not a reference
                
            }//---------->> End if value is an array
            
        }//---------->> End for each object's property
        
    }//-------------------->> End _setReferenceProperties()
    
}//---------------------------------------->> End Class AppContext