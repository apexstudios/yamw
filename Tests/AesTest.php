<?php
use Yamw\Lib\Aes\AesCtr;

use Yamw\Lib\Aes\Aes;

class AesTest extends PHPUnit_Framework_TestCase
{
    public $text = 'sometext';
    public $pw = 'somepw';
    public $bits = 256;
    public $encrypted_text;
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->encrypted_text = AesCtr::encrypt($this->text, $this->pw, $this->bits);
    }
    
    public function testDecrypt()
    {
        self::assertEquals($this->text, AesCtr::decrypt($this->encrypted_text, $this->pw, $this->bits));
    }
    
    public function isEncrypted()
    {
        self::assertNotEquals($this->text, $this->encrypted_text);
        
        self::assertNotEquals(
            $this->text,
            AesCtr::encrypt($this->text, $this->pw, $this->bits)
        );
    }
    
    public function testFailIfInvalidBitLength()
    {
        self::assertSame("", AesCtr::encrypt($this->text, $this->pw, $this->bits-1));
    }
}
