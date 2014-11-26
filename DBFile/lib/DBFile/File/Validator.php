<?php

/**
 * 
 * DBFile_Files Validator
 * @package     DBFile
 * @subpackage  File
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DBFile_File_Validator 
    implements DBFile_Validator
{//------------------------->> Class Validator
    
    /**
     *
     * Validator configuration.
     * @access  protected
     * @var     array
     */
    protected $_config;
    
    /**
     * 
     * DBFile_File_Validator Constructor
     * @param array $config Validator configuration with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>max_file_size</td><td>int</td><td>Maximum size of the valid files expressed in Bytes.</td>
     *  </tr>
     *  <tr>
     *      <td>allowed_mime_types</td><td>array</td><td>Mime-types of valid file types.</td>
     *  </tr>
     * </table>
     * @throws  DBFile_Exception
     */
    public function __construct( array $config )
    {//-------------------->> __construct()
        $this->setConfig( $config );
    }//-------------------->> End __construct()
    
    /**
     * 
     * Tries to establish the validator configuration.
     * @param array $config Validator configuration with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>max_file_size</td><td>int</td><td>Maximum size of the valid files expressed in Bytes.</td>
     *  </tr>
     *  <tr>
     *      <td>allowed_mime_types</td><td>array</td><td>Mime-types of valid file types.</td>
     *  </tr>
     * </table>
     * @throws DBFile_Exception
     */
    public function setConfig( array $config )
    {//-------------------->> setConfig()
        
        if ( !isset( $config[ 'max_file_size' ] ) || !is_numeric( $config[ 'max_file_size' ] ) ) {//---------->> if max file size not defined
            throw new DBFile_Exception( 'No Max File Sized defined on DBFile_Validator' );
        }//---------->> End if max file size not defined
        
        settype( $config[ 'max_file_size' ], 'int' );
        
        if ( !isset( $config[ 'allowed_mime_types' ] ) || !is_array( $config[ 'allowed_mime_types' ] ) || 
             empty( $config[ 'allowed_mime_types' ] )  ) {//---------->> if not allowed_mime_types defined
            throw new DBFile_Exception( 'No Allowed Mime Types defined on DBFile_Validator' );
        }//---------->> End if not allowed_mime_types defined
        
        $this->_config = $config;
        
    }//-------------------->> End setConfig()
    
    /**
     * 
     * Validates that the size of the file is not greater than max_file_size and
     * that is mime-type is one of the allowed ones.
     * @param   DBFile $file DBFile Instance.
     * @throws  DBFile_Exception
     */
    public function validate( DBFile_File $file ) 
    {//-------------------->> validate()
        
        if ( $file->getSize() > $this->_config[ 'max_file_size' ] ) {//---------->> if file size is greater than max_file_size
            throw new DBFile_Exception( "The Size of the File {$file->getPath()}[{$file->getSize()}] is greater than {$this->_config[ 'max_file_size' ]}" );
        }//---------->> End if file size is greater than max_file_size
        
        if ( !in_array( $file->getType(), $this->_config[ 'allowed_mime_types' ] ) ) {//---------->> if not allowed mime-type
            throw new DBFile_Exception( "The Mime-Type of the File {$file->getPath()}[{$file->getType()}] is not allowed" );
        }//---------->> End ifnot allowed mime-type
        
    }//-------------------->> End validate()
    
}//------------------------->> End Class Validator