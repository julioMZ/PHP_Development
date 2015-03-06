<?php

/**
 * 
 * Description of DAO_Model_ContactosTelefonos
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Model_ContactosTelefonos 
    extends DAO_Model
{//------------------------->> Class DAO_Model_ContactosTelefonos
    
    public function init() 
    {
        $this->_daoManager->setTableName( 'contactos_telefonos' );
    }
    
    public function save( array $data ) 
    {
        
        if ( !array_key_exists( 'telefonos', $data ) ) {
            throw new InvalidArgumentException( 'Debe proporcionar por lo menos un teléfono para el Contacto' );
        }
        
        if ( !isset( $data[ 'contacto' ] ) ) {
            throw new InvalidArgumentException( 'Debe proporcionar el identificador del Contacto para almacenar sus números telefónicos' );
        }
        
        $telefonos = array();
        
        if ( !empty( $data[ 'telefonos' ] ) ) {
            
            if ( isset( $data[ 'telefonos' ][ 0 ] ) ) {
        
                $iteraciones = count( $data[ 'telefonos' ] ) - 1;

                for ( $i = 0; $i <= $iteraciones; $i++ ) {
                    $telefonos[ $data[ 'telefonos' ][ $i ][ 'tipo' ] ][] = $this->getValidator()->validate( $data[ 'telefonos' ][ $i ] );
                }

            } else {
                $telefonos[ $data[ 'telefonos' ][ 'tipo' ] ] = $this->getValidator()->validate( $data[ 'telefonos' ] );
            }
            
        }
        
        $transacData = array( 'contacto' => $data[ 'contacto' ], 'numeros_telefonicos' => json_encode( $telefonos ) );
        $id = $this->contactoTieneTelefonos( $data[ 'contacto' ] );
        
        if ( $id > 0 ) {
            $transacData[ $this->_daoManager->getPrimaryKey() ] = $id;
        }
        
        return $this->_daoManager->save( $transacData );
        
    }
    
    public function contactoTieneTelefonos( $contactoID )
    {
        
        $result = $this->_daoManager->fetch( array(
            'fields'    => array( $this->_daoManager->getPrimaryKey() ),
            'where'     => 'contacto = :contactoID',
            'params'    => array( 'contactoID' => (int) $contactoID )
        ) );
        
        return ( !empty( $result ) ) ? $result[ 0 ][ $this->_daoManager->getPrimaryKey() ] : 0;
        
    }
    
}//------------------------->> End Class DAO_Model_ContactosTelefonos