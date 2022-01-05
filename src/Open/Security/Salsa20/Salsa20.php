<?php
namespace Youzan\Open\Security\Salsa20;

use Youzan\Open\Security\Salsa20\FieldElement;


/**
 * Salsa20, HSalsa20 and XSalsa20
 *
 * Assembled from:
 *  - http://tweetnacl.cr.yp.to/
 *
 *
 * @link   https://github.com/devi/Salt
 *
 */
class Salsa20 {

    /* Salsa20, HSalsa20, XSalsa20 */
    const salsa20_KEY    = 32;
    const salsa20_NONCE  =  8;
    const salsa20_INPUT  = 16;
    const salsa20_OUTPUT = 64;
    const salsa20_CONST  = 16;
    const hsalsa20_KEY    = 32;
    const hsalsa20_INPUT  = 16;
    const hsalsa20_OUTPUT = 32;
    const hsalsa20_CONST  = 16;
    const xsalsa20_KEY   = 32;
    const xsalsa20_NONCE = 24;

    /* Stream salsa20, salsa20_xor */
    const stream_salsa20_KEY   = 32;
    const stream_salsa20_NONCE = 24;

    public static $sigma = array(101,120,112,97,110,100,32,51,50,45,98,121,116,101,32,107);
    /* Lazy load */
    private static $instance;
    public static function instance() {
        if (!isset(static::$instance)) {
            static::$instance = new Salsa20();
        }
        return static::$instance;
    }
    function load($x, $offset = 0) {
        return
            $x[$offset] |
            ($x[1+$offset] << 8) |
            ($x[2+$offset] << 16) |
            ($x[3+$offset] << 24);
    }
    function store($x, $offset = 0, $u) {
        $x[$offset]   = $u & 0xff; $u >>= 8;
        $x[1+$offset] = $u & 0xff; $u >>= 8;
        $x[2+$offset] = $u & 0xff; $u >>= 8;
        $x[3+$offset] = $u & 0xff;
    }
    function rotate(&$x, $y, $z, $c) {
        $u = $y + $z;
        $u &= 0xffffffff;
        $x ^= ($u << $c) | ($u >> (32 - $c));
        $x &= 0xffffffff;
    }
    function core($out, $in, $k, $c, $salsa20 = true) {
        $x0  = $this->load($c,   0);
        $x1  = $this->load($k,   0);
        $x2  = $this->load($k,   4);
        $x3  = $this->load($k,   8);
        $x4  = $this->load($k,  12);
        $x5  = $this->load($c,   4);
        $x6  = $this->load($in,  0);
        $x7  = $this->load($in,  4);
        $x8  = $this->load($in,  8);
        $x9  = $this->load($in, 12);
        $x10 = $this->load($c,   8);
        $x11 = $this->load($k,  16);
        $x12 = $this->load($k,  20);
        $x13 = $this->load($k,  24);
        $x14 = $this->load($k,  28);
        $x15 = $this->load($c,  12);
        if ($salsa20) {
            $j0 = $x0; $j1 = $x1; $j2 = $x2; $j3 = $x3; $j4 = $x4;
            $j5 = $x5; $j6 = $x6; $j7 = $x7; $j8 = $x8; $j9 = $x9;
            $j10 = $x10; $j11 = $x11; $j12 = $x12; $j13 = $x13;
            $j14 = $x14; $j15 = $x15;
        }
        for ($i = 0; $i < 20; $i += 2) {
            $this->rotate($x4,  $x0,  $x12,  7);
            $this->rotate($x8,  $x4,  $x0,   9);
            $this->rotate($x12, $x8,  $x4,  13);
            $this->rotate($x0,  $x12, $x8,  18);
            $this->rotate($x9,  $x5,  $x1,   7);
            $this->rotate($x13, $x9,  $x5,   9);
            $this->rotate($x1,  $x13, $x9,  13);
            $this->rotate($x5,  $x1,  $x13, 18);
            $this->rotate($x14, $x10, $x6,   7);
            $this->rotate($x2,  $x14, $x10,  9);
            $this->rotate($x6,  $x2,  $x14, 13);
            $this->rotate($x10, $x6,  $x2,  18);
            $this->rotate($x3,  $x15, $x11,  7);
            $this->rotate($x7,  $x3,  $x15,  9);
            $this->rotate($x11, $x7,  $x3,  13);
            $this->rotate($x15, $x11, $x7,  18);
            $this->rotate($x1,  $x0,  $x3,   7);
            $this->rotate($x2,  $x1,  $x0,   9);
            $this->rotate($x3,  $x2,  $x1,  13);
            $this->rotate($x0,  $x3,  $x2,  18);
            $this->rotate($x6,  $x5,  $x4,   7);
            $this->rotate($x7,  $x6,  $x5,   9);
            $this->rotate($x4,  $x7,  $x6,  13);
            $this->rotate($x5,  $x4,  $x7,  18);
            $this->rotate($x11, $x10, $x9,   7);
            $this->rotate($x8,  $x11, $x10,  9);
            $this->rotate($x9,  $x8,  $x11, 13);
            $this->rotate($x10, $x9,  $x8,  18);
            $this->rotate($x12, $x15, $x14,  7);
            $this->rotate($x13, $x12, $x15,  9);
            $this->rotate($x14, $x13, $x12, 13);
            $this->rotate($x15, $x14, $x13, 18);
        }
        if ($salsa20) {
            $x0 += $j0; $x1 += $j1; $x2 += $j2; $x3 += $j3; $x4 += $j4;
            $x5 += $j5; $x6 += $j6; $x7 += $j7; $x8 += $j8; $x9 += $j9;
            $x10 += $j10; $x11 += $j11; $x12 += $j12; $x13 += $j13;
            $x14 += $j14; $x15 += $j15;
            $this->store($out,  0,  $x0);
            $this->store($out,  4,  $x1);
            $this->store($out,  8,  $x2);
            $this->store($out, 12,  $x3);
            $this->store($out, 16,  $x4);
            $this->store($out, 20,  $x5);
            $this->store($out, 24,  $x6);
            $this->store($out, 28,  $x7);
            $this->store($out, 32,  $x8);
            $this->store($out, 36,  $x9);
            $this->store($out, 40, $x10);
            $this->store($out, 44, $x11);
            $this->store($out, 48, $x12);
            $this->store($out, 52, $x13);
            $this->store($out, 56, $x14);
            $this->store($out, 60, $x15);
        } else {
            $this->store($out,  0,  $x0);
            $this->store($out,  4,  $x5);
            $this->store($out,  8, $x10);
            $this->store($out, 12, $x15);
            $this->store($out, 16,  $x6);
            $this->store($out, 20,  $x7);
            $this->store($out, 24,  $x8);
            $this->store($out, 28,  $x9);
        }
    }
    function stream($out, $m, $mlen ,$n, $k) {
        if (!$mlen) return false;
        $z = new \SplFixedArray(16);
        $x = new \SplFixedArray(64);
        for ($i = 0;$i < 8;++$i) $z[$i] = $n[$i];
        if ($m) {
            $l = count($m);
            if ($l < $mlen) {
                for ($i = $l;$i <  $mlen;++$i) $m[$i] = 0;
            }
            $mpos = 0;
        }
        $outpos = 0;
        while ($mlen >= 64) {
            $this->core($x, $z, $k, Salsa20::$sigma);
            for ($i = 0;$i < 64;++$i) {
                $out[$i+$outpos] = ($m?$m[$i+$mpos]:0) ^ $x[$i];
            }
            $u = 1;
            for ($i = 8;$i < 16;++$i) {
                $u += $z[$i];
                $z[$i] = $u;
                $u >>= 8;
            }
            $mlen -= 64;
            $outpos += 64;
            if ($m) $mpos += 64;
        }
        if ($mlen) {
            $this->core($x, $z, $k, Salsa20::$sigma);
            for ($i = 0;$i < $mlen;++$i) {
                $out[$i+$outpos] = ($m?$m[$i+$mpos]:0) ^ $x[$i];
            }
        }
    }


