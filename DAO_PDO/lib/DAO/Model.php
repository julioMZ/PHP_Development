<?php

/**
 * 
 * DAO_CRUD derivation making use of DAO_CRUD_Manager composition.
 * It's intend is to represent a Data Base Table Access Gateway.
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
abstract class DAO_Model 
    implements DAO_CRUD
{//------------------------->> Class Model
    
    /**
     *
     * DAO_CRUD_Manager Instance responsable to perform
     * CRUD actions.
     * @var     DAO_CRUD_Manager
     */
    protected $_daoManager;
    
    /**
     *
     * Model constructor.
     * @param DAO_CRUD_Manager $daoManager DAO_CRUD_Manager instance to delegate
     * CRUD actions to.
     * @see     DAO_Model::init()
     */
    public function __construct( DAO_CRUD_Manager $daoManager )
    {//-------------------->> __construct()

        $this->_daoManager = $daoManager;
        $this->init();

    }//-------------------->> __construct()
    
    /**
     *
     * Template method in order to extend the constructor action.
     */
    abstract public function init();

    /**
     *
     * @param   int $id
     * @return  boolean
     * @throws  DAO_Exception
     * @see     DAO_CRUD_Manager::delete( $id )
     */
    public function delete( $id ) 
    {//-------------------->> delete()
        return $this->_daoManager->delete( $id );
    }//-------------------->> End delete()

    /**
     *
     * @param   array $config
     * @return  array
     * @throws  DAO_Exception
     * @see     DAO_CRUD_Manager::fetch( $config )
     */
    public function fetch( array $config = array() ) 
    {//-------------------->> fetch()
        return $this->_daoManager->fetch( $config );
    }//-------------------->> End fetch()

    /**
     *
     * @param   array $config
     * @return  array
     * @throws  DAO_Exception
     * @see     DAO_CRUD_Manager::fetchAll( $config )
     */
    public function fetchAll( array $config = array() ) 
    {//-------------------->> fetchAll()
        return $this->_daoManager->fetchAll( $config );
    }//-------------------->> End fetchAll()

    /**
     *
     * @param   int $id
     * @return  array
     * @throws  DAO_Exception
     * @see     DAO_CRUD_Manager::find( $id )
     */
    public function find( $id )
    {//-------------------->> find()
        return $this->_daoManager->find( $id );
    }//-------------------->> End find()

    /**
     *
     * @param   array $data
     * @return  int
     * @throws  DAO_Exception
     * @see     DAO_CRUD_Manager::save( $data )
     */
    public function save( array $data )
    {//-------------------->> save()

        $filterData = $this->getValidator()->validate( $data );
        return $this->_daoManager->save( $filterData );

    }//-------------------->> End save()

    /**
     *
     * Gateway to invoke an specific method for a model in a standar way.
     * @param   string $name
     * @param   mixed $arguments
     * @param   boolean $ignoreArgumentsType
     * @throws  Exception
     * @return  mixed
     */
    public function callMethod( $name, $arguments, $ignoreArgumentsType = false )
    {//-------------------->> callMethod()
        
        if ( !method_exists( $this, $name ) ) {//---------->> if no method in concrete object
            throw new BadMethodCallException( "There is no defined method with the name {$name}" );
        }//---------->> End if no method in concrete object
        
        return ( is_array( $arguments ) && (bool) $ignoreArgumentsType == false ) ? call_user_func_array( array( $this, $name ), $arguments ) : call_user_func( array( $this, $name ), $arguments );
        
    }//-------------------->> End callMethod()

    /**
     *
     * Tries to build and retrieve the DAO_Model_Validator for this concrete
     * DAO_Model instance.
     * @throws  Exception
     * @return  DAO_Model_Validator
     * @see     DAO_Factory::getDAOModelValidator( $validatorName )
     */
    public function getValidator()
    {//-------------------->> getVAlidator()

        $fullClassName = split( '_', get_class( $this ) );
        return DAO_Factory::getInstance()->getDAOModelValidator( $fullClassName[ count( $fullClassName ) - 1 ] );

    }//-------------------->> End getValidator()

    /**
     *
     * Returns the value of the last executed SQL query in this model.
     * @return  string
     */
    public function __toString()
    {//-------------------->> __toString()
        return $this->_daoManager->__toString();
    }//-------------------->> End __toString()
    
}//------------------------->> End Class Model