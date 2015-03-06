<?php

/**
 * 
 * Description of the main behaviour for concrete CRUD
 * (Create, Read, Update, Delete) objects.
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DAO_CRUD
{//------------------------->> Interface CRUD
    
    /**
     * 
     * Find a record by its ID.
     * @param   int $id
     * @throws  DAO_Exception
     * @return  array PDO ResultSet
     */
    public function find( $id );
    
    /**
     * 
     * Find a sub set of records by many criterias.
     * @param   array $config Search Criterias
     * @throws  DAO_Exception
     * @return  array PDO ResultSet
     */
    public function fetch( array $config = array() );
    
    /**
     * 
     * Retrive all the records.
     * @param   array $config Fields and Order criterias.
     * @throws  DAO_Exception
     * @return  array PDO ResultSet
     */
    public function fetchAll( array $config = array() );
    
    /**
     * 
     * Saves or edits a record.
     * @param   array $data Data to be saved or edited.
     * @throws  DAO_Exception
     * @return  int ID of the new record or number of edited records.
     */
    public function save( array $data );
    
    /**
     * 
     * Deletes a record by its ID.
     * @param   int $id
     * @throws  DAO_Exception
     * @return  boolean True if the delete action was successful.
     */
    public function delete( $id );
    
}//------------------------->> End Interface CRUD