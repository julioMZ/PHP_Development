<?php

/**
 * 
 * Main behaviour for Object Factories.
 * @package     DI
 * @category    Object Factory
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DI_ObjectFactory 
{//---------------------------------------->> Class ObjectFactory
    
    /**
     *
     * Method to dynamically build and retrieve an Object Instance
     * @param   string $classPath Dir Path where the Class of the Object to be 
     *          dinamically created is placed.
     * @param   string $className Name of the Class to be instantiated.
     * @param   array $constArgs Object's Constructor arguments.
     * @throws  DI_Exception_ObjectFactoryException 
     * @throws  ReflectionException 
     * @return  object
     */
    public function getObject( $classPath, $className, array $constArgs = array() );
    
    /**
     *
     * Tries to dynamically set a property value in a bean by the 
     * corresponding public setter method.
     * @param   object $object Instance of the object where the setter method
     *          will be invoked.
     * @param   string $propertyName Name of the property to be setted.
     * @param   mixed $value Value of the property to be setted.
     * @throws  DI_Exception_ObjectFactoryException 
     */
    public function setProperty( $object, $propertyName, $value );
    
    /**
     * 
     * @param   object $object Instance of the object where the setter methods
     *          will be invoked.
     * @param   array $properties Key=>value array where Key = property name and
     *          value = new property value.
     * @throws  DI_Exception_ObjectFactoryException
     */
    public function setProperties( $object, array $properties );
    
}//---------------------------------------->> End Class ObjectFactory