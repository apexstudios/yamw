<?php
namespace Yamw\Lib\Parsers;

/**
 * Due to its nature, this class can only generate modules. Modifying existing
 * ones fucks up all
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Parsers
 */
class ModuleParser
{
    /**
     * Whether we're creating a Cli Module or not
     * @var bool
     */
    private $cli;
    private $path;
    private $name;

    private $parser;
    private $prettyPrinter;
    private $factory;
    private $template;

    private $methods = array();
    private $obj;

    public function __construct($name, $path, $cli = false)
    {
        $this->name = ucwords($name).'Controller';
        $this->cli = $cli;
        $this->path = $path;


        $this->parser = new \PHPParser_Parser(new \PHPParser_Lexer);
        $this->prettyPrinter = new \PHPParser_PrettyPrinter_Zend;
        $this->factory = new \PHPParser_BuilderFactory;

        $moduleName = $this->cli ? 'CliModule' : 'Module';
        $this->template = new \PHPParser_Template(
            $this->parser,
            file_get_contents(
                path("Externals/PHPParser/templates/{$moduleName}.template.php")
            )
        );

        $this->obj = $this->factory
            ->class($this->name)
            ->extend('RootController');
    }

    public function addMethod($name)
    {
        if (!is_scalar($name)) {
            throw new \InvalidArgumentException("The method name is an invalid type");
        }

        if (!preg_match('/[a-z_]{1}[a-z0-9_]+/i', $name)) {
            throw new \InvalidArgumentException("Illegal name format: $name");
        }

        $stmts = $this->template->getStmts(array('name' => $name));

        $this->obj->addStmts(
            array($stmts[0]->stmts[0])
        );

        $this->methods[] = $name;
    }

    /**
     * Gets the generated method as a string representation,
     * without <?php, namespaces and use statements
     *
     * @return string
     */
    public function getGeneratedModule()
    {
        if ($this->cli) {
            $stmts = $this->template->getStmts(array('name' => 'help'));
            $this->obj->addStmts(
                array(
                    $stmts[0]->stmts[1]
                )
            );
            $this->obj->implement('Help');
        }

        return $this->prettyPrinter->prettyPrint(array($this->obj->getNode()));
    }

    public function saveGeneratedModule()
    {
        $code = $this->getGeneratedModule();

        $moduleName = $this->cli ? 'CliModule' : 'Module';
        $prepend = file_get_contents(__DIR__."/Templates/{$moduleName}.template.php");
        $prepend = str_replace("__name__", $this->name, $prepend);
        $prepend = str_replace("__version__", VERSION, $prepend);

        if (!file_put_contents($this->path."/".$this->name.'.php', $prepend.$code)) {
            throw new \RuntimeException("Could not write module file to disk");
        }
    }
}
