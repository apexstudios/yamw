<?php
namespace Yamw\Lib\Parsers;

use Yamw\Cli\Exceptions\ErrorException;
use Yamw\Cli\Lib\Color\Color;
use Yamw\Cli\Exceptions\FatalException;

class ConfigParser
{
    private $parser;
    private $prettyPrinter;
    private $configCode;
    private $configPath;
    private $stmts;
    private $entries;

    public function __construct($configpath = 'config/config.php')
    {
        $this->configPath = path($configpath);

        try {
            $this->parser = new \PHPParser_Parser(new \PHPParser_Lexer);
            $this->prettyPrinter = new \PHPParser_PrettyPrinter_Zend;

            $this->configCode = file_get_contents($this->configPath);

            $this->stmts = $this->parser->parse($this->configCode);
            $this->entries =& $this->stmts[0]->expr->items;
        } catch (\PHPParser_Error $e) {
            throw new FatalException("Error parsing config file. " . $e->getMessage());
        }
    }

    private function entryValue($value)
    {
        if (!is_scalar($value)) {
            throw new FatalException("Invalid value given");
        }

        return is_numeric($value) ?
            (                                        // Number START
                is_float($value) ?
                new \PHPParser_Node_Scalar_DNumber($value) : // Float
                new \PHPParser_Node_Scalar_LNumber($value)   // Int
            ) :                                      // Number END
            new \PHPParser_Node_Scalar_String($value); // String
    }

    public function entryExists($key)
    {
        foreach ($this->entries as $i => &$item) {
            // Change single value
            if ($item->key->value == $key) {
                return true;
            }
        }

        // It will only exit the loop when no key was found
        return false;
    }

    public function addEntry($key, $value)
    {
        if ($this->entryExists($key)) {
            throw new ErrorException("Entry ".Color::blue($key).
                " already exists. Was not added");
        }

        foreach ($this->entries as $ii => $entry) {
            if (!isset($this->entries[$ii + 1])) { // Append the entry
                $this->entries[] = new \PHPParser_Node_Expr_ArrayItem(
                    $this->entryValue($value),
                    new \PHPParser_Node_Scalar_String($key)
                );
                break;
            }

            if (
                !isset($this->entries[$ii - 1]) &&
                isset($this->entries[$ii + 1]) &&
                $key < $entry->key->value
            ) { // Prepend the entry
                array_unshift(
                    $this->entries,
                    new \PHPParser_Node_Expr_ArrayItem(
                        $this->entryValue($value),
                        new \PHPParser_Node_Scalar_String($key)
                    )
                );
                break;
            }

            if (
                isset($this->entries[$ii - 1]) &&
                isset($this->entries[$ii + 1]) &&
                $key < $entry->key->value &&
                $key < $this->entries[$ii + 1]->key->value
            ) { // Insert an entry
                $pre = array_slice($this->entries, 0, $ii);
                $aft = array_slice($this->entries, $ii);

                $this->entries = $pre;

                $this->entries[] = new \PHPParser_Node_Expr_ArrayItem(
                    $this->entryValue($value),
                    new \PHPParser_Node_Scalar_String($key)
                );

                $this->entries = array_merge($this->entries, $aft);


                break;
            }
        }

        return $this;
    }

    public function setEntry($key, $value)
    {
        if (!$this->entryExists($key)) {
            throw new ErrorException("Entry ".Color::blue($key).
                " does not exist. Was not changed");
        }

        foreach ($this->entries as $i => &$item) {
            // Change single value
            if ($item->key->value == $key) {
                $item->value = $this->entryValue($value);
                return $this;
            }
        }
        return $this;
    }

    public function removeEntry($key)
    {
        if (!$this->entryExists($key)) {
            throw new ErrorException("Entry ".Color::blue($key).
                " does not exist. Was not removed");
        }

        foreach ($this->entries as $i => &$item) {
            // Remove single value
            if ($item->key->value == $key) {
                unset($this->entries[$i]);
                return $this;
            }
        }
        return $this;
    }

    private function resetComments()
    {
        // Reset comments
        $this->stmts[0]->setAttribute('comments', array());
    }

    public function getGeneratedConfig()
    {
        $this->resetComments();

        return $this->prettyPrinter->prettyPrint($this->stmts);
    }

    public function saveGeneratedConfig()
    {
        $content = "<?php\n";
        $content .= "// This file has been automatically generated by ".VERSION."\n";
        $content .= "// Would be cool if you won't edit it, as that would sure break things\n\n";
        $content .= $this->getGeneratedConfig();

        if (!file_put_contents($this->configPath, $content)) {
            throw new FatalException("Could not write config");
        }
    }
}
