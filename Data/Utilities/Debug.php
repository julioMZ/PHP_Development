<?php


    abstract class Data_Utilities_Debug
    {


        public static function dump( $var = '', $title = '', $show = true )
        {

            $varDump = null;

            ob_start();//<<----------------------- buffering
                var_dump( $var );
                $varDump = ob_get_contents();
            ob_end_clean();//<<------------------- End buffering

            if( $show === true ) {
                echo ( !empty( $title ) ) ? "<h3>{$title}</h3><pre>{$varDump}</pre>" : "<pre>{$varDump}</pre>";
            }

            return "{$title}\n{$varDump}";

        }


    }

?>