<?php

/**
 * 
 * Objects Factory by Reflection API.
 * @package     DI
 * @subpackage  ObjectFactory
 * @category    Object Factory
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>     
 */
class DI_ObjectFactory_Beans 
    implements DI_ObjectFactory
{//---------------------------------------->> Class Beans
    
    /**
     * 
     * Object Constructor
     */
    public function __construct() 
    {//-------------------->> __construct()
        
    }//-------------------->> End __construct()
    
    /**
     *
     * Method to dynamically build and retrieve a Bean Instance.
     * @param   string $classPath Dir Path where the Class of the Object to be 
     *          dinamically created is placed.
     * @param   string $className Name of the Class to be instantiated. If the name
     *          is send as an empty string, the Class Name will be retrived from the
     *          $classPath value.
     * @param   array $constArgs Object's Constructor arguments.
     * @throws  DI_Exception_ObjectFactoryException 
     * @throws  ReflectionException 
     * @return  Object  
     */
    public function getObject( $classPath, $className, array $constArgs = array() ) 
    {//-------------------->> getObject()
        
        $requiredClassName = ( empty( $className ) ) ? $this->_getClassNameFromPath( $classPath ) : $className;
            
        if ( !class_exists( $requiredClassName ) ) {//---------->> if class is not defined
            throw new DI_Exception_ObjectFactoryException( "The Class {$requiredClassName} is not defined" );
        }//---------->> End if class is not defined
        
        $objectClass = new ReflectionClass( $requiredClassName );
        $object = $objectClass->newInstanceArgs( $constArgs );
        
        return $object;
        
    }//-------------------->> End getObject()
    
    /**
     * 
     * Returns the name of a Class File from its Dir Path.
     * @param   string $classPath Dir Path where the Class of the Object to be 
     *          dinamically created is placed.
     * @return  string
     */
    private function _getClassNameFromPath( $classPath )
    {//-------------------->> _getClassNameFromPath()
        
        $lastPeriodIndex = strripos( $classPath, '.' );
        $lastDirSeparatorIndex = strpos( $classPath, DIRECTORY_SEPARATOR );
        
        return substr( $classPath, ( $lastDirSeparatorIndex ) ? $lastDirSeparatorIndex : 0, $lastPeriodIndex );
        
    }//-------------------->> End _getClassNameFromPath()
    
    /**
     *
     * Tries to dynamically set a property value in a bean by the corresponding 
     * public setter method.
     * @param   Object $object Instance of the object where the setter method
     *          will be invoked.
     * @param   string $propertyName Name of the property to be setted.
     * @param   mixed $value Value of the property to be setted.
     * @throws  DI_Exception_ObjectFactoryException
     */
    public function setProperty( $object, $propertyName, $value ) 
    {//-------------------->> setProperty()
        
        if ( !is_object( $object ) ) {//---------->> if is not object
            throw new DI_Exception_ObjectFactoryException( 'The received argument is not an object' );
        }//---------->> End if is not object
        
        $objectClass = new ReflectionObject( $object );
        $setterMethodName = 'set' . ucfirst( $propertyName );
        
        if ( !$objectClass->hasMethod( $setterMethodName ) ) {//---------->> if setter is not defined
            throw new DI_Exception_ObjectFactoryException( "The class {$objectClass->getName()} doesn't implement the {$setterMethodName} method" );
        }//---------->> End if setter is not defined
        
        $objectClass->getMethod( $setterMethodName )->invoke( $object, $value );
        
    }//-------------------->> End setProperty()
    
    /**
     * 
     * @param   object $object Instance of the object where the setter methods
     *          will be invoked.
     * @param   array $properties Key=>value array where Key = property name and
     *          value = new property value.
     * @throws  DI_Exception_ObjectFactoryException
     */
    public function setProperties( $object, array $properties ) 
    {//-------------------->> setProperties()
        
        foreach ( $properties as $propertyName => $propertyValue ) {//---------->> for each property
            $this->setProperty( $object, $propertyName, $propertyValue );
        }//---------->> for each property
        
    }//-------------------->> End setProperties()
    
}//---------------------------------------->> End Class Beans