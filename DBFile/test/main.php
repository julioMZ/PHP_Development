<?php

    //---- DEFINE ----//
        define( 'BASE_PATH', dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' . DIRECTORY_SEPARATOR );
            define( 'RESOURCES_PATH', BASE_PATH .'resources' . DIRECTORY_SEPARATOR );
                define( 'FILES_PATH', RESOURCES_PATH . 'files' . DIRECTORY_SEPARATOR );
    //----------------//
            
    //---- AUTOLOAD ----//
    
        /**
         * 
         * Tries to load dynamically a Class or Interface.
         * @param   string $className Name of the Class to be dynamically loaded.
         */
        function __autoload( $className )
        {//-------------------->> __autoload()
            $classPath = str_replace( '_', DIRECTORY_SEPARATOR, $className );
            require_once LIB_PATH . "{$classPath}.php";   
        }//-------------------->> End __autoload()
            
    //------------------//
            
    //---- CONFIG ----//
        $config = parse_ini_file( RESOURCES_PATH . 'config.ini', true );
        $configDb = $config[ 'DataBase' ];
        $configDbFile = $config[ 'DBFile' ];
    //----------------//
            
    //---- PROGRAM ----//
    
        try {//---------->> try
            
            //---- PDO CONNECTION ----//
                $pdoInstance = new PDO( 
                    "{$configDb[ 'dbms' ]}:host={$configDb[ 'host' ]};dbname={$configDb[ 'dbname' ]}", 
                    $configDb[ 'user' ], 
                    $configDb[ 'pass' ] 
                );
                $pdoInstance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                $pdoInstance->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            //------------------------//
            
            //---- DAO INSTANCE ----//
                $daoInstance = new DAO_Manager( array(
                    'pdoInstance' => $pdoInstance,
                    'tableName' => $configDbFile[ 'table_name' ]
                ) );
            //----------------------//

            //---- DBFile INSTANCE ----//
                $dbFileInstance = new DBFile_Manager( array(
                    'daoInstance' => $daoInstance,
                    'validator' => new DBFile_File_Validator( array( 
                        'max_file_size' => $configDbFile[ 'max_file_size' ],
                        'allowed_mime_types' => $configDbFile[ 'allowed_mime_types' ]
                     ) ),
                    'encryption' => new DBFile_File_Encryption()
                ) );
            //------------------------//
            
            //---- TEST CASES ----//
                $filePath = FILES_PATH . 'document.pdf';
                $fileData = $dbFileInstance->download( $dbFileInstance->save( DBFile_File_Builder::getFileFromPath( $filePath ), false ) );
            //--------------------//
                
        } catch( Exception $ex ) {//---------->> catch
            echo $ex->getMessage();
        }//---------->> End try/catch
        
    //-----------------//