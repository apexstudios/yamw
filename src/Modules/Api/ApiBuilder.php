<?php
namespace Modules\Api;

use Yamw\Modules\RootBuilder;

class ApiBuilder extends RootBuilder
{
    private $result = array();
    private $format = 'json';

    public function pushResult(array $result)
    {
        $this->result = $result;
    }

    public function pushFormat($format)
    {
        $this->format = $format;
    }

    public function build()
    {
        $function = 'build'.ucwords($this->format);

        $this->$function();
    }

    protected function buildXml()
    {
        // TODO: Build as Xml
    }

    protected function buildJson()
    {
        $this->pushBuildMarkup(json_encode($this->result));
    }

    protected function buildCsv()
    {
        $upperLine = '';
        $lowerLine = '';

        foreach ($this->result as $key => $val) {
            $upperLine .= $key . ',';
            $lowerLine .= $val . ',';
        }

        $markup = $upperLine . "\n" . $lowerLine;
        $this->pushBuildMarkup($markup);
    }
}
