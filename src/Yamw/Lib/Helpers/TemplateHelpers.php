<?php
use Yamw\Lib\Core;
use Yamw\Lib\Mongo\Stats;
use Yamw\Lib\TemplateHelper;
use Yamw\Lib\Templater\Templater;

function noTemplate()
{
    return Templater::noTemplate();
}

function addNotice($text, $type = NULL, $stay = NULL)
{
    static $yamw_notices;
    if (!isset($yamw_notices)) {
        $yamw_notices = array();
    }

    $yamw_notices[] = array(
        'text' => $text,
        'type' => $type,
        'stay' => $stay
    );
}

function getNotices($return = false)
{
    global $yamw_notices;
    if (!$yamw_notices) {
        return false;
    }
    if ($return) {
        ob_start();
    }
    echo '<script>';
    foreach ($yamw_notices as $notice) {
?>
$.noticeAdd({
        text: <?php echo '"'.addslashes($notice['text']).'"';
    if ($notice['type']) {
        echo ",type: '".$notice['type']."'";
    }
    if ($notice['stay']) echo ",stay: '".$notice['stay']."'";
    ?>
    });
<?php
    }
    echo '</script>';
    if($return) {
        return ob_get_clean();
    }
}

function dontSaveStats()
{
    Stats::dontSaveStats();
}

function saveStats()
{
    Stats::saveStats();
}

function statGroup($_statgroup)
{
    return Stats::statGroup($_statgroup);
}

/**
 * Returns the absolute URL to the current website location (where the index.php is located)
 * @return string The absolute URL to this website
 */
function getAbsPath()
{
    static $abspath;

    if (!isset($abspath)) {
        $abspath = Core::getInstance()->getPage();
    }

    return $abspath;
}

function getRandomImage($img_folder)
{
    $imglist = array();
    mt_srand(microtime(true)*1000);

    $imgs = dir(path($img_folder));

    while ($file = $imgs->read()) {
        if (preg_match("/jpg$/", $file) || preg_match("/png$/", $file)) {
            $imglist[] = $file;
        }
    }
    closedir($imgs->handle);

    if (!count($imglist)) {
        return '';
    }

    $random = mt_rand(0, count($imglist)-1);
    $image = $imglist[$random];

    return $image;
}

function addMeta($type, $value, $http = false)
{
    global $layout_metas;

    if (!$layout_metas) {
        $layout_metas = array();
    }

    if ($http) {
        $http = 'http-equiv';
    } else {
        $http = 'name';
    }

    $layout_metas[] = array('type' => $type, 'value' => $value, 'http' => $http);
}

function include_metas($return = false)
{
    if($return) {
        ob_start();
    }

    global $layout_metas;
    if(!isset($layout_metas) || !is_array($layout_metas) || !$layout_metas) {
        return false;
    }
    foreach ($layout_metas as $key => $value) {
        echo "        <meta {$value['http']}=\"{$value['type']}\" content=\"{$value['value']}\" />\n";
    }

    $layout_stylesheets = array();

    if($return) {
        return ob_get_clean();
    }
}

function use_style($stylesheet, $relative = true)
{
    return TemplateHelper::use_style($stylesheet, $relative);
}

function include_styles($return = false)
{
    return TemplateHelper::include_styles($return);
}

function includePartialCSS($return = false)
{
    if ($return) {
        return TemplateHelper::includePartialCSS($return);
    } else {
        TemplateHelper::includePartialCSS($return);
    }
}

function set_slot($name, $content)
{
    TemplateHelper::set_slot($name, $content);
}

function include_slot($name, $def_val = 'Slot empty!', $return = true)
{
    if ($return) {
        return TemplateHelper::include_slot($name, $def_val, $return);
    } else {
        TemplateHelper::include_slot($name, $def_val);
    }
}

function include_js_files($return = false)
{
    if ($return) {
        return TemplateHelper::include_js_files($return);
    } else {
        TemplateHelper::include_js_files($return);
    }
}

function includeContent()
{
    TemplateHelper::includeContent();
}

function use_partialJs($js_string)
{
    TemplateHelper::use_partialJs($js_string);
}

function includePartialJs($return = false)
{
    if ($return) {
        return TemplateHelper::includePartialJs($return);
    } else {
        TemplateHelper::includePartialJs($return);
    }
}

function img_for($src, $alt = "", $relative = true, $extras = array(), $return = false)
{
    if (!$src) {
        return false;
    }

    if ($relative) {
        $src = getAbsPath().$src;
    }

    $ret = "<img src=\"{$src}\"";
    foreach ($extras as $key => $value) {
        $ret .= " {$key}=\"".escape($value, true)."\"";
    }
    $ret .= " alt=\"{$alt}\" title=\"{$alt}\" />";

    if ($return) {
        return $ret;
    } else {
        echo $ret;
    }
}

function link_to($target, $text, $relative = true, $extras = array())
{
    if (!$target || !$text) {
        return false;
    }

    if ($relative) {
        $target = getAbsPath().$target;
    }

    echo "<a href=\"{$target}\"";

    foreach ($extras as $key => $value) {
        echo " {$key}=\"{$value}\"";
    }

    echo ">{$text}</a>";
}

/**
 * Prints a line colored with HTML
 * @param $text The text for output
 * @param $color The color for output (without hash [123456])
 * @param $html Whether to use HTML line breaks or not
 * @return void
 */
function print_c($text, $color = COLOR_INFO, $html = true)
{
    println('<font color="#'.$color.'">'.$text.'</font>', $html);
}
