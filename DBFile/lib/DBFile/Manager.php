<?php

/**
 * 
 * Concrete implementation of Data Base File
 * @package DBFile
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DBFile_Manager 
    implements DBFile
{//------------------------->> Class Manager
    
    /**
     * 
     * Data Access Object
     * @access  private
     * @var     DAO
     */
    private $_DAO;
    
    /**
     * 
     * File Validator
     * @access  private
     * @var     DBFile_Validator
     */
    private $_validator;
    
    /**
     *
     * File Content Encryption
     * @access  private
     * @var     DBFile_Encryption
     */
    private $_encryption;
    
    /**
     * 
     * Data Base File Manager constructor.
     * @param   array $config Assosiative array composed by:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th><th>Mandatory</th>
     *  </tr>
     *  <tr>
     *      <td>daoInstance</td><td>DAO</td><td>Data Acces Object to access the DB via PDO</td><td>Yes</td>
     *  </tr>
     *  <tr>
     *      <td>validator</td><td>DBFile_Validator</td><td>Data Base File Validator</td><td>No</td>
     *  </tr>
     *  <tr>
     *      <td>encryption</td><td>DBFile_Encryption</td><td>File Data Encryption</td><td>No</td>
     *  </tr>
     * </table> 
     */
    public function __construct( array $config )
    {//--------------->> __construct()
        
        $this->setDAO( $config[ 'daoInstance' ] );
        
        if ( isset( $config[ 'validator' ] ) ) {//---------->> if validator is setted
            $this->setValidator( $config[ 'validator' ] );
        }//---------->> End if validator is setted
        
        if ( isset( $config[ 'encryption' ] ) ) {//---------->> if encryption is setted
            $this->setEncryption( $config[ 'encryption' ] );
        }//---------->> End if encryption is setted
        
    }//--------------->> End __construct()
    
    /**
     * 
     * Retrives the current DAO instance.
     * @return  DAO
     */
    public function getDAO() 
    {//--------------->> getDAO()
        return $this->_DAO;
    }//--------------->> End getDAO()

    /**
     * 
     * Tries to establish the DAO instance to be used in order to access the DB
     * via PDO API.
     * @param   DAO $pdoDAO DAO concrete instance.
     */
    public function setDAO( DAO $pdoDAO )
    {//--------------->> setDAO()
        $this->_DAO = $pdoDAO;
    }//--------------->> End setDAO()
    
    /**
     * 
     * Retrives the current File Validator. If there is no validator, this 
     * method will return a null value.
     * @return  DBFile_Validator|null
     */
    public function getValidator() 
    {//--------------->> getValidator()
        return $this->_validator;
    }//--------------->> End getValidator()

    /**
     * 
     * Tries to establish the Validator to be used in order to deny the 
     * persistence of certain types of files.
     * @param DBFile_Validator $validator File Validator
     */
    public function setValidator( DBFile_Validator $validator ) 
    {//--------------->> setValidator()
        $this->_validator = $validator;
    }//--------------->> End setValidator()
    
    /**
     * 
     * Retrives the current File Encryption. If there is no encryption, this 
     * method will return a null value.
     * @return  DBFile_Encryption|null
     */
    function getEncryption() 
    {//--------------->> getEncryption()
        return $this->_encryption;
    }//--------------->> End getEncryption()

    /**
     * 
     * Tries to establish the Encryption to be used in order to encode/decode the
     * original content of one DBFile_File.
     * @param DBFile_Encryption $encryption File Encryption
     */
    function setEncryption( DBFile_Encryption $encryption )
    {//--------------->> setEncryption()
        $this->_encryption = $encryption;
    }//--------------->> End setEncryption()
     
    /**
     * 
     * Tries to save the file into a Data Base Table performing before a 
     * validation if a DBFile_Validator was previously setted.
     * <p>If the second param is a boolean true, the original file will be erased.</p>
     * @param   DBFile_File $file Instance of the File to be saved in the Data Base.
     * @param   boolean $eraseFile Flag to determine if the original file will be physically erased.
     * @throws  DBFile_Exception
     * @return  int ID of the File in the Data Base Table.
     */
    public function save( DBFile_File $file, $eraseFile = true ) 
    {//--------------->> save()
        
        if( !is_null( $this->_validator ) ) {//---------->> if File Validator is setted
            $this->_validator->validate( $file );
        }//---------->> End if File Validator is setted
        
        if( !is_null( $this->_encryption ) ) {//---------->> if File Encryption is setted
            $this->_encryption->encode( $file );
        }//---------->> End if File Encryption is setted
        
        $isertedId = $this->_DAO->save( array( 
            'mime_type' => $file->getType(), 
            'name' => $file->getName(),
            'size' => $file->getSize(),
            'content' => array( 
                'type' => 'BLOB', 
                'value' => $file->getContent()
            ) 
        ) );
        
        if ( $eraseFile ==  true ) {//---------->> if $eraseFile is true
            unlink( $file->getPath() );
        }//---------->> End if $eraseFile is true
        
        return $isertedId;
        
    }//--------------->> End save()
    
    /**
     * 
     * Retrives the DBFile_File instance from the Data Base performing a search 
     * by its ID.
     * @param   int $fileID
     * @throws  Exception
     * @return  DBFile
     */
    public function retrieve( $fileID ) 
    {//--------------->> retrieve()
        
        $fileData = $this->_DAO->find( (int) $fileID );
        
        if ( empty( $fileData ) ) {//---------->> if no file found
            throw new DBFile_Exception( "No file with ID {$fileID} found!" );
        }//---------->> End if no file found
        
        $file = DBFile_File_Builder::getFileFromResultSet( $fileData );
        
        if( !is_null( $this->_encryption ) ) {//---------->> if File Encryption is setted
            $this->_encryption->decode( $file );
        }//---------->> End if File Encryption is setted

        return $file;
        
    }//--------------->> End retrieve()
    
    /**
     * 
     * Tries to force the download of a DBFile_File performing a search by its ID.
     * @param   int $fileID ID of the File to be searched and forced to be downloaded.
     * @param   boolean $setOriginalMimeType Flag to determine if the original mime-type will be 
     *          setted on download headers.
     * @throws  Exception
     * @see     DBFile_Manager::retrieve($fileID)
     */
    public function download( $fileID, $setOriginalMimeType = false ) 
    {//--------------->> download()
        
        $file = $this->retrieve( $fileID );
        
        header( 'Content-Description: File Transfer' );
        
        if ( $setOriginalMimeType === true ) {//---------->> if $setOriginalMimeType == true
            header( "Content-type: {$file->getType()}" );
        } else {//---------->> else
            header( 'Content-type: application/octet-stream' );
        }//---------->> End else
        
        header( "Content-disposition: attachment; filename={$file->getName()}" );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Pragma: public' );
        header( "Content-Length: {$file->getSize()}" );
        
        echo $file->getContent();
        
    }//--------------->> End download()
    
    /**
     * 
     * Tries to delete a File from the Data Base by its ID.
     * @param   int $fileID ID of the File to be deleted from the Data Base.
     * @throws  Exception
     */
    public function delete( $fileID )
    {//--------------->> delete()
        $this->_DAO->delete( $fileID );
    }//--------------->> End delete()
    
}//------------------------->> End Class Manager