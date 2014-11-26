<?php

/**
 *
 * DAO with the logic for activate/deactivate registers via active field.
 * @package     DAO
 * @subpackage  Manager
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Manager_ActiveField 
    extends DAO_Manager
{//------------------------->> Class DAO_Manager_ActiveField
    
    /**
     *
     * Name of the field that determines if a row is active/innactive
     * @access  protected
     * @var     string
     */
    protected $_activeFieldName;
    
    /**
     * 
     * DAO_Manager_ActiveField Constructor
     * @param array $config Associative Array with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>pdoInstance</td><td>PDO</td><td>PDO Instance in order to access into the DB</td>
     *  </tr>
     *  <tr>
     *      <td>tableName</td><td>string</td><td>Name of the Table to be used in SQL Queries.</td>
     *  </tr>
     *  <tr>
     *      <td>primaryKey</td><td>string</td><td>Name of the field that represents the Primary Key of the table. If is ommited the default value will be 'id'.</td>
     *  </tr>
     *  <tr>
     *      <td>activeField</td><td>string</td><td>Name of the field that represents the Active Field of the table. If is ommited the default value will be 'active'.</td>
     *  </tr>
     * </table> 
     * @throws  DAO_Exception
     * @uses    DAO_Manager::__construct($config)
     */
    public function __construct( array $config )
    {//-------------------->> __construct()
        
        parent::__construct( $config );
        $this->setActiveFieldName( ( isset( $config[ 'activeField' ] ) ) ? $config[ 'activeField' ] : 'active' );
        
    }//-------------------->> End __construct()
    
    /**
     * 
     * Retrieves the name of the Active Field of the Table.
     * @return  string
     */
    public function getActiveFieldName()
    {//-------------------->> getActiveFieldName()
        return $this->_activeFieldName;
    }//-------------------->> End getActiveFieldName()

   /**
     * 
     * Tries to stablish the name of the Active Field of the table.
     * A DAO_Exception will be thrown if the recived param is an empty string.
     * @param   string $activeFieldName Name of the active field of the table.
     * @throws  DAO_Exception
     */
    public function setActiveFieldName( $activeFieldName ) 
    {//-------------------->> setActiveFieldName()
        
        $daoActiveFieldName = trim( $activeFieldName );

        if ( strlen( $daoActiveFieldName ) == 0  ) {//---------->> if empty $daoActiveFieldName
           throw new DAO_Exception( "{$daoActiveFieldName} is not a valid active field name" );
        }//---------->> End if empty $daoActiveFieldName
       
        $this->_activeFieldName = $daoActiveFieldName;
        
    }//-------------------->> End setActiveFieldName()
    
    /**
     * 
     * Method to intercept each fetch action in order to retrive only the
     * active records.
     */
    protected function _interceptFetchAction() 
    {//-------------------->> _interceptFetchAction()
        
        $wherePosition = strpos( $this->_sqlQuery, 'WHERE' );
        
        if ( $wherePosition != false ) {//---------->> if
            $this->_sqlQuery = str_replace( 'WHERE ', "WHERE {$this->_activeFieldName} = 1 AND ", $this->_sqlQuery );
        } else {//---------->> else
            $this->_sqlQuery = str_replace( "FROM {$this->_tableName}", "FROM {$this->_tableName} WHERE {$this->_activeFieldName} = 1", $this->_sqlQuery );
        }//---------->> End else
        
    }//-------------------->> End _interceptFetchAction()
    
    /**
     * 
     * Performs an UPDATE action setting the active field to 0.
     * @param   int $id ID of the record to be updated into an innactive state.
     * @return  boolean
     */
    public function delete( $id )
    {//-------------------->> delete()
        return ( $this->save( array( 'id' => (int) $id, $this->_activeFieldName => 0 ) ) > 0 );   
    }//-------------------->> End delete()
    
}//------------------------->> End Class DAO_Manager_ActiveField