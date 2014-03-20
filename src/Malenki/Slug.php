<?php
/*
 * Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


namespace Malenki;


class Slug
{
    protected $str = null;
    protected $arr_historic = array();
    protected $use_historic = false;

    public function __construct($str = null)
    {
        if(!extension_loaded('iconv'))
        {
            throw new \RuntimeException('Missing Iconv extension. This is required to use ' . __CLASS__);
        }

        if(!is_null($str))
        {
            $this->value($str);
        }
    }


    public function value($str)
    {
        if(!is_scalar($str))
        {
            throw new \InvalidArgumentException('Argument for constructor of' .__CLASS__ . ' must be a scalar!');
        }

        if(!extension_loaded('mbstring'))
        {
            trigger_error('You have not multibyte extension, this may create weird result!', E_USER_WARNING);
            $this->str = strtolower((string) $str);
        }
        else
        {
            $this->str = mb_strtolower((string) $str, 'UTF-8');
        }


        return $this;
    }



    public function v($str)
    {
        $this->value($str);

        return $this;
    }



    public function render()
    {
        //$str = iconv('utf-8', 'us-ascii//TRANSLIT', $this->str);
        $str = transliterator_transliterate(
            "Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();",
            $this->str
        );

        $str = transliterator_transliterate(
            "Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;  Lower();",
            $this->str
        );
        $str = trim(preg_replace('/[^a-z0-9-]+/', '-', $str), '-');

        return $str;
    }



    public function __toString()
    {
        return $this->render();
    }
}
