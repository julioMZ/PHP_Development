<?php

/**
 * 
 * File Object.
 * @package DBFile
 * @author  Julio Mora <julio.mora.zamora@gmail.com>
 */
class DBFile_File 
{//------------------------->> Class File
    
    /**
     *
     * Path where the file is located.
     * @access  private
     * @var     string 
     */
    private $_path;
    
    /**
     *
     * Base name of the file.
     * @access  private
     * @var     string
     */
    private $_name;
    
    /**
     *
     * Size of the file in Bytes
     * @access  private
     * @var     int
     */
    private $_size;
    
    /**
     *
     * Mime-type of the file
     * @access  private
     * @var     string
     */
    private $_type;
    
    /**
     *
     * Content of the file in binary string format
     * @access  private
     * @var     string
     */
    private $_content;
    
    /**
     * 
     * BDFile_File object Constructor.
     * @param string $path Path where the file is located.
     * @param string $name Base name of the file.
     * @param int $size Size of the file in Bytes.
     * @param string $type Mime-type of the file.
     * @param string $content Content of the file in binary string format.
     */
    public function __construct( $path = '', $name = '', $size = 0, $type = '', $content = '') 
    {//-------------------->> __construct()
        
        $this->_path = $path;
        $this->_name = $name;
        $this->_size = (int) $size;
        $this->_type = $type;
        $this->_content = $content;
        
    }//-------------------->> End __construct()

    /**
     * 
     * Retrieves the Path where the file is located.
     * @return  string
     */
    public function getPath()
    {//-------------------->> getPath() 
        return $this->_path;
    }//-------------------->> End getPath()
            
    /**
     * 
     * Retrieves the Base name of the file.
     * @return  string
     */
    public function getName() 
    {//-------------------->> getName()
        return $this->_name;
    }//-------------------->> End getName()

    /**
     * 
     * Retrieves the Size of the file in Bytes.
     * @return  int
     */
    public function getSize() 
    {//-------------------->> getSize()
        return $this->_size;
    }//-------------------->> End getSize()

    /**
     * 
     * Retrieves Mime-type of the file.
     * @return  string
     */
    public function getType() 
    {//-------------------->> getType()
        return $this->_type;
    }//-------------------->> End getType()

    /**
     * 
     * Retrieves the Content of the file in binary string format.
     * @return  string
     */
    public function getContent() 
    {//-------------------->> getContent()
        return $this->_content;
    }//-------------------->> End getContent()
    
    /**
     * 
     * Establishes the Path where the file is located.
     * @param string $path Path where the file is located.
     */
    public function setPath( $path )
    {//-------------------->> setPath()
        $this->_path = $path;
    }//-------------------->> End setPath()
    
    /**
     * 
     * Establishes the Base name of the file.
     * @param string $name Base name of the file.
     */
    public function setName( $name ) 
    {//-------------------->> setName()
        $this->_name = $name;
    }//-------------------->> End setName()

    /**
     * 
     * Establishes the Size of the file in Bytes.
     * @param int $size Size of the file in Bytes.
     */
    public function setSize( $size ) 
    {//-------------------->> setSize()
        $this->_size = (int) $size;
    }//-------------------->> End setSize()

    /**
     * 
     * Establishes the Mime-type of the file.
     * @param string $type Mime-type of the file.
     */
    public function setType( $type ) 
    {//-------------------->> setType()
        $this->_type = $type;
    }//-------------------->> End setType()

    /**
     * 
     * Establishes the Content of the file in binary string format.
     * @param string $content Content of the file in binary string format.
     */
    public function setContent( $content ) 
    {//-------------------->> setContent()
        $this->_content = $content;
    }//-------------------->> End setContent()
    
}//------------------------->> End Class File