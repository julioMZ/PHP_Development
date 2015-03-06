<?php

/**
 * 
 * Description of DAO_Validator_Contactos
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DAO_Model_Validator_Contactos
    implements DAO_Model_Validator
{//------------------------->> Class DAO_Validator_Contactos
    
    public function getValidationRules() 
    {
        
        return array(
            'id'        => FILTER_VALIDATE_INT,
            'nombre'    => array( 
                'filter'    => FILTER_VALIDATE_REGEXP, 
                'options'   => array( 'regexp' => '/^[A-Za-záéíóúñ\'\s]{3,45}$/' ) 
            ),
            'apellidos' => array( 
                'filter'    => FILTER_VALIDATE_REGEXP, 
                'options'   => array( 'regexp' => '/^[A-Za-záéíóúñ\'\s]{3,80}$/' ) 
            ),
            'puesto_departamento'  => array( 
                'filter'    => FILTER_VALIDATE_REGEXP, 
                'options'   => array( 'regexp' => '/^[A-Za-záéíóúñ&\/\s]{2,80}$/' ) 
            ),
            'correo_electronico'    => FILTER_VALIDATE_EMAIL,
            'active'    => array(
                'filter'    => FILTER_VALIDATE_INT,
                'options'   => array( 'min_range' => 0, 'max_range' => 1 )
            )
        );
        
    }
    
    public function validate( array $data ) 
    {
        
        $filterData = filter_var_array( $data, $this->getValidationRules() );
        
        if ( !is_null( $filterData[ 'id' ] ) && $filterData[ 'id' ] == false  ) {
            throw new InvalidArgumentException( 'El identificador del Contacto no es válido' );
        }
        
        if ( is_null( $filterData[ 'id' ] ) ) {
            unset( $filterData[ 'id' ] );
        }
        
        if ( empty( $filterData[ 'nombre' ] ) ) {
            throw new InvalidArgumentException( 'Verifique que la longitud del Nombre del Contacto no sea menor a 3 caracteres o mayor a 45 caracteres' );
        }
        
        $filterData[ 'nombre' ] = utf8_decode( $filterData[ 'nombre' ] );
        
        if ( empty( $filterData[ 'apellidos' ] )  ) {
            throw new InvalidArgumentException( 'Verifique que la longitud de los Apellidos del Contacto no sea menor a 3 caracteres o mayor a 80 caracteres' );
        }
        
        $filterData[ 'apellidos' ] = utf8_decode( $filterData[ 'apellidos' ] );
        
        if ( empty( $filterData[ 'puesto_departamento' ] )  ) {
            throw new InvalidArgumentException( 'Verifique que la longitud del Puesto o Deparamento del Contacto no sea menor a 2 caracteres o mayor a 80 caracteres' );
        }
        
        $filterData[ 'puesto_departamento' ] = utf8_decode( $filterData[ 'puesto_departamento' ] );
        
        if ( !is_null( $filterData[ 'correo_electronico' ] ) && $filterData[ 'correo_electronico' ] == false  ) {
            throw new InvalidArgumentException( 'Proporcione una cuenta de correo electrónico válida para el Contacto' );
        }
        
        if( is_null( $filterData[ 'correo_electronico' ] ) ) {
            unset( $filterData[ 'correo_electronico' ] );
        }
        
        if ( is_null( $filterData[ 'active' ] ) || $filterData[ 'active' ] === false  ) {
            $filterData[ 'active' ] = 1;
        }
        
        return $filterData;
        
    }
    
}//------------------------->> End Class DAO_Validator_Contactos