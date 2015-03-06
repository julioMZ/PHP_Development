<?php

    define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] );
    define( 'PROYECT_ROOT', dirname( dirname( $_SERVER['PHP_SELF'] ) ) . DIRECTORY_SEPARATOR );
    define( 'LIB_ROOT', ROOT. PROYECT_ROOT );
    define( 'JUMP', "<br />\n" );


    function __autoload( $className ) {
        $classPath = str_replace( '_', DIRECTORY_SEPARATOR, $className );
        require_once LIB_ROOT . "{$classPath}.php";
    }

    try {

        $string = new Data_Type_String( 'Test Value' );
        Data_Utilities_Debug::dump( $string, 'String Type' );
        Data_Utilities_Debug::dump( $string->getClass() );

        $int = new Data_Type_Integer( '101010', 2 );
        Data_Utilities_Debug::dump( $int, 'Integer Type' );
        Data_Utilities_Debug::dump( $int->getClasses() );
        Data_Utilities_Debug::dump( $int->toDecimalString(), 'Decimal Format' );
        Data_Utilities_Debug::dump( $int->toBinaryString(), 'Binary Format' );
        Data_Utilities_Debug::dump( $int->toHexString(), 'Hex Format' );
        Data_Utilities_Debug::dump( $int->toOctalString(), 'Octal Format' );

        $float = new Data_Type_Float();
        $float->setVar( '-30.5' );
        Data_Utilities_Debug::dump( $float, 'Float Type' );

        $boolean = new Data_Type_Boolean( 'test' );
        Data_Utilities_Debug::dump( $boolean, 'Boolean Type' );

        $array = new Data_Type_Array( $string->getVar() );
        
        for ( $i = 1; $i < 6; $i++ ) {
            $array[] = "Test Value {$i}";
        }

        $array->add( 30, 'Last Index' );
        $array->remove( 5 );

        Data_Utilities_Debug::dump( $array, 'Array Type' );

        foreach ( $array as $index => $value ) {
            echo "{$index}.- {$value}" . JUMP;
        }

        $arrayTypes = new Data_Type_Collection_Types();
        $arrayTypes->add( $string )->add( $int )->add( $float )->add( $boolean )->add( $array );
        Data_Utilities_Debug::dump( $arrayTypes, 'Array Types' );

        $newArray = Data_Type_Array::valueOf( $int );
        Data_Utilities_Debug::dump( $newArray, 'Integer to Array Conversion' );

        $newString = Data_Type_String::valueOf( $int );
        Data_Utilities_Debug::dump( $newString, 'Integer to String Conversion' );

        $newFloat = Data_Type_Float::valueOf( $int );
        Data_Utilities_Debug::dump( $newFloat, 'Integer to Float Conversion' );

        $newBoolean = Data_Type_Boolean::valueOf( $int );
        Data_Utilities_Debug::dump( $newBoolean, 'Integer to Boolean Conversion' );

        $newArrayTypes = Data_Type_Collection_Types::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayTypes, 'Integer to Types Collection' );

        $newArrayArrays = Data_Type_Collection_Arrays::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayArrays, 'Integer to Arrays Collection' );

        $newArrayInts = Data_Type_Collection_Integers::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayInts, 'Integer to Integers Collection' );

        $newArrayStrings = Data_Type_Collection_Strings::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayStrings, 'Integer to Strings Collection' );

        $newArrayBools = Data_Type_Collection_Booleans::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayBools, 'Integer to Booleans Collection' );

        $newArrayFloats = Data_Type_Collection_Floats::valueOf( $int );
        Data_Utilities_Debug::dump( $newArrayFloats, 'Integer to Floats Collection' );

    } catch ( Exception $e ) {
        echo $e->getMessage();
    }

?>
