<?php

/**
 * 
 * DAO_CRUD Implementation making use of PDO API
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_CRUD_Manager
    implements DAO_CRUD
{//------------------------->> Class Manager
    
    /**
     *
     * PDO instance.
     * @access  protected
     * @var     PDO
     */
    protected $_pdoInstance;
    
    /**
     *
     * Name of the Table to be used in SQL sentences.
     * @access  protected
     * @var     string
     */
    protected $_tableName;
    
    /**
     *
     * Name of the field that represents the Primary Key of the table.
     * @access  protected
     * @var     string
     */
    protected $_primaryKey;
    
    /**
     *
     * PDOStatement generated after a PDO::prepare() invocation.
     * @access  protected
     * @var     PDOStatement
     */
    protected $_pdoStatement;
    
    /**
     *
     * Last executed SQL query.
     * @access  protected
     * @var     string
     */
    protected $_sqlQuery;
    
    /**
     * 
     * DAO_Manager Constructor
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
     * </table> 
     * @throws  DAO_Exception
     */
    public function __construct( array $config )
    {//-------------------->> __construct()
        
        $this->setPdoInstance( $config[ 'pdoInstance' ] )
             ->setTableName( $config[ 'tableName' ] )
             ->setPrimaryKey( ( isset( $config[ 'primaryKey' ] ) ) ? $config[ 'primaryKey' ] : 'id' );
        
    }//-------------------->> End __construct()
    
    /**
     * 
     * Retrieves the internal PDO instance.
     * @return  PDO
     */
    public function getPDOInstance()
    {//-------------------->> getPDOInstance()
        return $this->_pdoInstance;
    }//-------------------->> End getPDOInstance()

    /**
     * 
     * Gets the Name of the Table used in SQL Queries.
     * @return  string
     */
    public function getTableName()
    {//-------------------->> getTableName()
        return $this->_tableName;
    }//-------------------->> End getTableName()
    
    /**
     * 
     * Gets the name of the field that represents the Primary Key of the Table.
     * @return  string
     */
    public function getPrimaryKey() 
    {//-------------------->> getPrimaryKey()
        return $this->_primaryKey;
    }//-------------------->> End getPrimaryKey()
    
    /**
     * 
     * Sets the PDO Instance.
     * @param   PDO $pdo PDO instance
     * @return  DAO_Manager
     */
    public function setPDOInstance( PDO $pdo )
    {//-------------------->> setPDOInstance()
        $this->_pdoInstance = $pdo;
        return $this;
    }//-------------------->> End setPDOInstance()

    /**
     * 
     * Tries to stablish the name of the table where the data will be saved and
     * retrived.
     * A DAO_Exception will be thrown if the recived param is an empty string.
     * @param   string $tableName Name of the data base table to be queryfied.
     * @throws  DAO_Exception
     * @return  DAO_Manager
     */
    public function setTableName( $tableName = '' ) 
    {//-------------------->> setTableName()

       $daoTableName = trim( $tableName );

       if ( strlen( $daoTableName ) == 0  ) {//---------->> if empty $daoTableName
           throw new DAO_Exception( "{$daoTableName} is not a valid DB Table Name" );
       }//---------->> End if empty $daoTableName

       $this->_tableName = $daoTableName;

       return $this;
       
    }//-------------------->> End setTableName()
    
    /**
     * 
     * Tries to stablish the name of the Primary Key of the table.
     * A DAO_Exception will be thrown if the recived param is an empty string.
     * @param   string $primaryKey Name of the primary key field of the table.
     * @throws  DAO_Exception
     * @return  DAO_Manager
     */
    public function setPrimaryKey( $primaryKey = '' ) 
    {//-------------------->> setTableName()

       $daoPrimaryKey = trim( $primaryKey );

       if ( strlen( $daoPrimaryKey ) == 0  ) {//---------->> if empty $daoPrimaryKey
           throw new DAO_Exception( "{$daoPrimaryKey} is not a valid primary key" );
       }//---------->> End if empty $daoPrimaryKey

       $this->_primaryKey = $daoPrimaryKey;

       return $this;
       
    }//-------------------->> End setTableName()
    
    /**
     * 
     * Tries to execute a PDO Statement according to the sqlQuery property state.
     * <p>If the PDO is not setted as ERROR MODE EXCEPTION, this method will throw
     * a DAO_Exception if an error ocurrs executing the sentence.</p>
     * @access  protected
     * @param   array $params Associative Array with the index->value to be replaced
     *          on prepared statements placeholders.
     * @throws  DAO_Exception
     */
    protected function _executeStatement( array $params = array() )
    {//-------------------->> _executeStatement()
        
        $this->_pdoStatement = $this->_pdoInstance->prepare( $this->_sqlQuery );
        $this->_bindParams( $params );
        
        if ( !$this->_pdoStatement->execute() && 
             $this->_pdoInstance->getAttribute( PDO::ATTR_ERRMODE ) != PDO::ERRMODE_EXCEPTION ) {//---------->> if error executing sentence
                
            $errorData = $this->_pdoStatement->errorInfo();
            throw new DAO_Exception( "Data Base Error: {$errorData[ 2 ]} executing {$this->_sqlQuery}" );

        }//---------->> End if error executing sentence
        
    }//-------------------->> End _executeStatement()
    
    /**
     * 
     * Find a record by its ID.
     * @param   int $id ID of the record to be located.
     * @throws  DAO_Exception
     * @return  array
     */
    public function find( $id )
    {//-------------------->> find()
        
        $this->_sqlQuery = "SELECT * FROM {$this->_tableName} WHERE {$this->_primaryKey} = :id;";
        $this->_interceptFetchAction();
        $this->_executeStatement( array( 'id' => (int) $id ) );

        return $this->_pdoStatement->fetch();
        
    }//-------------------->> End find()
    
    /**
     * 
     * Find a sub set of records by many criterias.
     * @param array $config Associative Array with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>fields</td><td>array</td><td>Name of the fields to be requested on SQL SELECT sentence.</td>
     *  </tr>
     *  <tr>
     *      <td>joinList</td><td>array</td><td>Array Strings of JOIN statements.</td>
     *  </tr>
     *  <tr>
     *      <td>where</td><td>string</td><td>String to be placed after the WHERE keyword. It could acept PDO place holders in the form :paramName</td>
     *  </tr>
     *  <tr>
     *      <td>groupBy</td><td>string</td><td>String to be placed after the GROUP BY keyword.</td>
     *  </tr>
     *  <tr>
     *      <td>having</td><td>string</td><td>String to be placed after the HAVING keyword.</td>
     *  </tr>
     *  <tr>
     *      <td>orderBy</td><td>string</td><td>String to be placed after the ORDER BY keyword.</td>
     *  </tr>
     *  <tr>
     *      <td>limit</td><td>string</td><td>String to be placed after the LIMIT keyword (Only for MySQL DBMS).</td>
     *  </tr>
     *  <tr>
     *      <td>params</td><td>array</td><td>Associative Array with the paramName->values to be replaced on query placeholders.</td>
     *  </tr>
     * </table>
     * @throws  DAO_Exception
     * @return  array 
     */
    public function fetch( array $config = array() )
    {//-------------------->> fetch()
        
        $fieldsList = ( isset( $config[ 'fields' ] ) && is_array( $config[ 'fields' ] ) && !empty( $config[ 'fields' ] ) ) ? implode( ',',  $config[ 'fields' ] ) : '*';
        $joinList = ( isset( $config[ 'join' ] ) && is_array( $config[ 'join' ] ) && !empty( $config[ 'join' ] ) ) ? ' ' . implode( ' ',  $config[ 'join' ] ) : '';
        $whereSentence = ( isset( $config[ 'where' ] ) && !empty( $config[ 'where' ] ) ) ? " WHERE {$config[ 'where' ]}" : '';
        $groupBySentence = ( isset( $config[ 'groupBy' ] ) && !empty( $config[ 'groupBy' ] ) ) ? " GROUP BY {$config[ 'groupBy' ]}" : '';
        $havingSentence = ( isset( $config[ 'having' ] ) && !empty( $config[ 'having' ] ) ) ? " HAVING {$config[ 'having' ]}" : '';
        $orderBySentence = ( isset( $config[ 'orderBy' ] ) && !empty( $config[ 'orderBy' ] ) ) ? " ORDER BY {$config[ 'orderBy' ]}" : '';
        $limitSentence = ( isset( $config[ 'limit' ] ) && !empty( $config[ 'limit' ] ) ) ? " LIMIT {$config[ 'limit' ]}" : '';
        
        $this->_sqlQuery = "SELECT {$fieldsList} FROM {$this->_tableName}{$joinList}{$whereSentence}{$groupBySentence}{$havingSentence}{$orderBySentence}{$limitSentence};";
        $this->_interceptFetchAction();
        $this->_executeStatement( ( isset( $config[ 'params' ] ) && is_array( $config[ 'params' ] ) ) ? $config[ 'params' ] : array() );

        return $this->_pdoStatement->fetchAll();
        
    }//-------------------->> End fetch()
    
    /**
     * 
     * Retrive all the records.
     * @param array $config Associative Array with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>fields</td><td>array</td><td>Name of the fields to be requested on SQL SELECT sentence.</td>
     *  </tr>
     *  <tr>
     *      <td>orderBy</td><td>string</td><td>String to be placed after the ORDER BY keyword.</td>
     *  </tr>
     * </table>
     * @throws  DAO_Exception
     * @return  array 
     */
    public function fetchAll( array $config = array() )
    {//-------------------->> fetchAll()
        
        return $this->fetch( array( 
            'fields' => ( isset( $config[ 'fields' ] ) ) ? $config[ 'fields' ] : array(), 
            'orderBy' => ( isset( $config[ 'orderBy' ] ) ) ? $config[ 'orderBy' ] : array() ) 
        );
        
    }//-------------------->> End fetchAll()
    
    /**
     * 
     * Template Method in order to modify SELECT queries in execution time.
     */
    protected function _interceptFetchAction()
    {//-------------------->> _interceptFetchAction()
        
    }//-------------------->> End _interceptFetchAction()
    
    /**
     * 
     * Inserts or edits a record.
     * <p>If the received data contains a <i>primary key</i> value, then an <i>UPDATE</i> action
     * will be executed. Otherwise, an <i>INSERT</i> action will be executed.</p>
     * @param   array $data Record Data in form of Associative Array (fieldName->value).
     * @throws  DAO_Exception
     * @return  int ID of the new record or number of edited records.
     */
    public function save( array $data )
    {//-------------------->> save()
        
        $fieldsList = array_keys( $data );
        $this->_sqlQuery = ( isset( $data[ $this->_primaryKey ] ) ) ? $this->_getUpdateSentence( $fieldsList ) : $this->_getInsertSentence( $fieldsList );
        $this->_executeStatement( $data );
        
        return ( isset( $data[ $this->_primaryKey ] ) ) ? $this->_pdoStatement->rowCount() : (int) $this->_pdoInstance->lastInsertId( $this->_primaryKey );
        
    }//-------------------->> End save()
    
    /**
     * 
     * Builds an INSERT SQL query acording to the received list of fields.
     * <p>The placeholders of the sentence will be placed in form of <i>:fieldName</i></p>
     * @param   array $fieldsList List of fields to be included on the SQL sentence.
     * @return  string
     */
    private function _getInsertSentence( array $fieldsList )
    {//-------------------->> _getInsertSentence()
        
        $fields = implode( ',', $fieldsList );
        $fieldsValues = array();
            
        foreach( $fieldsList as $fieldName ) {//---------->> foreach $fieldName
            $fieldsValues[] = ":{$fieldName}";
        }//---------->> End foreach $fieldName

        $fieldsValuesList = implode( ',', $fieldsValues );

        return "INSERT INTO {$this->_tableName} ({$fields}) VALUES ({$fieldsValuesList});";
        
    }//-------------------->> End _getInsertSentence()
    
    /**
     * 
     * Builds an UPDATE SQL query acording to the received list of fields.
     * <p>The placeholders of the sentence will be placed in form of <i>:fieldName</i></p>
     * @param array $fieldsList List of fields to be included on the SQL sentence.
     * @return  string
     */
    private function _getUpdateSentence( array $fieldsList )
    {//-------------------->> _getUpdateSentence()
        
        $fields = array();
            
        foreach( $fieldsList as $fieldName ) {//---------->> foreach $fieldName
            
            if ( $fieldName == $this->_primaryKey ) {//---------->> if $fieldName is the primary key
                continue;
            }//---------->> End if $fieldName is the primary key
            
            $fields[] = "{$fieldName} = :{$fieldName}";
            
        }//---------->> End foreach $fieldName

        $fieldsUpdateLists = implode( ',', $fields ); 
            
        return "UPDATE {$this->_tableName} SET {$fieldsUpdateLists} WHERE {$this->_primaryKey} = :id;";
        
    }//-------------------->> End _getUpdateSentence()
    
    /**
     * 
     * Replaces the placeholders in the Sentence by its final values.
     * @param   array $data Associative Array with the name of the params and its values (fieldName->value).
     * @throws  DAO_Exception
     * @uses    PDOStatement::bindValue($parameter, $variable, $data_type)
     */
    protected function _bindParams( array $data )
    {//-------------------->> _bindParams()
        
        $dataType = 0;
        $valueData = null;
        
        foreach( $data as $fieldName => $value ) {//---------->> foreach $fieldName=>$value
            
            if ( is_array( $value ) ) {//---------->> if $value is array
                
                $dataType = $this->_getPDOParamType( $value[ 'type' ] );
                
                if ( !isset( $value[ 'value' ] ) ) {//---------->> if $value array doesn't have a value property
                    throw new DAO_Exception( "No value defined for {$fieldName}" );
                }//---------->> End if $value array doesn't have a value property
                
                $valueData = $value[ 'value' ];
                
            } else {//---------->> else $value is not array
                
                $dataType = $this->_getPDOParamType( gettype( $value ) );
                $valueData = $value;
                
            }//---------->> End else
            
            $this->_pdoStatement->bindValue( ":{$fieldName}" , $valueData, $dataType );
            
        }//---------->> End foreach $fieldName=>$value
        
    }//-------------------->> End _bindParams()
    
    /**
     * 
     * Retrives the appropriate PDO::PARAM according to a param type. 
     * @param   string $paramType Definition of the type of one parameter.
     * @return  int
     */
    private function _getPDOParamType( $paramType )
    {//-------------------->> _getPDOParamType()
        
        switch ( strtoupper( $paramType ) ) {//---------->> switch $paramType
                
            case 'BLOB':
            case 'LOB':
                return PDO::PARAM_LOB;

            case 'BOOLEAN':
            case 'BOOL':
                return PDO::PARAM_BOOL;

            case 'INTEGER':
            case 'INT':
                return PDO::PARAM_INT;

            case 'NULL':
                return PDO::PARAM_NULL;

            default:
                return PDO::PARAM_STR;

        }//---------->> End switch $paramType
        
    }//-------------------->> End _getPDOParamType()
    
    /**
     * 
     * Tries to delete a record from the Table by its ID.
     * @param   int $id ID of the record to delete.
     * @throws  DAO_Exception
     * @return  boolean True if the record was successful deleted.
     */
    public function delete( $id )
    {//-------------------->> delete()
        
        $this->_sqlQuery = "DELETE FROM {$this->_tableName} WHERE {$this->_primaryKey} = :id;";
        $this->_executeStatement( array( 'id' => (int) $id ) );
        
        return ( $this->_pdoStatement->rowCount() > 0 );
        
    }//-------------------->> End delete()

    /**
     *
     * Prints the last executed SQL query when the object is treated as
     * String.
     * @return  string
     */
    public function __toString() 
    {//-------------------->> __toString()
        return $this->_sqlQuery;
    }//-------------------->> End __toString()
    
}//------------------------->> End Class Manager