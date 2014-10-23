<?php

    //---- DEFINE ----//
        define( 'BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR );
            define( 'LIB_PATH', BASE_PATH . 'lib' . DIRECTORY_SEPARATOR );
            define( 'VIEWS_PATH', BASE_PATH . 'views' . DIRECTORY_SEPARATOR );
    //----------------//

    //---- REQUIRE ----//
        require_once LIB_PATH . 'View.php';
    //-----------------//
        
    try {//-------------------->> try
        
        View::setViewFilesRepository( VIEWS_PATH );
        
        $demoContent = new View( 'template.html' );
        $demoContent->title = 'Demo Test';
        $demoContent->setVar( 'content', View::getFileContent( VIEWS_PATH . 'content.html' ) );
        
        $compress = View_Compressor::compressView( $demoContent, true );
        
        $demo = new View( 'demo.html' );
        
        $demo->setVars( array(
            'noCompress' => htmlentities( $demoContent ),
            'compress' => htmlentities( $compress )
        ) );
        
        $demo->render();
        
    } catch ( Exception $exc ) {//-------------------->> catch
        echo $exc->getMessage();
    }//-------------------->> End try/catch
