<?php
namespace Yamw\Modules;

use Yamw\Lib\Builders\Interfaces\BuilderInterface;
use Yamw\Lib\Builders\Interfaces\YamwMarkupInterface;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Modules
 */
class RootBuilder implements BuilderInterface
{
    private $content_markups = array();
    private $template = null;
    private $build_markup;

    /**
     *
     * @param string $name
     * @param string|YamwMarkupInterface $content
     *
     * @return $this
     */
    public function push($name, $content)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name is not a string!');
        }

        $this->content_markups[$name] = $content;

        return $this;
    }

    public function setTemplate($markup)
    {
        $this->template = $markup;
    }

    public function build()
    {
        $this->build_markup = null;

        if ($this->template) {
            // Do string replacements for markup
            $build_markup = $this->template;

            foreach ($this->content_markups as $name => $markup) {
                $tag = sprintf("{%s}", strtoupper($name));

                $build_markup = str_replace($tag, $markup, $build_markup);
            }
            $this->build_markup = $build_markup;
        } else {
            // Just echo the markup
            foreach ($this->content_markups as $markup) {
                $this->build_markup .= $markup;
            }
        }
    }

    protected function pushBuildMarkup($markup)
    {
        $this->build_markup = $markup;
    }

    public function isBuilt()
    {
        return isset($this->build_markup);
    }

    public function retrieve()
    {
        if (!$this->isBuilt()) {
            $this->build();
        }

        return $this->build_markup;
    }

    public function outputMarkup()
    {
        echo $this->retrieve();
    }
}
