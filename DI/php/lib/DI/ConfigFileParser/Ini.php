<?php

/**
 * 
 * Object to parse .ini files in order to obtain the DI configuration
 * @package     DI
 * @subpackage  ConfigFileParser
 * @category    Configuration File Parser
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @uses        DI_ConfigFileParser.
 */
class DI_ConfigFileParser_Ini 
    extends DI_ConfigFileParser
{//---------------------------------------->> Class Ini

    public function validateFile( $filePath, $ext )
    {//-------------------->> validateFile()
        
        if ( $ext != 'ini' ) {//---------->> if invalid file type
            return array( 'isValid' => false, 'message' => "The Config File: {$filePath} is not a ini file" );
        }//---------->> End if invalid file type
        
        return array( 'isValid' => true );

    }//-------------------->> End validateFile()
    
    /**
     *
     * Verifies if the received value is a json string to deserialize
     * the object into an assosiative array.
     * @access  private
     * @param   mixed $value 
     * @return  mixed 
     */
    private function _parseValue( $value )
    {//-------------------->> _parseValue()
        
        if ( is_string( $value ) ) {//---------->> if is not a json string
            
            if ( preg_match( parent::$_jsonPattern, $value ) > 0 ) {//---------->> if string is json array or object
                $value = json_decode( $value, true );
            }//---------->> End if string is json array or object
            
        }//---------->> if is not a json string
        
        return $value;
        
    }//-------------------->> End _parseValue()
    
    /**
     *
     * Parses the initial config array searching for keys with ':' 
     * tokens in order to group the values inside a new associative array.
     * @access  private
     * @param   array $array Assosiative array from a .ini file representation.
     * @return  array  
     */
    private function _parseIniAdvanced( array $array ) 
    {//-------------------->> _parseIniAdvanced()
        
        $returnArray = array();

        foreach ( $array as $key => $value ) {//---------->> for each ini element
            
            $array[ $key ] = $this->_parseValue( $value );
            
            $e = explode( ':', $key );
            
            if ( !empty( $e[ 1 ] ) ) {//---------->> if key element has : tokens
                
                $x = array();
                
                foreach ( $e as $tk => $tv ) {//---------->> for each : token
                    $x[ $tk ] = trim( $tv );
                }//---------->> End for each : token
                
                $x = array_reverse( $x, true );
                
                foreach ( $x as $k => $v ) {//---------->> for each token value
                    
                    $c = $x[ 0 ];
                    
                    if ( empty( $returnArray[ $c ] ) ) {//---------->> if index does't exist in $returnArray
                        $returnArray[ $c ] = array();
                    }//---------->> End if index does't exist in $returnArray
                    
                    if ( isset( $returnArray[ $x[ 1 ] ] ) ) {//---------->> if token index exists in $returnArray
                        $returnArray[ $c ] = array_merge( $returnArray[ $c ], $returnArray[ $x[ 1 ] ] );
                    }//---------->> End if token index exists in $returnArray
                    
                    if ( $k === 0 ) {//---------->> if token index is equal to 0
                        $returnArray[ $c ] = array_merge( $returnArray[ $c ], $array[ $key ] );
                    }//---------->> End if token index is equal to 0
                    
                }//---------->> End for each token value
                
            } else {//---------->> else if key element has not : tokens
                $returnArray[ $key ] = $array[ $key ];
            }//---------->> End if key element has : tokens
            
        }//---------->> End for each ini element
            
        return $returnArray;
        
    }//-------------------->> End _parseIniAdvanced()
    
    /**
     *
     * Parses recursively the initial config array searching for keys with '.' 
     * tokens in order to group the values inside a new associative array.
     * @access  private
     * @param   array $array Assosiative array from a .ini file representation.
     * @return  array
     */
    private function _recursiveParse( array $array )
    {//-------------------->> _recursiveParse()
        
        $returnArray = array();
        
        foreach ( $array as $key => $value ) {//---------->> for each initial array config element
            
            $array[ $key ] = $this->_parseValue( $value );
            
            if ( is_array( $array[ $key ] ) ) {//---------->> if value is array
                $array[ $key ] = $this->_recursiveParse( $array[ $key ] );
            }//---------->> End if value is array
            
            $x = explode( '.', $key );
            
            if ( !empty( $x[ 1 ] ) ) {//---------->> if key element has . tokens
                
                $x = array_reverse( $x, true );
                
                if ( isset( $returnArray[ $key ] ) ) {//---------->> if key exist in $returnArray
                    unset( $returnArray[ $key ] );
                }//---------->> End if key exist in $returnArray
                
                if ( !isset( $returnArray[ $x[ 0 ] ] ) ) {//---------->> if token key doesn't exist in $returnArray
                    $returnArray[ $x[ 0 ] ] = array();
                }//---------->> End if token key doesn't exist in $returnArray
                
                $first = true;
                
                foreach ( $x as $k => $v ) {//---------->> for each token
                    
                    if ( $first === true ) {//---------->> if is firts token
                        $b = $array[ $key ];
                        $first = false;
                    }//---------->> End if is firts token
                    
                    $b = array( $v => $b );
                    
                }//---------->> End for each token
                
                $returnArray[ $x[ 0 ] ] = array_merge_recursive( $returnArray[ $x[ 0 ] ], $b[ $x[ 0 ] ] );
                
            } else {//---------->> else if key element has not . tokens
                $returnArray[ $key ] = $array[ $key ];
            }//---------->> End if key element has . tokens
            
        }//---------->> End for each initial array config element
            
        return $returnArray;
        
    }//-------------------->> End _recursiveParse()
    
    public function parse() 
    {//-------------------->> parse()
        
        $configArray = parse_ini_file( $this->_configFilePath, true );
        return $this->_recursiveParse( $this->_parseIniAdvanced( $configArray ) );
        
    }//-------------------->> End parse()
    
}//---------------------------------------->> End Class Ini