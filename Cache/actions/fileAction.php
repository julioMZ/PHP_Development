<?php

    //---- FILE CACHE SYSTEM CONFIG ----//
        $cacheConf = $config[ 'CacheConfig' ];
        CacheManager_File::setBaseRepositoryPath( $cacheConf[ 'file_sys_repo_path' ] );
        
        /*
        $cacheManager = new CacheManager_File( array( 
            'expTime' => $cacheConf[ 'expTime' ] ) 
        );
        */
        
        $cacheManager = CacheManager_Factory::getCacheManager( array(
            'type' => 'File',
            'consArgs' => array( 
                'expTime' => $cacheConf[ 'expTime' ] 
            )
        ) );
        
    //---------------------------------//

    //---- INCLUDE TEST ----//
        include TESTS_PATH . 'cacheTest.php';
    //----------------------//
        
    //---- VIEW CONFIG ----//
        $type = 'File';
    //---------------------//
