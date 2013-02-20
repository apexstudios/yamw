<?php
namespace Yamw\Lib;

class TemplateHelper
{
    protected static $stylesheets = array();
    protected static $js_files = array();
    protected static $slots = array();

    protected static $partialJs = array();
    protected static $partialCSS = array();

    protected static $data = array();

    protected static $layout_metas = array();

    public static function use_style($stylesheet, $relative = true)
    {
        if (!$stylesheet || !is_string($stylesheet)) {
            return false;
        }

        if ($relative) {
            $stylesheet = getAbsPath().$stylesheet;
        }

        if (!in_array($stylesheet, static::$stylesheets)) {
            static::$stylesheets[] = $stylesheet;
        }
    }

    public static function use_js($js, $relative = true)
    {
        if (!$js) {
            return false;
        }

        if ($relative) {
            $js = getAbsPath().$js;
        }

        if (!in_array($js, self::$js_files)) {
            self::$js_files[count(self::$js_files)] = $js;
        }
    }

    public static function include_styles($return = false)
    {
        if (!static::$stylesheets) {
            return false;
        }

        if ($return){
            ob_start();
        }

        foreach (static::$stylesheets as $key => $value) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"${value}\" />\n";
        }

        static::$stylesheets = array();

        if ($return) {
            return ob_get_clean();
        }
    }

    public static function include_js_files($return = false)
    {
        if (!static::$js_files) {
            return false;
        }

        if ($return) {
            ob_start();
        }

        foreach (static::$js_files as $key => $value) {
            echo "<script src=\"${value}\" type=\"text/javascript\"></script>\n";
        }

        static::$js_files = array();

        if ($return) {
            return ob_get_clean();
        }
    }

    public static function use_partialJs($script)
    {
        if (!in_array($script, static::$partialJs)) {
            static::$partialJs[] = $script;
        }
    }

    public static function use_partialCSS($style)
    {
        if (!in_array($style, static::$partialCSS)) {
            static::$partialCSS[] = $style;
        }
    }

    public static function includePartialJs($return = false)
    {
        if (!static::$partialJs) {
            return false;
        }

        if ($return) {
            ob_start();
        }

        println('<script type="text/javascript">', false);
        foreach (static::$partialJs as $value) {
            println($value, false);
        }
        println('</script>', false);

        static::$partialJs = array();

        if ($return) {
            return ob_get_clean();
        }
    }

    public static function includePartialCSS($return = false)
    {
        if (!static::$partialCSS) {
            return false;
        }

        if ($return) {
            ob_start();
        }

        println('<style type="text/css">', false);
        foreach (static::$partialCSS as $value) {
            echo $value;
        }
        println('</style>', false);

        static::$partialCSS = array();

        if ($return) {
            return ob_get_clean();
        }
    }

    /**
     * Sets a specified slot
     * Warning: Replaces the WHOLE slot
     * @param string $name
     * @param string $content
     * @return nothing
     */
    public static function set_slot($name, $content)
    {
        static::$slots[$name] = $content;
    }

    public static function include_slot($name, $default = "No such slot!", $return = false)
    {
        $slot = (isset(static::$slots[$name]) ? static::$slots[$name] : $default);
        if ($return) {
            return $slot;
        } else {
            echo $slot;
        }
    }

    public static function get_slot($name, $default = "No such slot!")
    {
        if (isset(static::$slots[$name])) {
            return static::$slots[$name];
        } else {
            return $default." $name";
        }
    }

    /**
     * Returns whether the named slot exists
     * @param string $name <p>
     * The name of the slot
     * </p>
     * @return boolean
     */
    public static function isset_slot($name)
    {
        return isset(static::$slots[$name]);
    }

    public static function errorCSS($error = 404)
    {
         static::use_partialCSS(
            ".body {
    /*height: 7em;*/
    font-size: 1.25em;
    font-weight: 1000;
    margin: 0;
    padding: 3em;
    padding-top: 1em;
    padding-bottom: 2em;
    color: #eee;
}

.ccontainer {
    background-image: url('{ROOT}img/errors/{$error}.png');
    background-position: center;
    background-align: center;
    background-repeat: repeat;
    text-shadow: #000 0 0 0.5em;
}
"
        );
    }
}
