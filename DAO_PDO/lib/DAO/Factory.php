<?php

/**
 * 
 * Abstract Factory for DAO_CRUD, DAO_Model and DAO_Model_Validator
 * concrete objects.
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Factory 
{//------------------------->> Class DAO_Factory
    
    /**
     *
     * Type of Manager and PDO connection config
     * @var     array
     * @static
     * @access  private
     */
    private static $_config;

    /**
     *
     * Singleton instance
     * @var     DAO_Factory
     * @static
     * @access  private
     */
    private static $_instance;
    
    /**
     *
     * PDO Instance
     * @var     PDO
     * @access  private
     */
    private $_pdoInstance;
    
    /**
     *
     * DAO_CRUD_Manager Instance
     * @var     DAO_CRUD
     * @access  private
     */
    private $_daoInstance;
    
    /**
     *
     * Array of DAO_Model concrete instances.
     * @var     array
     * @access  private
     */
    private $_models = array();
    
    /**
     *
     * Aray of DAO_Model_Validator concrete instances.
     * @var     array
     * @access  private
     */
    private $_validators = array();

    /**
     *
     * @param array $config Associative Array with the following structure:
     * <table border=1>
     *  <tr>
     *      <th>Key</th><th>Type</th><th>Mandatory</th><th>Description</th>
     *  </tr>
     *  <tr>
     *      <td>dbms</td><td>string</td><td>Yes</td><td>Name of the DBMS to be used</td>
     *  </tr>
     *  <tr>
     *      <td>host</td><td>string</td><td>Yes</td><td>IP of alias of the server where the DBMS is located</td>
     *  </tr>
     *  <tr>
     *      <td>dbname</td><td>string</td><td>Yes</td><td>Name of the Data Base to be used</td>
     *  </tr>
     *  <tr>
     *      <td>user</td><td>string</td><td>Yes</td><td>User name to be used to login into the DB</td>
     *  </tr>
     *  <tr>
     *      <td>pass</td><td>string</td><td>Yes</td><td>Password to be used to login into the DB</td>
     *  </tr>
     *  <tr>
     *      <td>active_field</td><td>boolean</td><td>No</td><td>Flag to define the type if DAO_CRUD_Manager_Active will be used. False by default.</td>
     *  </tr>
     * </table>
     * @throws  DAO_Exception
     */
    public static function setConfig( array $config )
    {//-------------------->> setConfig()

        if ( !isset( $config[ 'dbms' ] ) || empty( $config[ 'dbms' ] ) ) {//---------->> if no dbms provided
            throw new DAO_Exception( 'No dbms property found' );
        }//---------->> End if no dbms provided

        if ( !isset( $config[ 'host' ] ) || empty( $config[ 'host' ] ) ) {//---------->> if no host provided
            throw new DAO_Exception( 'No host property found' );
        }//---------->> End if no host provided

        if ( !isset( $config[ 'dbname' ] ) || empty( $config[ 'dbname' ] ) ) {//---------->> if no dbname provided
            throw new DAO_Exception( 'No dbname property found' );
        }//---------->> End if no dbname provided

        if ( !isset( $config[ 'user' ] ) ) {//---------->> if no user provided
            throw new DAO_Exception( 'No user property found' );
        }//---------->> End if no user provided

        if ( !isset( $config[ 'pass' ] ) ) {//---------->> if no pass provided
            throw new DAO_Exception( 'No pass property found' );
        }//---------->> End if no pass provided

        self::$_config = $config;

    }//-------------------->> End setConfig()

    /**
     *
     * Private Constructor to ensure static access and implement the Singleton
     * Design Pattern.
     * @access  private
     */
    private function __construct()
    {//-------------------->> __construct()

    }//-------------------->> End __consruct()

    /**
     *
     * Retrieves the Singleton Instance of this Factory.
     * @return  DAO_Factory
     */
    public static function getInstance()
    {//-------------------->> getInstance()

        if ( is_null( self::$_instance ) ) {//---------->> if instance is null
            self::$_instance = new self();
        }//---------->> End if instance is null

        return self::$_instance;

    }//-------------------->> End getInstance()
    
    /**
     *
     * Generates and returns the PDO Instance according to the provided
     * connection config in a singleton way.
     * @return  PDO
     * @throws  Exception
     */
    public function getPDOInstance()
    {//-------------------->> getPDOInstance()
        
        if ( is_null( $this->_pdoInstance ) ) {//---------->> if null PDO instance

            if ( empty( self::$_config ) ) {//---------->> if no config provided
                throw new DAO_Exception( 'No config array provided' );
            }//---------->> End if no config provided

            $this->_pdoInstance = new PDO(
                self::$_config[ 'dbms' ] . ':host=' . self::$_config[ 'host' ] . ';dbname=' . self::$_config[ 'dbname' ], 
                self::$_config[ 'user' ], 
                self::$_config[ 'pass' ] 
            );
            
            $this->_pdoInstance->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            
            if ( self::$_config[ 'dbms' ] == 'mysql' ) {//---------->> if mysql as dbms
                $this->_pdoInstance->setAttribute( PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8' );
            }//---------->> End if mysql as dbms
            
        }//---------->> End if null PDO instance
        
        return $this->_pdoInstance;
        
    }//-------------------->> End PDOInstance()
    
    /**
     *
     * Generates and retrives a DAO_CRUD_Manager instance.
     * @param   boolean $singleton Flag to define if the instance will be treated
     * as a singleton object. If its false, a new instance will be returner for each
     * invocation.
     * @return  DAO
     */
    public function getDAOCRUDInstance( $singleton = true )
    {//-------------------->> getDAOCRUDInstance()
        
        if ( $singleton == false ) {//---------->> if not singleton
            return $this->_getDAOCRUDManagerInstance();
        }//---------->> End if not singleton
        
        if ( is_null( $this->_daoInstance ) ) {//---------->> if singleton and null instance
            $this->_daoInstance = $this->_getDAOCRUDManagerInstance();
        }//---------->> End if singleton and null instance
        
        return $this->_daoInstance;
        
    }//-------------------->> End getDAOCRUDInstance()
    
    /**
     *
     * Builds and retieves a DAO_CRUD_Manager instance according to the 
     * self::$_config[ 'active_field' ] property.
     * <p>If that property is setted as <i>true</i>, a DAO_CRUD_Manager_ActiveField
     * instance will be returned.</p>
     * @access  private
     * @return  DAO_CRUD_Manager
     */
    private function _getDAOCRUDManagerInstance()
    {//-------------------->> _getDAOCRUDManagerInstance()

        $config = array( 'pdoInstance' => $this->getPDOInstance(), 'tableName' => '_' );
        return ( isset( self::$_config[ 'active_field' ] ) && self::$_config[ 'active_field' ] == true ) ? 
            new DAO_CRUD_Manager_ActiveField( $config ) : new DAO_CRUD_Manager( $config );

    }//-------------------->> End _getDAOCRUDManagerInstance()
    
    /**
     *
     * Tries to generate and retrieve a concrete DAO_Model class in a singleton way.
     * @param   string $modelName Name of the Model Class file to be retrieved
     * without extention.
     * @return  DAO_Model
     * @throws  Exception
     */
    public function getDAOModel( $modelName = '' )
    {//-------------------->> getDAOModel()
        
        if ( !isset( $this->_models[ $modelName ] ) || is_null( $this->_models[ $modelName ] ) ) {//---------->> if no model in models
            
            $modelClass = new ReflectionClass( "DAO_Model_{$modelName}" );

            if ( !$modelClass->isSubclassOf( 'DAO_Model' ) ) {//---------->> if class does not extend DAO_Model Abstract Class
                throw new DAO_Exception( "The class {$modelClass} is not a sub class of DAO_Model" );
            }//---------->> End if class does not extend DAO_Model Abstract Class

            $manager = $this->getDAOCRUDInstance( false );

            $this->_models[ $modelName ] = $modelClass->newInstanceArgs( array( $manager ) );

        }//---------->> End if no model in models
        
        $this->_models[ $modelName ]->init();
        
        return $this->_models[ $modelName ];
        
    }//-------------------->> End getDAOModel()
    
    /**
     *
     * Tries to generate and retrieve a concrete DAO_Model_Validator class in
     * a singleton way.
     * @param   string $modelName Name of the Model Class file to be retrieved
     * without extention.
     * @return  DAO_Model_Validator
     */
    public function getDAOModelValidator( $modelName = '' )
    {//-------------------->> getDAOModelValidator()
        
        if ( !isset( $this->_validators[ $modelName ] ) || is_null( $this->_validators[ $modelName ] ) ) {//---------->> if no validator in validators
            
            $validatorClass = new ReflectionClass( "DAO_Model_Validator_{$modelName}" );

            if ( !$validatorClass->isSubclassOf( 'DAO_Model_Validator' ) ) {//---------->> if class does not implement DAO_Model_Validator interface
                throw new DAO_Exception( "The class {$validatorClass} not implemets the DAO_Model_Validator interface" );
            }//---------->> End if class does not implement DAO_Model_Validator interface

            $this->_validators[ $modelName ] = $validatorClass->newInstance();

        }//---------->> End if not validator in validators
        
        return $this->_validators[ $modelName ];
        
    }//-------------------->> End getDAOModelValidator()
    
    
}//------------------------->> End Class DAO_Factory