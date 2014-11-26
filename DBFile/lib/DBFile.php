<?php

/**
 *
 * Main behaiviour of Data Base File
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DBFile 
{//------------------------->> Interface DBFile
    
    /**
     * 
     * Save a file into a DB Table.
     * @param   DBFile_File $file File representation.
     * @param   boolean $eraseFile Flag to define if the original file will be erased or not.
     *          Boolean True by default.
     * @throws  DBFile_Exception
     * @returns string The URI since the file could be retrived again.
     */
    public function save( DBFile_File $file, $eraseFile = true );
    
    /**
     * 
     * Retrieve a File from the Data Base by its ID.
     * @param   int $fileID The ID of the file to be retrived from the DB.
     * @throws  DBFile_Exception
     * @return  array
     */
    public function retrieve( $fileID );
    
    /**
     * 
     * Force Download of a File from the Data Base making a search by its ID.
     * @param   int $fileID The ID of the file to be retrived and downloaded from the DB.
     * @throws  DBFile_Exception
     */
    public function download( $fileID );
    
    /**
     * 
     * Delete a File from the Data Base by iits ID.
     * @param   int $fileID The ID of the file to be deleted.
     * @throws  DBFile_Exception
     * @return  boolean
     */
    public function delete( $fileID );
    
}//------------------------->> End Interface DBFile