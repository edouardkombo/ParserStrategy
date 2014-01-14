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
     * @var array $container
     */
    protected $container = array();

    /**
     * Constructor
     */
    public function __construct()
    {
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
    public function open($file)
    {
        $this->file = file($file);
        
        return $this;
    }
    
    /**
     * Escape comments
     * 
     * @return \TTI\ParserStrategy\IlnParser
     */
    public function escape()
    {
        foreach ($this->file as $line_num => $line) {
            if (substr($line, 0, 1) == '#') {
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
        foreach ($this->file as $line_num => $line) {
            if (empty($line)) {
                continue;    
            }
            
            if (substr($line, 0, 1) != ' ') {
                $driver = \explode(':', trim($line));
                $this->container[$driver[0]] = new stdClass();
                $this->container = $this->container[$driver[0]];
                $this->container->namespace = $driver[1];
                continue;
            }
            
            if (substr($line, 0, 4) == '    ' && substr($line, 4, 1) != '') {
                $method = \explode(':', trim($line));
                $this->container->method = array($method[0], 0);
                continue;
            }
            
            if (substr($line, 0, 8) == '        ' && substr($line, 8, 1) != '') {
                $arg = \explode(':', trim($line));
                $this->container->method[1][] = array($arg[0], $arg[1]);
                continue;
            }            
        }
        
        return $this;
    }     

    /**
     * Close something
     *
     * @return mixed final
     */
    public function close()
    {
        
    }   
}
