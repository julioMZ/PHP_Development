<?php

    /**
     * As the instance is retrived by a Class method and by Singleton Dessing 
     * Pattern implementation, we could acces, by reference, at the same Register 
     * instance, and its internal registered values, in any scope of the system.
     */
    $registerTest = Register::getInstance();

    //Counting current positions on Register
        echo count( $registerTest );
    
    //---- RETRIVING VALUES ----//
    
        //Getting value by ArrayAccess::offsetGet implementation.
        var_dump( $registerTest->offsetGet( 'arrayConfig' ) );
        
        //Getting value like array access-get action.
        var_dump( $registerTest[ 'string' ] );
        
        //Getting value like object access-get action.
        var_dump( Register::getInstance()->number );
    
    //-------------------------//
    
    //---- ITERATING Register TO UNSET VALUES ----//
        foreach ( $registerTest as $key => $value ) {//---------->> foreach $registerTest
            unset( $registerTest[ $key ] );
        }//---------->> End foreach $registerTest
    //-------------------------------------------//
    