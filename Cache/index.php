<?php

/**
 * 
 * Cache API demo script
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
        
     //---- GENERAL CONFIG ----//
        date_default_timezone_set( 'America/Mexico_City' );
        error_reporting( 0 );
     //------------------------//

     //---- DEFINE ----//
        define( 'BASE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );   
            define( 'ACTIONS_PATH', BASE_PATH . 'actions' . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' . DIRECTORY_SEPARATOR );
            define( 'RESOURCES_PATH', BASE_PATH . 'resources' . DIRECTORY_SEPARATOR  );
            define( 'TESTS_PATH', BASE_PATH . 'tests' . DIRECTORY_SEPARATOR  );
            define( 'VIEWS_PATH', BASE_PATH . 'views' . DIRECTORY_SEPARATOR );
     //-----------------//
     
     //---- INCLUDE PATHS ----//  
        set_include_path( 
            get_include_path() . PATH_SEPARATOR . 
            BASE_PATH . PATH_SEPARATOR . 
            ACTIONS_PATH .PATH_SEPARATOR .
            LIB_PATH . PATH_SEPARATOR . 
            RESOURCES_PATH . PATH_SEPARATOR . 
            TESTS_PATH . PATH_SEPARATOR . 
            VIEWS_PATH
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
        
        //---- INI CONFIG ----//
            $config = (array) parse_ini_file( 'config.ini', true );
        //--------------------//
       
    //---- CONTROLLER ----//
    
        try {//-------------------->> try

            $action = ( isset( $_GET[ 'action' ] ) && !empty( $_GET[ 'action' ] ) ) ? strtolower( $_GET[ 'action' ] ) : 'file';
            $actionPath = ACTIONS_PATH . "{$action}Action.php";
            $viewPath = VIEWS_PATH . "{$action}View.php";
            
            if( !file_exists( $actionPath ) ) {//---------->> if action doesn't exist
                die( "No action {$action} found" );
            }//---------->> End if action doesn't exist

            //---- REQUIRE ACTION ----//
                require_once $actionPath;
            //------------------------//
                
            if( !file_exists( $viewPath ) ) {//---------->> if action doesn't exist
                die( "No view for {$action} action found" );
            }//---------->> End if action doesn't exist

            //---- INCLUDE VIEW ----//
                include_once $viewPath;
            //------------------------//

        } catch ( Exception $exc ) {//-------------------->> catch
            echo "{$exc->getMessage()}\n{$exc->getTraceAsString()}";
        }//-------------------->> End try/catch
    
    //--------------------//