<?php

/**
 *
 * Object to parse .xml files in order to obtain the DI configuration.
 * @package     DI
 * @subpackage  ConfigFileParser
 * @category    Configuration File Parser
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @uses        DI_ConfigFileParser
 */
class DI_ConfigFileParser_XML
    extends DI_ConfigFileParser
{//---------------------------------------->> Class XML

    public function validateFile( $filePath, $ext )
    {//-------------------->> validateFile()
        
        if ( $ext != 'xml' ) {//---------->> if invalid file type
            return array( 'isValid' => false, 'message' => 'The Config File is not a XML file' );
        }//---------->> End if invalid file type
        
        $dom = new DOMDocument();
        $dom->load( $filePath );
        libxml_use_internal_errors( true );
        
        if ( !$dom->validate() ) {//---------->> if invalid XML file
            
            $errors = array();
            $errorList = libxml_get_errors();
            
            foreach ( $errorList as $error ) {
                $errors[] = "{$error->message} in line {$error->line}";
            }
            
            $errors = implode( ', ', $errors );
            
            return array( 'isValid' => false, 'message' => "The XML file {$filePath} is not valid and has the following errors: {$errors}" );
            
        }//---------->> End if invalid XML file
        
        return array( 'isValid' => true );

    }//-------------------->> End validateFile()
    
    
    /**
     * 
     * Parses the Context Paths and Packages from file config.
     * @access  private
     * @param   array $pathsConf The associative array representing the paths config
     *          from config file.
     * @return  array
     */
    private function _parsePathsConfig( array $pathsConf )
    {//-------------------->> _parsePathsConfig()
        
        $paths = array();
        
        foreach( $pathsConf as $path ) {//---------->> foreach $path
            
            $type =  (string) $path[ 'type' ];
            $id = isset( $path[ 'id' ] ) ? (string) $path[ 'id' ] : null;
            $value = (string) $path[ 'value' ];
            
            ( $type != 'contextPath') ? ( $id != null ) ? $paths[ 'packages' ][ $id ] =  $value: $paths[ 'packages' ][ ] = $value : $paths[ $type ] = $value;
            
        }//---------->> End foreach $path
        
        return $paths;
        
    }//-------------------->> End _parsePathsConfig()
    
    /**
     * 
     * Parses the Objects constructors and set property methods configuration 
     * from file config.
     * @access  private
     * @param   array $objectsConf The associative array representing the objects config
     *          from config file.
     * @return  array
     * @see     DI_ConfigFileParser_XML::_getConstArgs()
     * @see     DI_ConfigFileParser_XML::_getSetProperty()
     */
    private function _parseObjectsConfig( array $objectsConf )
    {//-------------------->> _parseObjectsConfig()

        $objects = array();

        foreach( $objectsConf as $object ) {//---------->> foreach $object

            $id = (string) $object[ 'id' ];
            
            $objects[ $id ][ 'classPath' ] = (string) $object[ 'classPath' ];
            
            if ( isset( $object[ 'className' ] ) && !empty( $object[ 'className' ] ) ) {//---------->> if className found
                $objects[ $id ][ 'className' ] = (string) $object[ 'className' ];
            }//---------->> End if className found
            
            $constArgs = (array) $object->constArgs;
            $objects[ $id ][ 'constArg' ] = $this->_getConstArgs( $constArgs );

            $objects[ $id ][ 'setProperty' ] = array();
            $setProperties = (array) $object->setProperties;
            $this->_getSetProperty( $setProperties, $objects[ $id ][ 'setProperty' ] );
            
            $objects[ $id ][ 'scope' ] = (string) $object[ 'scope' ];

        }//---------->> End foreach $object
        
        return $objects;

    }//-------------------->> End _parseObjectsConfig()
    
    /**
     * 
     * Builds and retrieves the Constructor Params configuration.
     * @access  private
     * @param   array $constArgs The associative array representing the constructor
     *          params configuration from an object.
     * @return  mixed
     * @see     DI_ConfigFileParser_XML::_getArgumentValue()
     */
    private function _getConstArgs( array $constArgs ) 
    {//-------------------->> _getConstArgs()
        
        $args = array();
        
        if ( !empty( $constArgs ) ) {//---------->> if not empty $constArgs
            
            foreach( (array) $constArgs[ 'constArg' ] as $constArg ) {//---------->> foreach $constArg
                
                if ( !is_array( $constArgs[ 'constArg' ] ) ) {//---------->> if $constArg has possibly content value in XML
                    $constArg[ 'contentValue' ] = ( !empty( (string) $constArgs[ 'constArg' ] ) ) ? (string) $constArgs[ 'constArg' ] : '';
                }//---------->> if $constArg has possibly content value in XML
                
                $args[] = $this->_getArgumentValue( $constArg );
                
            }//---------->> End foreach $constArg

        }//---------->> End if not empty $constArgs
        
        return $args;
        
    }//-------------------->> End _getConstArgs()
    
    /**
     * 
     * Builds and stores by reference, into the $arrayTarget, the setPropery 
     * methods params configuration.
     * @access  private
     * @param   array $setProperties The associative array representing the setProperty
     *          params configuration from an object.
     * @param   array $arrayTarget The array that will be updated by reference and
     *          where the setProperty methods configuration will be placed. Is used by
     *          reference in order to allow the method to be used recursively.
     * @see     DI_ConfigFileParser_XML::_getArgumentValue()
     */
    private function _getSetProperty( array $setProperties, array &$arrayTarget )
    {//-------------------->> _getSetProperty()
        
        if ( !empty( $setProperties ) ) {//---------->> if not empty $setProperties

            foreach( $setProperties as $property ) {//---------->> foreach $property

                if ( !is_array( $property ) ) {//---------->> if not compound $property
                    
                    $propertyName = (string) $property->attributes()->name;
                    
                    if ( empty( $propertyName ) ) {//---------->> if $propertyName is empty
                        continue;
                    }//---------->> End if $propertyName is empty
                    
                    $arrayTarget[ (string) $propertyName ] = $this->_getArgumentValue( $property );

                } else {//---------->> else if compound $property
                    $this->_getSetProperty( $property, $arrayTarget );
                }//---------->> End if not compound $property

            }//---------->> End foreach $property

        }//---------->> End if not empty $setProperties
        
    }//-------------------->> End _getSetProperty()

    /**
     * 
     * Retrieves the value for an argument to be used on a constructor or setter method.
     * @access  private
     * @param   array|object $arg The property|constructor arg configuration array|object
     * @return  mixed
     * @see     DI_ConfigFileParser_XML::_getArgumentValueFromArray()
     * @see     DI_ConfigFileParser_XML::_getArgumentValueFromObject()
     */
    private function _getArgumentValue( $arg )
    {//-------------------->> _getArgumentValue()
        return ( is_array( $arg ) ) ? $this->_getArgumentValueFromArray( $arg ) : $this->_getArgumentValueFromObject( $arg );
    }//-------------------->> End _getArgumentValue()
    
    /**
     * 
     * Retrieves the argument value from an array configuration. This method is 
     * commonly called while the constructor arguments are being build.
     * @access  private
     * @param   array $arg
     * @return  array
     */
    private function _getArgumentValueFromArray( array $arg )
    {//-------------------->> _getArgumentValueFromArray()
        
        $value = null;
        
        if ( !isset( $arg[ 'ref' ] ) ) {//---------->> if ref attr is not setted to true
            
            $type = ( isset( $arg[ 'type' ] ) ) ? (string) $arg[ 'type' ] : 'string';

            if ( $type != 'cdata-json' ) {//---------->> if $type is not complex 

                $value = $arg[ 'value' ];
                settype( $value, $type );

            } else {//---------->> else if $type is complex (read json inside <![CDATA[]]>
                $value = json_decode( $arg[ 'contentValue' ], true );
            }//---------->> End if $type is complex
            
        } else {//---------->> else if ref attr is setted to true
            $value = array( 'ref' => $arg[ 'ref' ] );
        }//---------->> End if ref attr is not setted to true
        
        return $value;
        
    }//-------------------->> End _getArgumentValueFromArray()
    
    /**
     * 
     * Retrieves the argument value from an SimpleXMLElement configuration. This method is 
     * commonly called while arguments from any setProperty method are being build.
     * @access  private
     * @param   SimpleXMLElement $arg
     * @return  mixed
     */
    private function _getArgumentValueFromObject( SimpleXMLElement $arg )
    {//-------------------->> _getArgumentValueFromObject()
        
        $value = null;
        
        $attr = $arg->attributes();
            
        if ( !isset( $attr->ref ) ) {//---------->> if ref attr is not setted to true
            
            $type = ( isset( $attr->type ) ) ? (string) $attr->type : 'string';

            if ( $type != 'cdata-json' ) {//---------->> if $type is not complex

                $value = $attr->value;
                settype( $value, $type );

            } else {//---------->> else if $type is complex (read json inside <![CDATA[]]> 
                $value = json_decode( (string) $arg, true );
            }//---------->> End if $type is complex
            
        } else {//---------->> else if ref attr is setted to true
            $value = array( 'ref' => (string) $attr->ref );
        }//---------->> End if ref attr is not setted to true
        
        return $value;
        
    }//-------------------->> End _getArgumentValueFromObject()

    public function parse()
    {//-------------------->> parse()

        $dom = simplexml_load_file( $this->_configFilePath );

        $pathsConfig = (array) $dom->paths;
        $objectsConfig = (array) $dom->objects;
        $config = array( 
            'DI_Config' => array( 
                'paths' => $this->_parsePathsConfig( $pathsConfig[ 'path' ] ), 
                'objects' => $this->_parseObjectsConfig( $objectsConfig[ 'object' ] ) 
            ) 
        );

        return $config;

    }//-------------------->> End parse()

}//---------------------------------------->> End Class XML