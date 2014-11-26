<?php

/**
 *
 * Definition of the main behaviour of DB Files Encryption
 * @package DBFile
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DBFile_Encryption 
{//------------------------->> Interface Encryption
    
    /**
     * 
     * Encodes the file content.
     * @param DBFile_File $file
     */
    public function encode( DBFile_File $file );
    
    /**
     * 
     * Decodes the file content.
     * @param DBFile_File $file
     */
    public function decode( DBFile_File $file );
    
}//------------------------->> End Interface Encryption