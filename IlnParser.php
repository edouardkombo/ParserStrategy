<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  IlnParser
 * @package   ParserStrategy
 * @author    Edouard Kombo <edouard.kombo@gmail.com>
 * @copyright 2013-2014 Edouard Kombo
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://www.breezeframework.com/thetrollinception.php
 * @since     1.0.0
 */
namespace TTI\ParserStrategy;

use TTI\AbstractFactory\PorterAbstraction;

/**
 * IlnParser responsibility is to parse Iln (Intuitive Language Notation) files.
 *
 * @category IlnParser
 * @package  ParserStrategy
 * @author   Edouard Kombo <edouard.kombo@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://www.breezeframework.com/thetrollinception.php
 */
class IlnParser extends PorterAbstraction
{
    /**
     *
     * @var string $file
     */
    protected $file;    

    /**
     *
     * @var stdClass $container
     */
    public $container;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->container = new \stdClass();
    }
    
    /**
     * Cloner
     * 
     * @return void
     */
    public function __clone()
    {
    }      
    
    /**
     * Open file
     *
     * @param string $file File to open
     * 
     * @return \TTI\ParserStrategy\IlnParser
     */
    public function open($file = null)
    {
        try {
            if (!file_exists($file)) {
                throw new \RuntimeException("$file doesn't exists!");
            }
                
            $this->file = file($file);
        } catch (\RuntimeException $ex) {
            echo $ex->getMessage();
            exit();
        }
        
        return $this;
    }
    
    /**
     * Escape comments and empty lines
     * 
     * @return \TTI\ParserStrategy\IlnParser
     */
    public function escape()
    {
        foreach ($this->file as $line_num => $line) {
            if ((substr($line, 0, 1) == '#') OR (strlen($line) == 1)) {
                unset($this->file[$line_num]);
            }
        }
        
        return $this;
    } 
    
    /**
     * Parse files
     * 
     * @return \TTI\ParserStrategy\IlnParser
     */
    public function parse()
    {
        $driver = '';
        $method = '';
        
        foreach ($this->file as $line) {
            $separator = \explode(':', trim($line));            
          
            if (substr($line, 0, 1) != ' ') {
                $driver = $separator[0];
                $this->container->driver[$driver] = new \stdClass();
                $this->container->driver[$driver]->namespace = trim($separator[1]);
                continue;
            }
            
            if (substr($line, 0, 4) == '    ' && substr($line, 4, 1) != ' ') {
                $method = trim($separator[0]);
                $this->container->driver[$driver]->method[$method] = array();
                continue;
            }

            if (substr($line, 0, 8) == '        ' && substr($line, 8, 1) != '') {
                $arg = trim($separator[0]);
                $val = trim($separator[1]);
                $this->container->driver[$driver]->method[$method][$arg] = $val;
                continue;
            }            
        }
        
        return $this;
    }
}
