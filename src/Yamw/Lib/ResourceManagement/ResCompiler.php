<?php
namespace Yamw\Lib\ResourceManagement;

use Yamw\Lib\Assertions\BasicAssertions;

/**
 * Aggregates, compiles and saves resources
 *
 * @author AnhNhan <anhnhan@outlook.com>
 */
class ResCompiler
{
    const BOOTSTRAP_LOCATION = "../vendor/twitter/bootstrap/twitter/bootstrap/";
    const JS_LOCATION = "js/";
    const CSS_LOCATION = "css/";

    private $type;

    /**
     * @param string $type
     * The type of the resource stack - either `css` or `js`
     */
    public function __construct($type)
    {
        $type = strtolower($type);
        BasicAssertions::assertIsEnum(strtolower($type), array('js', 'css'));

        $this->type = $type;
    }

    private $resources = array();

    /**
     * Pushes a resource onto the resource stack for the resource type
     *
     * For example, it when pushing a Js file with the name jquery.min.js,
     * it will add it to the stack for js files
     *
     * @param string $type
     * The resource type
     *
     * @param string $name
     * The name of the resource
     */
    public function pushResource($name)
    {
        BasicAssertions::assertIsString($name);
        if (!file_exists($this->path($name))) {
            throw new \RuntimeException("$name does not exist!");
        }

        $this->resources[] = $this->trim($name);

        return $this;
    }

    /**
     * Compiles and saves the resource to the cache, from where it can be
     * retrieved again
     *
     * @return string
     * The resource identifier
     */
    public function compile()
    {
        $content = "";

        foreach ($this->resources as $resource) {
            if ($this->type == 'css') {
                $content .= Builders\CssBuilder::buildFile(
                    $this->path($resource)
                );
            } elseif ($this->type == 'js') {
                $content .= Builders\JsBuilder::buildFromFile(
                    $this->path($resource)
                );
            } else {
                $content .= file_get_contents($this->path($resource));
            }
        }

        $hash = ResCache::save($content);

        $generator = new \Yamw\Lib\Parsers\ConstantMapParser;
        $generator->setEntry($this->type.'_hash', $hash)
            ->setEntry($this->type.'_last_update', (string)time())->save();

        return $hash;
    }

    private function path($name)
    {
        return path($name);
    }

    private function trim($string)
    {
        return trim($string, ' /\\.');
    }
}
