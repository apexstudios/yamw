<?php
namespace Yamw\Lib\Builders\Markup;

use \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface;

/**
 * An abstract class for specific Markup
 *
 * @author AnhNhan
 * @package Yamw\Lib\Builders
 * @since 5.0
 */
abstract class AbstractMarkup implements YamwMarkupInterface
{
    private $name;
    private $content;
    private $pretty = true;
    
    public function __construct($name, $content = null)
    {
        if (!is_string($name) || !$name) {
            throw new \InvalidArgumentException();
        }
        $this->name = $name;
        $this->setContent($content);
    }
    
    /**
     * The current name of this markup
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * The current content of this markup
     *
     * @return Ambigous <string, NULL, \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface>
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Replaces the current content with $content
     *
     * @param Ambigous <string, NULL, \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface> $content
     * @throws \InvalidArgumentException
     *
     * @return \Yamw\Lib\Builders\Markup\AbstractMarkup
     */
    public function setContent($content)
    {
        if (!is_scalar($content) && $content !== null && !($content instanceof YamwMarkupInterface)) {
            throw new \InvalidArgumentException();
        }
        
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Appends content to the existing content, possibly mergins them together
     *
     * @param Ambigous <string, NULL, \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface> $content
     * @throws \InvalidArgumentException
     *
     * @return \Yamw\Lib\Builders\Markup\AbstractMarkup
     */
    public function appendContent($content)
    {
        if (!is_scalar($content) && !($content instanceof YamwMarkupInterface)) {
            throw new \InvalidArgumentException();
        }
        
        if (!$this->content) {
            $this->content = $content;
            return $this;
        }
        
        if (is_object($this->content) || is_object($content)) {
            $this->content = $this->content.$content;
        } else {
            $this->content .= $content;
        }
        
        return $this;
    }
    
    /**
     * Wipes the current content
     *
     * @return $this
     */
    public function removeContent()
    {
        $this->content = null;
        
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface::isPretty()
     */
    public function isPretty()
    {
        return $this->pretty;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface::makePretty()
     */
    public function makePretty($lvl = 1)
    {
        if (is_object($this->content)) {
            $this->content->makePretty(++$lvl);
        }
        $this->pretty = $lvl;
        return $this;
    }
    
    /**
     * disables formatting
     * @see \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface::makeDirty()
     */
    public function makeDirty()
    {
        if (is_object($this->content)) {
            $this->content->makeDirty();
        }
        $this->pretty = false;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Yamw\Lib\Builders\Interfaces\YamwMarkupInterface::__toString()
     */
    abstract public function __toString();
}
