<?php

/**
 * 
 * Register API demo script
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */

    //---- GENERAL CONFIG ----//
        date_default_timezone_set( 'America/Mexico_City' );
        error_reporting( E_ERROR );
    //------------------------//

    //---- DEFINE ----//
        define( 'BASE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' . DIRECTORY_SEPARATOR );
            define( 'TEST_PATH', BASE_PATH . 'test' . DIRECTORY_SEPARATOR );
    //-----------------//
     
    //---- INCLUDE PATHS ----//  
        set_include_path( 
            get_include_path() . PATH_SEPARATOR . 
            BASE_PATH . PATH_SEPARATOR . 
            LIB_PATH
        ); 
    //-----------------------//
        
    //---- AUTOLOAD ----//
      
        function __autoload( $className ) 
        {//-------------------->> __autoload()
            
            $classPath = str_replace( '_', DIRECTORY_SEPARATOR, $className );
            
            //---- REQUIRE ----//
                require_once "{$classPath}.php";
            //-----------------//
            
        }//-------------------->> End __autoload()
    
    //------------------//
        
    try {//---------->> try
        
        //---- GETTING Register SINGLETON INSTANCE ----//
            $register = Register::getInstance();
        //---------------------------------------------//

        //---- REGISTERING VALUES ----//

            //Setting value by ArrayAccess::offsetSet implementation.
            $register->offsetSet( 'number', 1 );

            //Setting value like array access-set action.
            $register[ 'string' ] = 'Hello';

            //Setting value like object access-set action.
            $register->arrayConfig = array(
                'dbms' => 'mysql',
                'db_name' => 'db_name',
                'user'  => 'root',
                'password' => ''
            );

        //---------------------------//

        //---- INCLUDE TEST ----//
            include TEST_PATH . 'registerTest.php';
        //----------------------//

        //Asking if string position exist in Register
            var_dump( isset( $register[ 'string' ] ) );

        //Counting current positions on Register
            echo count( $register );
        
    } catch ( Exception $ex ) {//---------->> catch
        die( $ex->getMessage() );
    }//---------->> End try/catch