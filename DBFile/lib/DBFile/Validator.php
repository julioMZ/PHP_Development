<?php

/**
 *
 * Definition of the main behaviour of DBFile Validators.
 * @package DBFile
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DBFile_Validator 
{//------------------------->> Interface Validator
    
    /**
     * 
     * Main method of validators.
     * @param   DBFile_File $file Instance of DBFile_File class.
     * @throws  DBFile_Exception
     */
    public function validate( DBFile_File $file );
    
}//------------------------->> End Interface Validator