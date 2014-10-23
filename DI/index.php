<?php
    
    try {//---------->> try
    //
        //---- AUTOLOAD ----//
        
            require_once dirname( __FILE__ ). DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'DI' . DIRECTORY_SEPARATOR . 'Autoloader.php';
            
            DI_Autoloader::registerAutoload( array(
                'contextPath' => dirname( __FILE__ ) . DIRECTORY_SEPARATOR,
                'packages' => array( 
                    'PHP' => 'php' . DIRECTORY_SEPARATOR,
                        'LIB' => 'php' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR,
                    'RESOURCES' => 'resources' . DIRECTORY_SEPARATOR
                )
            ) );

        //------------------//
        
        $appContext = new DI_AppContext( new DI_ConfigFileParser_XML( DI_Autoloader::getPackagePathById( 'RESOURCES' ) . 'config.xml' ), new DI_ObjectFactory_Beans() );
        echo $appContext->get( 'BusFlee' )->run();
        
    } catch ( Exception $e ) {//---------->> catch
        echo "{$e->getMessage()}\n{$e->getTraceAsString()}\n";
    }//---------->> End try/catch