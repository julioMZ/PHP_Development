<?php

    //---- Simple Data Example ----//
        $cacheID = 'time';
        $data = date( 'H:i:s' );

        //Retriving cache content without taking care about expiration time
        $cacheFileData = $cacheManager->getCacheContent( $cacheID );

        //Retriving cache content taking care about expiration time
        $cacheData = $cacheManager->getCache( $cacheID );

        if ( $cacheData == null ) {//-------------------->> if cache has expired

            $cacheData = $data;
            $cacheManager->save( $cacheData, $cacheID );

        }//-------------------->> End if cache has expired
    //----------------------------//

    //---- Serialized Data Example ----//
        $cacheID = 'timeArray';

        //Retriving cache content without taking care about expiration time and without unserialize data
        $serializedCacheFileData = $cacheManager->getCacheContent( $cacheID, false );

        //Retriving cache content taking care about expiration time
        $serializedCacheData = $cacheManager->getCache( $cacheID );

        if ( $serializedCacheData == null ) {//-------------------->> if cache has expired

            $serializedCacheData = array( 'time' => $data );
            $cacheManager->save( $serializedCacheData, $cacheID );

        }//-------------------->> End if cache has expired
    //----------------------------//