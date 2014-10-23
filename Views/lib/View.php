<?php

    //---- REQUIRE DEPENDENCIES ----//
    
        if ( !defined( 'VIEW_PACKAGE_PATH' ) ) {
            define( 'VIEW_PACKAGE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR );
        }
        
        require_once VIEW_PACKAGE_PATH . 'Exception.php';
        require_once VIEW_PACKAGE_PATH . 'Compressor.php';
        
    //------------------------------//

/**
 * 
 * Class        View
 * Object to represent the View component from the MVC pattern.
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 * @version     2.7
 * @category    Views
 */    
    class View
    {//------------------------------------------------------------------>> Class View
        
        /**
         * 
         * String Pattern to be searched in template definition as dynamic
         * content (placeholders).
         * @var     string $_tempVarDef
         * @static
         * @access  private
         */
    	private static $_tempVarDef = '#\{{([a-z0-9\-_]*?)\}}#is';
        
        /**
         * 
         * Default Directory where the template files are located.
         * @var     string $_viewFilesRepository
         * @static	
         * @access  private
         */
        private static $_viewFilesRepository = '';
        
        /**
         * 
         * View Type (file extension).
         * @var     string $_viewType
         * @access  private
         */
        private $_viewType = '';
        
        /**
         * 
         * Path where the file to be used as templete is located.
         * @var     string $_viewFilePath
         * @access  private 
         */
        private $_viewFilePath = '';
        
        /**
         * 
         * Associative Array (pair->value) containing the value 
         * of the variables to be remplaced on the templete.
         * @var     array $_vars
         * @access  private
         */ 
        private $_vars = array();
        
        /**
         * 
         * Original Templete string.
         * @var     string $_template
         * @access  private
         */
        private $_template = '';
        
        /**
         * 
         * Resulting view string after the variables value substitution 
         * on $_template property.
         * @var     string $_view
         * @access  private
         */ 
        private $_view = '';
        
        /**
         * 
         * View Object Constructor.
         * Tries to set the file to be used as template if the param $filePath is 
         * not empty.
         * @param   string $tempDef File path or string to be used as templete.
         * @param   boolean $fromFile Flag to define if the template will be set 
         *          since a file content or since the $tempDef param value. Boolean
         *          true is the default value.
         * @throws  Exception
         * @see     View::setTemplate()
         */
        public function __construct( $tempDef = '', $fromFile = true ) 
        {//---------------------------------------->> __construct()
            
            if( !empty( $tempDef ) ) {//-------------------->> if not empty param
                $this->setTemplate( $tempDef, (bool) $fromFile );
            }//-------------------->> End if not empty param
            
        }//---------------------------------------->> End __construct()
        
        /**
         * 
         * Sets a directory path as the template files repository.
         * An Exception will be thrown if the path is not a directory or if the
         * directory has not read permissions.
         * @static
         * @param   string $dirPath
         * @throws  Exception
         * @see     View::_throwViewException()
         */
        public static function setViewFilesRepository( $dirPath = '' )
        {//---------------------------------------->> setViewFilesRepository()
            
            if( !is_dir( $dirPath ) || !is_readable( $dirPath ) ) {//-------------------->> if param is not a dir
                self::_throwViewException( "Directory {$dirPath} not found or without read permissions" );
            }//-------------------->> End if param is not a dir
            
            self::$_viewFilesRepository = $dirPath;
            
        }//---------------------------------------->> End setViewFilesRepository()
        
        /**
         * 
         * Tries to set a new Template Variable Definition Pattern to be match
         * in Dinamic Templates Definitions.
         * @static
         * @param	string $tempVarDef New Template Variable Definition Pattern.
         * @throws	Exception
         */
        public static function setTempVarDef( $tempVarDef = '' )
        {//---------------------------------------->> End setTempVarDef()
        	
            if( empty( $tempVarDef ) ) {//-------------------->> if invalid param
                self::_throwViewException( 'Plase set the Template Variable Definition Pattern ' . 
                'as a non empty string' );
            }//-------------------->> End if invalid param

            self::$_tempVarDef = (string) $tempVarDef;
        
        }//---------------------------------------->> End setTempVarDef()
        
        /**
         * 
         * Method to ensure that the file expressed in $tempDef exist and/or set
         * the base object's properties to be used in template's variable substitution
         * 
         * @param   string $tempDef Path where the file to use as templete is located or
         *          the string to use as template definition.
         * @param   boolean $fromFile Flag to define if the $tempDef represents a file path or
         *          a template definition. Boolean true value by default.
         * @return  View
         * @throws  Exception
         * @see     View::_setTemplateFile()
         * @see     View::_setTemplateString()
         * @see     View::_throwViewException()
         */
        public function setTemplate( $tempDef = '', $fromFile = true )
        {//---------------------------------------->> setTemplate()
            
            if( !empty( $tempDef ) && is_string( $tempDef ) ) {//-------------------->> if not empty param

                if( (bool) $fromFile == true ) {//-------------------->> if $tempDef $fromFile
                    $this->_setTemplateFile( $tempDef );
                } else {//-------------------->> else if $tempDef not $fromFile
                    $this->_setTemplateString( $tempDef );
                }//-------------------->> End if $tempDef $fromFile
                
            } else {//-------------------->> else if empty param
                self::_throwViewException( 'Please set the File Path or string to be used as template' );
            }//-------------------->> End if not empty param
            
            return $this;
            
        }//---------------------------------------->> End setTemplate()
        
        /**
         * 
         * Evaluates if the View::$_tempDef has at last one template variable 
         * definition on its content.
         * @static
         * @access	public
         * @param	string $tempDef Template Definition to be verified.
         * @return	boolean
         */
        public static function isDinamicDefinition( $tempDef = '' )
        {//---------------------------------------->> isDinamicDefinition()
            return ( preg_match( self::$_tempVarDef, (string) $tempDef ) ) ? true : false; 
        }//---------------------------------------->> End isDinamicDefinition()
        
        /**
         * 
         * Remplaces template variables definition from {var} to $var and sets
         * the resulting string as the template property value. 
         * @access	private
         * @param	string $tempDef Dinamic template Dedinition.
         * @return	void
         */
        private function _prepareTemplateDefinition( $tempDef = '' )
        {//---------------------------------------->> _prepareDefinition()
            $this->_template = preg_replace( self::$_tempVarDef, "' . $\\1 . '", str_replace ( "'", "\'", $tempDef ) );
        }//---------------------------------------->> End _prepareDefinition()
        
        /**
         * 
         * Evaluates if the File expressed in $filePath exist and has dynamic content
         * (placeholders) to set the template property value.
         * @access	private
         * @param	string $filePath Path where the file to be used as template is located.
         * @throws	Exception
         * @see		View::getFileContent()
         * @see         View::isDinamicDefinition()
         * @see         View::_throwViewException()
         * @see         View::_prepareTemplateDefinition()
         */
        private function _setTemplateFile( $filePath = '' )
        {//---------------------------------------->> _setTemplateFile()
        	
            $this->_viewFilePath = self::$_viewFilesRepository . $filePath;
                
            if( file_exists( $this->_viewFilePath ) ) {//-------------------->> if file exist
            
            	$tempDef = self::getFileContent( $this->_viewFilePath );
                
            	if( !self::isDinamicDefinition( $tempDef ) ) {//-------------------->> if is not a dynamic definition
            		
                    self::_throwViewException( 'Template Definition with static content.' .
                        'To use static content from a file please use the static method ' .
                        __CLASS__ . '::getFileContent() instead of using an instance.'  );
            	
            	}//-------------------->> End if is not a dynamic definition

                $this->_viewType = strtolower( substr( strrchr( $this->_viewFilePath, "." ), 1 ) );
                $this->_prepareTemplateDefinition( $tempDef );
                
            } else {//-------------------->> else if file doesn't exist
                self::_throwViewException( "The file {$filePath} doesn't exist" );
            }//-------------------->> End if file exist
        
        }//---------------------------------------->> End _setTemplateFile()
        
        /**
         * 
         * Evaluates if the $tempDef has dynamic content (placeholders) to set 
         * the template property value.
         * @access	private
         * @param	string $tempDef String with dynamic content definitions.
         * @see		View::isDinamicDefinition()
         * @see         View::_throwViewException()
         * @see         View::_prepareTemplateDefinition()
         */
        private function _setTemplateString( $tempDef = '' )
        {//---------------------------------------->> _setTemplateString()
        	
            if( !self::isDinamicDefinition( $tempDef ) ) {//-------------------->> if is not a dynamic definition

                self::_throwViewException( 'Template Definition with static content.' .
                    'To use static content please use a string var value.'  );

            }//-------------------->> End if is not a dynamic definition

            $this->_viewType = 'string';
            $this->_prepareTemplateDefinition( $tempDef );
                                                 	
        }//---------------------------------------->> End _setTemplateString()
        
        /**
         * 
         * Return the Original Template string.
         * @return	string	
         */
        public function getTemplate()
        {//---------------------------------------->> getTemplate()
            return (string) $this->_template;
        }//---------------------------------------->> End getTemplate()
        
        /**
         * 
         * Retrive the current view file path.
         * @return  string
         */
        function getViewFilePath() 
        {//---------------------------------------->> getViewFilePath()
            return $this->_viewFilePath;
        }//---------------------------------------->> End getViewFilePath()
        
        /**
         * 
         * Retrives the type of string used on this Views instance.
         * If the template comes from an string, the returned value will be a 'sting'' value.
         * @return  string
         */
        function getViewType() 
        {//---------------------------------------->> getViewType()
            return $this->_viewType;
        }//---------------------------------------->> eND getViewType()

            
        /**
         * 
         * Set the value of the variables to be remplaced on template's variables.
         * @param   array $vars Associative Array (pair->value) containing the
         *          value of the variables to be replaced on template's variables.
         * @return  View
         * @see     View::_throwViewException()
         */
        public function setVars( array $vars )
        {//---------------------------------------->> setVars()
            
            if( empty( $vars ) ) {//-------------------->> if empty param
                self::_throwViewException( 'Set the variables as a non empty associative array' );
            }//-------------------->> End if empty param
            
            $this->_vars = ( empty( $this->_vars ) ) ? $vars : array_merge( (array) $this->_vars, $vars );
            
            return $this;
            
        }//---------------------------------------->> End setVars()
        
        /**
         * 
         * Set the value of one variable to be remplaced on the template.
         * @param   string $name Name of the Variable
         * @param   mixed $value Value of the Variable
         */
        public function setVar( $name, $value )
        {//---------------------------------------->> setVar()
            
            settype( $value, 'string' );
            
            if ( empty( $name ) ) {//-------------------->> if empty var name
                self::_throwViewException( 'A View var name is required' );
            }//-------------------->> End if empty var name
             
            $this->_vars[ $name ] = $value;
            
        }//---------------------------------------->> End setVar()
        
        /**
         * 
         * Alias of the View::setVar() to allow its invocation from an instance
         * like public property setter action.
         * @param type $name
         * @param type $value
         */
        public function __set($name, $value) 
        {//---------------------------------------->> __set()
            $this->setVar( $name, $value );
        }//---------------------------------------->> End __set()
        
        /**
         * 
         * 
         * Make the var substitution according to the _vars property
         * content on _template property to get the new _view value.
         * @return  View
         * @see     View::_throwViewException()
         */
        public function buildView()
        {//---------------------------------------->> buildView()
            
            if( empty( $this->_template ) ) {//-------------------->> if empty _template
                self::_throwViewException( 'Please set the path from a non empty file ' . 
                    'before to render the resulting View' );
            }//-------------------->> End if empty _template
            
            $this->_view = $this->_template;
            
            reset( $this->_vars );
            foreach( (array) $this->_vars as $key => $val ) {//-------------------->> foreach _vars
                $$key = $val;
            }//-------------------->> End foreach _vars
            
            eval( "\$this->_view = '$this->_view';" );
            
            reset( $this->_vars );
            foreach( (array) $this->_vars as $key => $val ) {//-------------------->> foreach _vars
                unset( $$key );
            }//-------------------->> End foreach _vars

            $this->_view = str_replace( "\'", "'", $this->_view );
            
            return $this;
            
        }//---------------------------------------->> End buildView()
        
        /**
         * 
         * Return the _view property value.
         * If $render is a logic true, the value will be sent to the out buffer.
         * @param   boolean $render Flag to evaluate if the view property will be 
         *          send to the output buffer. Boolean false value by default.
         * @return  string
         */
        public function getView( $render = false ) 
        {//---------------------------------------->> getView()
            
            if ( (bool) $render === true ) {//-------------------->> if render
                echo $this->_view;
            }//-------------------->> End if render
            
            return $this->_view;
            
        }//---------------------------------------->> End getView()
        
        /**
         * 
         * Make the vars substitution on template's format and get the 
         * resulting string.
         * @param   boolean $display Flag to define if the resulting _view
         *          will be sent to output buffer. Boolean true is the default
         *          value.
         * @return  string
         * @see     View::buildView()
         * @see     View::getView()
         */
        public function render( $display = true )
        {//---------------------------------------->> render()
            return $this->buildView()->getView( $display );
        }//---------------------------------------->> End render()
        
        /**
         * 
         * Obtains the file content as a string.
         * An exception will be thrown if the file path is not an existing file or
         * if the file has no read permissions.
         * @static
         * @param   $filePath Path where the file to be used as template is located.
         * @return  string
         * @see     self::_throwViewException()
         */
        public static function getFileContent( $filePath = '' ) 
        {//---------------------------------------->> getFileContent()
        	
            $fileContent = '';
        	
            if( empty( $filePath ) || !is_string( $filePath ) ) {//-------------------->> if empty param
                self::_throwViewException( 'Set the path from the file to search as a non empty string' );
            }//-------------------->> End if empty param
            
            if( file_exists( $filePath ) && is_readable( $filePath ) ) {//-------------------->> if file exist
                $fileContent = file_get_contents( $filePath, true );
            } else {//-------------------->> else if file dosen't exist
                self::_throwViewException( "File {$filePath} not found or without read permissions" );
            }//-------------------->> End if file exist
        	
            return $fileContent;
            
        }//---------------------------------------->> End getFileContent()
        
        /**
         * 
         * Throws an Exception describing a View process error.
         * @static
         * @access  private
         * @param   string $message
         * @throws  View_Exception
         */ 
        private static function _throwViewException( $message = '' ) 
        {//---------------------------------------->> _throwViewException()
            throw new View_Exception( "View Exception: {$message}" );
        }//---------------------------------------->> End _throwViewException()
        
        /**
         * 
         * Executes View::render( false ) method and returns its result as
         * string. 
         * @return  string
         * @see     View::render()
         */
        public function __toString() 
        {//---------------------------------------->> __toString()
            return $this->render( false );
        }//---------------------------------------->> End __toString()
          
    }//------------------------------------------------------------------>> End Class View