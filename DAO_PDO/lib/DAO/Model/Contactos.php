<?php

/**
 * 
 * Acceso a la Tabla contactos
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Model_Contactos 
    extends DAO_Model
{//------------------------->> Class DAO_Model_Contactos

    /**
     *
     * Inicialización del modelo
     */
    public function init() 
    {//-------------------->> init()
        $this->_daoManager->setTableName( 'contactos' );
    }//-------------------->> End init()

    /**
     *
     * Sobreescritura del método para obtener todos los registros de la tabla
     * junto con su unión de registros para teléfonos.
     * @param   array $config
     * @return  array
     * @throws  DAO_Exception
     */
    public function fetchAll( array $config = array() )
    {//-------------------->> fetchAll()

        if ( isset( $config[ 'fields' ] ) && is_array( $config[ 'fields' ] ) ) {

            foreach ( $config[ 'fields' ] as $index => $value ) {
                $config[ 'fields' ][ $index ] = "contactos.{$value}";
            }

            $config[ 'fields' ][] = 'telefonos.numeros_telefonicos';

        } else {
            $config[ 'fields' ] = array( 'contactos.*' , 'telefonos.numeros_telefonicos' );
        }
        
        $selectConfig = array(
            'fields'    => $config[ 'fields' ],
            'join'      => array( "AS contactos INNER JOIN contactos_telefonos AS telefonos ON telefonos.contacto = contactos.{$this->_daoManager->getPrimaryKey()}" )
        );

        if ( isset( $config[ 'orderBy' ] )  && !empty( $config[ 'orderBy' ] ) ) {
            $selectConfig[ 'orderBy' ] = "contactos.{$config[ 'orderBy' ]}";
        }

        return $this->_daoManager->fetch( $selectConfig );

    }//-------------------->> End fetchAll()

    /**
     *
     * Ejemplo de transacción entre contactos y números telefónicos
     * @param   array $data
     * @return  int
     */
    public function save( array $data )
    {//-------------------->> save()
        
        $Factory = DAO_Factory::getInstance();
        $PDO = $Factory->getPDOInstance();

        try {//---------->> try
            
            $PDO->beginTransaction();
                
                $result = parent::save( $data );
                $id = ( isset( $data[ 'id' ] ) ) ? $data[ 'id' ] : $result;
                
                $Factory->getDAOModel( 'ContactosTelefonos' )->save( array( 'contacto' => $id, 'telefonos' => $data[ 'telefonos' ] ) );
                
            
            $PDO->commit();
            
            return $id;
            
        } catch ( Exception $ex ) {//---------->> catch
            
            $PDO->rollBack();
            throw $ex;
            
        }//---------->> End catch
            
    }//-------------------->> End save()

    /**
     *
     * Borrado lógico
     * @param   int $id
     * @return  boolean
     */
    public function delete( $id ) 
    {//-------------------->> delete()
        return ( parent::save( array( $this->_daoManager->getPrimaryKey() => (int) $id, 'activo' => 0 ) ) > 0 );
    }//-------------------->> End delete()
    
}//------------------------->> End Class DAO_Model_Contactos