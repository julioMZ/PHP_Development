<?php

/**
 * 
 * Object to parse .json or .js files in order to obtain the DI configuration.
 * @package     DI
 * @subpackage  ConfigFileParser
 * @category    Configuration File Parser
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @uses        DI_ConfigFileParser
 */
class DI_ConfigFileParser_Json 
    extends DI_ConfigFileParser 
{//---------------------------------------->> Class Json
    
    public function validateFile( $filePath, $ext )
    {//-------------------->> validateFile()
        
        if ( $ext != 'json' && $ext != 'js' ) {//---------->> if invalid file type
            return array( 'isValid' => false, 'message' => "The Config File: {$filePath} is not a json or js file" );
        }//---------->> End if invalid file type
        
        return array( 'isValid' => true );

    }//-------------------->> End validateFile()

    public function parse() 
    {//-------------------->> parse()
        
        $fileContent = file_get_contents( $this->_configFilePath );
        
        if ( empty( $fileContent ) || preg_match( parent::$_jsonPattern, $fileContent ) == 0 ) {//---------->> if string is json array or object
            throw new DI_Exception_ConfigFileParserException( "The file {$this->_configFilePath} doesn't contain a json object" );
        }//---------->> End if string is json array or object
        
        $result = json_decode( $fileContent, true );
        
        if ( is_null( $result ) ) {//---------->> if null result
            throw new DI_Exception_ConfigFileParserException( "Error parsing JSON Config File: {$this->_getJsonLastErrorDescription()}" );
        }//---------->> End if null result
        
        return $result[ 0 ];
        
    }//-------------------->> End parse()
    
    /**
     *
     * Retrieves a description of the last error produced in the json_decode() 
     * process.
     * @return  string
     */
    private function _getJsonLastErrorDescription()
    {//-------------------->> _getJsonLastErrorDescription()
        
        $errorDescription = '';
        
        if ( !function_exists( 'json_last_error' ) ) {//---------->> if function doesn't exists
            return 'Possible Syntax error, malformed JSON';
        }//---------->> End if function doesn't exists
        
        switch( json_last_error() ) {//---------->> switch last error
            
            case JSON_ERROR_NONE:
                $errorDescription = 'No Errors';
            break;
        
            case JSON_ERROR_DEPTH:
                $errorDescription = 'Has exceeded the maximum depth of the stack';
            break;
        
            case JSON_ERROR_STATE_MISMATCH:
                $errorDescription = 'Buffer overflow or modes do not match';
            break;
        
            case JSON_ERROR_CTRL_CHAR:
                $errorDescription = 'Found Unexpected control character';
            break;
        
            case JSON_ERROR_SYNTAX:
                $errorDescription = 'Syntax error, malformed JSON';
            break;
        
            case JSON_ERROR_UTF8:
                $errorDescription = 'Malformed UTF-8 characters, are likely miscoded';
            break;
        
            default:
                $errorDescription = 'Unknown Error';
            break;
        
        }//---------->> End switch last error
        
        return $errorDescription;
        
    }//-------------------->> _getJsonLastErrorDescription()
    
}//---------------------------------------->> End Class Json