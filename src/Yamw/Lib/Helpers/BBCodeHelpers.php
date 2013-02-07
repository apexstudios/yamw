<?php
/**
 * Converts BBCode to HTML
 * @param $text The BBCode you want to convert to HTML
 * @return string The string converted to HTML
 */
function BBCode2HTML($text)
{
    if (!$text || !is_string($text)) {
        return false;
    }
    
    $text = trim($text);
    
    // Extend this array with Regex-Expressions for BBCode
    // We use Regex so only tags being closed will be parsed...
    $BBCode = array(
        // HTML2BBCode
        '/(\n){0,}<h(2|3)>(.*?)<\/h(2|3)>(\n){0,}/si' => '[header]$3',
        '/(\n){0,}<br \/>(\n){0,}/si' => "\n",
        '/(\n){0,}<br\/>(\n){0,}/si' => "\n",
        '/(\n){0,}<br>(\n){0,}/si' => "\n",
        '/(\n){0,}<br style="clear:both" \/>(\n){0,}/si' => "<br style=\"clear:both\" />",
        // Fat text
        '/\[b\](.*?)\[\/b\]/si' => '<b>$1</b>',
        // Italic text
        '/\[i\](.*?)\[\/i\]/si' => '<i>$1</i>',
        // Underlined text
        '/\[u\](.*?)\[\/u\]/si' => '<u>$1</u>',
        // Complete URL
        '/\[url=(.*?)\](.*?)\[\/url\]/si' => '<a href="$1">$2</a>',
        // Simple URL
        '/\[url\](.*?)\[\/url\]/si' => '<a href="$1">$1</a>',
        // Image with alt
        '/\[img=(.*?)\](.*?)\[\/img\]/si' => '<img src="$1" alt="$2" />',
        // Simple Image
        '/\[img\](.*?)\[\/img\]/si' => '<img src="$1" alt="User Posted Image" />',
        // Colored text
        '/\[color=(.*?)\](.*?)\[\/color\]/si' => '<font color="$1">$2</font>',
        // Centered text
        '/\[center\](.*?)\[\/center\]/si' => '<div style="text-align: center">$1</div>',
        // Blockquote
        '/\[quote\](.*?)\[\/quote\]/si' => '<hr /><blockquote>$1</blockquote><hr />',
        // Aligned text
        '/\[align=(.*?)\](.*?)\[\/align\]/si' => '<div style="text-align: $1">$2</div>',
        // Floating text
        '/\[float=(.*?)\](.*?)\[\/float\]/si' => '<div style="float: $1">$2</div>',
        // Font using text
        '/\[font=(.*?)\](.*?)\[\/font\]/si' => '<span style="font-family: $1;">$2</span>',
        // Sized text
        '/\[size=(.*?)\](.*?)\[\/size\]/si' => '<span style="font-size: $1;">$2</span>',
        // Header text
        '/(\n){0,}\[header\](.*?)\[\/header\](\n){0,}/si' => '<br /><h2>$2</h2>',
        '/(\n){0,}\[header\](.*?)\n(\n){0,}/i' => '<br /><h2>$2</h2>',
        // HTML headers
        '/\[h(1|2|3|4|5|6)\](.*?)\[\/h(1|2|3|4|5|6)\](\n){0,}/si' => '<h$1>$2</h$3>',
        
        // Linebreak section
        // This section fixes some linebreak problems
        '/(\n){0,}<\/li>(\n){0,}/i' => '</li>',
        '/(\n){0,}<li>(\n){0,}/i' => '<li>',
        '/(\n){0,}<\/ul>(\n){0,}/i' => '</ul><br /><br /><br />',
        '/(\n){0,}<ul>(\n){0,}/i' => '<br /><ul>',
        '/(\n){0,}<hr(.*?)>(\n){0,}/i' => '<br /><br /><hr /><br />',
        
        '/([a-zA-Z0-9\:<>\/\[\]])(\n){0,}<object(.*?)>/si' => '$1<br /><object$3>',
    );
    
    // Regular BBCode
    foreach ($BBCode as $key => &$value) {
        $text = preg_replace($key, $value, $text);
    }
    
    $text = str_ireplace('[clear]', '<br style="clear: both" />', $text);
    $text = nl2br($text, true);
    
    return $text;
}
