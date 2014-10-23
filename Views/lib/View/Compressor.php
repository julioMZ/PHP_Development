<?php

/**
 * 
 * @package     View
 * @category    String Compressor
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     0.1
 * @desc        Class to compress various View output sttrings.
 */
    class View_Compressor 
    {//---------------------------------------->> Class View_Compressor
        
        /**
         * 
         * Private object Constructor to ensure only static access.
         * @access  private
         */
        private function __construct() 
        {//---------------------------------------->> __construct()
            
        }//---------------------------------------->> End __construct()
        
        /**
         * 
         * Compress the View acording to its type and returns a new string with 
         * the resulting process.
         * @static
         * @param   View $view View instance object where the output string will 
         *          be retrived.
         * @param   boolean $processView Flag to define if the View::render() mehtod
         *          will be invoked internaly. Boolean false value by default.
         * @return  string
         * @see     View_Compressor::compressString()
         * @see     View_Compressor::compressXMLString()
         */
        public static function compressView( View $view, $processView = false )
        {//---------------------------------------->> compressView()
            
            $viewContent = ( (bool) $processView == true ) ? $view->render( false ) : $view->getView();
            $viewType = $view->getViewType();
            $compressedViewContent = ( $viewContent == 'html' || $viewContent == 'xml' ) ? self::compressXMLString( $viewContent ) : self::compressString( $viewContent );
            
            return $compressedViewContent;
            
        }//---------------------------------------->> End compressView()
        
        /**
         * 
         * Removes tabs and return chars on the recived string and returns a new
         * string with the resulting process.
         * @static
         * @param   string $string Original string to be compressed.
         * @return  string
         */
        public static function compressString( $string = '' ) 
        {//---------------------------------------->> compressString()
            
            $compressedString = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string );
            $compressedString = str_replace( array( "\r\n", "\r", "\n", "\t", '    ', '     ', '      '), '', $compressedString );
            
            return $compressedString;
            
        }//---------------------------------------->> End compressString()
        
        /**
         * 
         * Removes comentaries, tabs and return chars on the recived string 
         * and returns a new string with the resulting process.
         * @static
         * @param   string $xmlString XML string to be compressed.
         * @return  string
         * @see     View_Compressor::compressString()
         */
        public static function compressXMLString( $xmlString = '' )
        {//---------------------------------------->> compressXMLString()
            $compressedString = preg_replace( '(<!-- (.*)-->)', '', self::compressString( $xmlString ) );
            return $compressedString;
        }//---------------------------------------->> End compressXMLString()

    }//---------------------------------------->> End Class View_Compressor
