<?php
use Yamw\Lib\TemplateHelper;

TemplateHelper::errorCSS($module);
set_slot('title', 'Page not found');
?>
<p>Sorry, but the page requested hasn't been found on this server.
Try visiting our <a href="<?php echo getAbsPath() ?>">homepage</a></p>