    public function crypto_core_salsa20($in, $key, $const) {
        $fieldElement = new FieldElement(32);
        $out = $fieldElement->getArray();
        $this->core($out, $in, $key, $const);
        return $out;
    }

    public function crypto_core_hsalsa20($in, $key, $const) {
        $fieldElement = new FieldElement(32);
        $out = $fieldElement->getArray();
        $this->core($out, $in, $key, $const, false);
        return $out;
    }

    public function crypto_stream_salsa20($length, $nonce, $key) {
        $fieldElement = new FieldElement($length);
        $out = $fieldElement->getArray();
        $this->stream($out, false, $length, $nonce, $key);
        return $out;
    }

    public function crypto_stream_salsa20_xor($in, $length, $nonce, $key) {
        $fieldElement = new FieldElement($length);
        $out = $fieldElement->getArray();
        $this->stream($out, $in, $length, $nonce, $key);
        return $fieldElement;
    }

    public function crypto_stream_xsalsa20($length, $nonce,$slice, $key) {
        $subkey = $this->crypto_core_hsalsa20($nonce, $key, Salsa20::$sigma);
        return $this->crypto_stream_salsa20($length, $slice, $subkey);
    }

    public function crypto_stream_xsalsa20_xor($in, $length, $nonce,$slice, $key) {
        $subkey = $this->crypto_core_hsalsa20($nonce, $key, Salsa20::$sigma);
        return $this->crypto_stream_salsa20_xor($in, $length, $slice, $subkey);
    }

    public function crypto_stream($length, $nonce,$slice, $key) {
        return $this->crypto_stream_xsalsa20($length, $nonce,$slice, $key);
    }

    public function crypto_stream_xor($in, $length, $nonce,$slice, $key) {
        return $this->crypto_stream_xsalsa20_xor($in, $length, $nonce,$slice, $key);
    }
    
}
