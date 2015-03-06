<?php

    //---- DEFINE ----//
        define( 'BASE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' .DIRECTORY_SEPARATOR );
            define( 'RESORCES_PATH', BASE_PATH . 'resources' . DIRECTORY_SEPARATOR );
    //----------------//
    
    //---- AUTOLOAD ----//

        /**
         *
         * Require a class by its name.
         * @param   string $className Name of the class to be required
         */
        function __autoload( $className ) 
        {//--------------->> ____autoload()
            $className = str_replace( '_', DIRECTORY_SEPARATOR, $className );
            require_once LIB_PATH . "$className.php";   
        }//--------------->> End ____autoload()
            
    //------------------//

    try {//---------->> try

        $config = parse_ini_file( RESORCES_PATH . 'config.ini', true );
        DAO_Factory::setConfig( $config[ 'DataBase' ] );

        $contactosModelo = DAO_Factory::getInstance()->getDAOModel( 'Contactos' );

        $datosContacto = array(
            'id'                    => 4,
            'nombre'                => 'Julio Adrián',
            'apellidos'             => 'Mora García',
            'puesto_departamento'   => 'Diversión',
            'correo_electronico'    => 'julioa.mora@gmail.com',
            'telefonos'             => array(
                array( 'tipo' => 'celular', 'numero' => '0445543534311' ),
                array( 'tipo' => 'oficina', 'numero' => '50220900', 'extension' => '2030' )
            )
        );

        var_dump( $contactosModelo->save( $datosContacto ) );

        var_dump( $contactosModelo->fetchAll( array( 'orderBy' => 'apellidos ASC' ) ) );
        echo $contactosModelo;

    } catch ( Exception $ex ) {//---------->> catch
        echo $ex->getMessage();
    }//---------->> End catch