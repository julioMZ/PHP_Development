<?php

    interface Data_Interface_Comparable
    {
        public function equals( Data_Interface_Object $object );
        public function contentEquals( $content = '' );
    }

?>