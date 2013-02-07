<?php
namespace Yamw\Forms;

use \Yamw\Lib\Interfaces\FormInterface;

abstract class IForm implements FormInterface
{
    /**
     * The Array holding all the necessary data for generating forms (What inputs to use, their labels and so on)
     * @var Array
     */
    private $data = array();
    private $id;
    private $class;
    
    /**
     * The ActionProcesser Instance
     * @var Object
     */
    private $ap;
    private $use_submit = true;
    
    private $ajax = false;
    private $url = '';
    
    private $dropdown = array();
    
    protected function __construct($form_id, $form_class = '')
    {
        $this->id = $form_id;
        $this->class = ($form_class) ? $form_class.' Form' : 'Form';
        
        global $Processer;
        $this->ap = $Processer;
    }
    
    final public function addInput($id, $type, $extras = array())
    {
        $this->data[$id] = array_merge(array('id' => $id, 'type' => $type, 'value' => ''), $extras);
    }
    
    final public function addOption($dropdown, $id, $label, $value)
    {
        $this->dropdown[$dropdown][] = array('id' => $id, 'label' => $label, 'value' => $value);
    }
    
    final public function setInputExtra($id, $extra)
    {
        $this->data[$id] = array_merge($this->data[$id], $extra);
    }
    
    /**
     * In case you don't want the submit button, call this method
     * @return void
     */
    final public function disableSubmitButton()
    {
        $this->use_submit = false;
    }
    
    /**
     * In case you want the submit button for some reason, call this method
     * @return void
     */
    final public function enableSubmitButton()
    {
        $this->use_submit = true;
    }
    
    /**
     * Enables the use of jQuery Ajax to send the data of the form to $url,
     * optionally also specifiying an Id will add the Id to the end
     *
     * @param string $url
     * @param int $id [optional]
     *
     * @return void
     */
    final public function enableAjax($url = '', $id = 0)
    {
        $this->ajax = true;
        $this->setURL($url, $id);
    }
    
    final public function setURL($url, $id = 0)
    {
        $this->url = $url;
        if ($id) {
            $this->url .= '/'.$id;
        }
    }
    
    /**
     * Alias for getForm()
     * @return void
     */
    final public function retrieveForm()
    {
        $this->getForm();
    }
    
    /**
     * Generates the form for you
     */
    final public function getForm()
    {
        ob_start();
        // AJAX response div
        if ($this->ajax) {
            println('<div id="'.$this->id.'_res"></div>', false);
        }
        
        println('<form id="'.$this->id.'" '.(($this->ajax) ? 'onsubmit="return false;"' : '').
            ' class="'.$this->class.'" method="POST"  action="'.getAbsPath().$this->url.'">', false);
        
        foreach ($this->data as $value) {
            $label = (@$value['label']) ? $value['label'] : '';
            $init_val = (@$value['value']) ? $value['value'] : '';
            $id = (@$value['id']) ? $value['id'] : '';
            $name = (@$value['name']) ? $value['name'] : $id;
            $attr = (@$value['extra']) ? $value['extra'] : array();
            
            switch (mb_strtolower($value['type'])) {
                // Rich Text Editor
                case 'tinymce':
                case 'ckeditor':
                case 'rtf':
                    // Temporary measure due to buggy javascript
                    if (isset($value['heigth'])) {
                        $height = $value['heigth'];
                    } else {
                        $height = RTF_STD_HEIGHT;
                    }
                    /*$this->ap->createRTF($id, $label, $name, '', $init_val, $height);*/
                    echo "$label:<br /><textarea id=\"$id\" name=\"$name\" rows=\"20\">$init_val</textarea>";
                    break;
                // Normal text box
                case 'text':
                    $this->ap->partialInput($label, 'text', $name, $init_val, $attr, $id);
                    break;
                case 'dropdown':
                    // TODO: Do form dropdown options code here
                    $this->ap->partialLabel($label);
                    echo '<select id="'.$id.'" name="'.$id.'"'.(@$value['size'] ? ' size="'.$value['size'].'"' : '').'>';
                    foreach ($this->dropdown[$id] as $option) {
                        echo '<option id="'.$option['id'].'"';
                        if($option['value'])
                            echo ' value="'.$option['value'].'"';
                        if($value['selected'] == $option['value'])
                            echo ' selected="selected"';
                        echo '>'.$option['label'].'</option>';
                    }
                    echo '</select>';
                    break;
                case 'notice':
                case 'label':
                    println($label);
                    break;
                // Line Break (HTML)
                case 'br':
                    println();
                    break;
                // Line Break (\n)
                case 'nl':
                    println('', false);
                    break;
                // A hidden input
                case 'hidden':
                    println('<input type="hidden" value="'.$init_val.'" id="'.$id.'" name="'.$name.'" />', false);
                    break;
            }
        }
        // Print out the rest of the form
        if ($this->use_submit) {
            println('<input type="submit" value="Submit" />', false);
        }
        println('</form>', false);
        
        $ret = ob_get_clean();
        
        // This part is responsible for the Ajax stuff
        if ($this->ajax) {
            // Ajax Support
            ob_start();
            println('<script type="text/javascript">
$(\'#'.$this->id.'\').submit(function() {
    $(\'#'.$this->id.'_res\').html(\'<font color="#'.COLOR_INFO.'">Submitting...</font>\');
    $.ajax({
        data: {', false);
            
            $isfirst = true;
            
            foreach ($this->data as $value) {
                $label = (@$value['label']) ? $value['label'] : '';
                $init_val = (@$value['value']) ? escape($value['value'], true) : '';
                $id = (@$value['id']) ? $value['id'] : '';
                $name = (@$value['name']) ? $value['name'] : $id;
                $attr = (@$value['extra']) ? $value['extra'] : array();
                
                switch (mb_strtolower($value['type'])) {
                    case 'tinymce':
                    case 'ckeditor':
                    case 'rtf':
                        // Temporar measure due to buggy javascript
                    /*    echo $name.': $(\'#'.$id.'\').html()';
                        println(',', false);
                        break;*/
                    case 'hidden':
                    case 'text':
                    case 'dropdown':
                        echo $name.': $(\'#'.$id.'\').val()';
                        println(',', false);
                        break;
                }
            }
            echo '::';
            $rep = str_replace(",\n::", '', ob_get_clean());
            ob_start();
            echo $rep;
            
            
            println('},', false);
            if ($this->url) {
                println("        url: '{$this->url}/nt',", false);
            }
            println('        type: \'POST\',
        success: function(a) {
            $(\'#'.$this->id.'_res\').html(a);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $(\'#res\').html(jqXHR+textStatus+errorThrown).slideDown();
        }
    });
    
    return false;
});
</script>', false);
            $ret .= ob_get_clean();
        }
        echo $ret;
    }
}