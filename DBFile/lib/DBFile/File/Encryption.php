<?php

/**
 * 
 * Description of DBFile_Fille_Encryption
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DBFile_File_Encryption 
    implements DBFile_Encryption
{//------------------------->> Class Encryption
    
    /**
     * 
     * Decodes the content of the file in order to retrieve its original content.
     * @param DBFile_File $file
     */
    public function decode( DBFile_File $file ) 
    {//-------------------->> decode()
        $file->setContent( base64_decode( $file->getContent() ) );
    }//-------------------->> End decode()

    /**
     * 
     * Encodes the content of the file in order to encrypt its original content.
     * @param DBFile_File $file
     */
    public function encode( DBFile_File $file ) 
    {//-------------------->> encode()
        $file->setContent( base64_encode( $file->getContent() ) );
    }//-------------------->> End encode()
    
}//------------------------->> End Encryption