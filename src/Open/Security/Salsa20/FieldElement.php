<?php
namespace Youzan\Open\Security\Salsa20;

use SplFixedArray;

    /**
 * FieldElement
 *
 *
 * SplFixedArray with more functions.
 *
 *
 * @author Devi Mandiri <devi.mandiri@gmail.com>
 * @link   https://github.com/devi/Salt
 *
 */
class FieldElement{

    private $splFixedArray;

    /**
     * FieldElement constructor.
     */
    public function __construct($size = 0)
    {
        $this->splFixedArray = new SplFixedArray($size);
    }

    public function getArray() {
        return $this->splFixedArray;
    }

    public function rewind() {
        $this->splFixedArray->rewind();
    }
    public function valid() {
        return $this->splFixedArray->valid();
    }
    public function current() {
        $this->splFixedArray->current();
    }
    public function next() {
        $this->splFixedArray->next();
    }
    public function getSize() {
        return $this->splFixedArray->getSize();
    }
    public function offsetGet($index) {
        return $this->splFixedArray->offsetGet($index);
    }



    public function toString(): string
    {
        $buf = "";
        foreach ($this->getArray() as $value) {
            $buf .= chr($value);
        }

//        $this->rewind();
//
//        while ($this->valid()) {
//            $buf .= chr($this->current());
//            $this->next();
//        }
//        $this->rewind();
        return $buf;
    }
    public function toHex() {
        $this->rewind();
        $hextable = "0123456789ABCDEF";
        $buf = "";
        while ($this->valid()) {
            $c = $this->current();
            $buf .= $hextable[$c>>4];
            $buf .= $hextable[$c&0x0f];
            $this->next();
        }
        $this->rewind();
        return $buf;
    }

    // compatible to java
    public function toHexLowcase(){
        $this->rewind();
        $hextable = "0123456789abcdef";
        $buf = "";
        while ($this->valid()) {
            $c = $this->current();
            $buf .= $hextable[$c>>4];
            $buf .= $hextable[$c&0x0f];
            $this->next();
        }
        $this->rewind();
        return $buf;
    }

    public function toBase64() {
        return base64_encode($this->toString());
    }
    public function toJson() {
        return json_encode($this->toString());
    }
    public function slice($offset, $length = null) {
        $length = $length ? $length : $this->getSize()-$offset;
        $sliceFieldElement = new FieldElement($length);
        $slice = $sliceFieldElement->getArray();
        for ($i = 0;$i < $length;++$i) {
            $slice[$i] = $this->offsetGet($i+$offset);
        }
        return $slice;
    }
    public function copy($src, $size, $offset = 0, $srcOffset = 0) {
        for ($i = 0;$i < $size;++$i) {
            $this->offsetSet($i+$offset, $src[$i+$srcOffset]);
        }
    }
    public static function fromArray($array, $save_indexes = true): FieldElement
    {
        $l = count($array);
        $fe = new FieldElement($l);
        $array = $save_indexes ? $array : array_values($array);
        foreach ($array as $k => $v) $fe->splFixedArray[$k] = $v;
        return $fe;
    }
    public static function fromString($str) {
        return static::fromArray(unpack("C*", $str), false);
    }
    public static function fromHex($hex) {
        $hex = preg_replace('/[^0-9a-f]/', '', $hex);
        return static::fromString(pack("H*", $hex));
    }
    public static function fromBase64($base64) {
        return FieldElement::fromString(base64_decode($base64, true));
    }
    public static function fromJson($json) {
        return FieldElement::fromArray(json_decode($json, true));
    }
}

