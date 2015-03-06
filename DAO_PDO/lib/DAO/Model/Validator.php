<?php

/**
 * 
 * Main behaivour of validator objects to be used on
 * DAO_Model::save method invocations.
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
interface DAO_Model_Validator
{//------------------------->> Interface DAO_Validator
    
    /**
     *
     * Retrieves an associative array with the
     * validation rules according to the filter_var_array
     * doc.
     * @return  array
     */
    public function getValidationRules();
    
    
    /**
     *
     * Validates and filters the input data using the
     * validtion rules and returns a new associative
     * array with the filtered values.
     * @param   array $data Asociative array with pair=>value for each
     *          Table column value to be insterted or updated.
     * @throws  Exception
     * @return  array
     */
    public function validate( array $data );
    
}//------------------------->> End Interface DAO_Validator