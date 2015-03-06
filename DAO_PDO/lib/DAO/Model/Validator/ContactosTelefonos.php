<?php

/**
 * 
 * Description of DAO_Validator_ContactosTelefonos
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Model_Validator_ContactosTelefonos
    implements DAO_Model_Validator
{//------------------------->> Class DAO_Validator_ContactosTelefonos
    
    public function getValidationRules() 
    {
        
        return array(
            'tipo'  =>  array( 
                'filter' => FILTER_VALIDATE_REGEXP, 
                'options' => array( 'regexp' => '/^(oficina|celular)$/' ) 
            ),
            'numero' => array( 
                'filter'    => FILTER_VALIDATE_REGEXP, 
                'options'   => array( 'regexp' => '/^[\d-]{8,15}$/' ) 
            ),
            'extension' => array( 
                'filter'    => FILTER_VALIDATE_REGEXP, 
                'options'   => array( 'regexp' => '/^[\d-]{2,5}$/' ) 
            )
        );
        
    }

    public function validate( array $data )
    {
        
        $filterData = filter_var_array( $data, $this->getValidationRules() );
        
        if( empty( $filterData[ 'tipo' ] ) ) {
            throw new InvalidArgumentException( 'Asegurece de que el tipo de Teléfono es oficina o celular' );
        }
        
        unset( $filterData[ 'tipo' ] );
        
        if ( empty( $filterData[ 'numero' ] ) ) {
            throw new InvalidArgumentException( "El número telefónico {$data[ 'numero' ]} no puede ser menor a 8 digitos y no mayor a 15" );
        }
        
        settype( $filterData[ 'numero' ], 'string' );
        
        if ( !is_null( $filterData[ 'extension' ] ) && $filterData[ 'extension' ] == false ) {
            throw new InvalidArgumentException( "La extensión {$data[ 'extension' ]} del número telefónico {$data[ 'numero' ]} debe ser una secuencia numerica no menor a 2 digitos y no mayor a 5" );
        }
        
        if( is_null( $filterData[ 'extension' ] ) ) {
            unset( $filterData[ 'extension' ] );
        } else {
            settype( $filterData[ 'extension' ], 'string' );
        }
        
        return $filterData;
        
    }
    
}//------------------------->> End Class DAO_Validator_ContactosTelefonos