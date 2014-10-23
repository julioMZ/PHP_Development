<?php

/**
 * 
 * Main behaviour of the Config File Parsers.
 * @package     DI
 * @category    Config File Parser
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>      
 */
abstract class DI_ConfigFileParser 
{//---------------------------------------->> Abstract Class ConfigFileParser
    
    /**
     *
     * Dir path where the config file is located.
     * @var     string
     * @access  protected  
     */
    protected $_configFilePath;
    
    /**
     *
     * Pattern to eval if a string value is a json object.
     * @var     string
     * @static
     * @access  protected  
     */
    protected static $_jsonPattern = '[\[*\]|\{*\}]';
    
    /**
     *
     * The constructor must receive the dir path where the config file
     * is located.
     * @param   string $filePath The Path where the config file is located.
     * @throws  DI_Exception_ConfigFileParserException
     */
    public function __construct( $filePath ) 
    {//-------------------->> __construct()
        $this->setConfigFilePath( $filePath );
    }//-------------------->> End __construct()
    
    /**
     * 
     * @param   string $filePath Path where the config path is located.
     * @throws  DI_Exception_ConfigFileParserException
     */
    public function setConfigFilePath( $filePath )
    {//-------------------->> setConfigFilePath()
        
        if ( !file_exists( $filePath ) ) {//---------->> if file doesn't exist
            throw new DI_Exception_ConfigFileParserException( "The config file {$filePath} doesn't exist" );
        }//---------->> End if if file doesn't exist
        
        $ext = strtolower( substr( strrchr( $filePath, '.' ), 1 ) );    
        $validation = $this->validateFile( $filePath, $ext );
        
        if ( $validation[ 'isValid' ] == false ) {//---------->> if validation failed
            throw new DI_Exception_ConfigFileParserException( "INVALID CONFIG FILE [{$validation[ 'message' ]}]" );
        }//---------->> End if validation failed

        $this->_configFilePath = $filePath;

    }//-------------------->> End setConfigFilePath()
    
    /**
     * 
     * Validates if the file and its extention are valid for this parser.
     * @param   string $filePath The Path where the config file is located.
     * @param   string $ext Extention of the file.
     * @retun   array Assosiative array with the following composition: 
     * <table border="1">
     *  <tr>
     *      <th>Key</th>
     *      <td>Type</td>
     *      <th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>isValid</td>
     *      <td>boolean</td>
     *      <td>Describes if the validatiion was positive or negative.</td>
     *  </tr>
     *  <tr>
     *      <td>Message</td>
     *      <td>string</td>
     *      <td>Message describing the validation Error</td>
     *  </tr>
     * </table>
     * @throws  DI_Exception_ConfigFileParserException
     */
    abstract public function validateFile( $filePath, $ext );
    
    /**
     * 
     * Parses the file in order to obtain the Beans Clasess and instances.
     * @throws  DI_Exception_ConfigFileParserException
     * @return  array 
     */
    abstract public function parse();
    
}//---------------------------------------->> End Abstract Class ConfigFileParser