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


/**
 * Slug 
 * 
 * @copyright 2014 Michel PETIT
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Slug
{
    protected $str = null;
    protected $str_out = null;
    protected $arr_rules = array();
    protected static $arr_history = array();
    protected $use_history = true;



    /**
     * Create new slug instance. 
     * 
     * @throw \RuntimeException If Intl extension is not available.
     * @param mixed $str String to slugify, or no value to set it later.
     * @access public
     * @return void
     */
    public function __construct($str = null)
    {
        if(!extension_loaded('intl'))
        {
            throw new \RuntimeException('Missing Intl extension. This is required to use ' . __CLASS__);
        }

        if(!is_null($str))
        {
            $this->value($str);
        }
    }



    /**
     * Set history with values from an array. 
     * 
     * @param array $arr Array of slug strings
     * @static
     * @access public
     * @return void
     * @todo Shall I use static way or not?
     */
    public static function history(&$arr)
    {
        self::$arr_history = $arr;
    }



    /**
     * Disables use of history. 
     * 
     * @access public
     * @return Slug
     */
    public function noHistory()
    {
        $this->use_history = false;
        return $this;
    }



    public function rule($str_to_change, $str_new)
    {
        if(!is_scalar($str_to_change) || !is_scalar($str_new))
        {
            throw new \InvalidArgumentException('New rule to replace character must be two valid string or scalar value.');
        }

        if(preg_match('/[^a-zA-Z0-9]/', $str_new))
        {
            throw new \InvalidArgumentException('Replacement string contains not allowed characters!');
        }

        $this->arr_rules[(string) $str_to_change] = (string) $str_new;

        return $this;
    }



    /**
     * New value to slugify using current slug object configuration.
     * 
     * Note: Raises an `E_USER_WARNING` if mbstring extension is not available.
     *
     * @throw \InvalidArgumentException If argument if not a scalar.
     * @param scalar $str String to slugify. can be scalar that will be cast to string.
     * @access public
     * @return Slug
     */
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



    /**
     * Shorthand for value() method. 
     * 
     * @param string $str New string to slugify
     * @access public
     * @return Slug
     */
    public function v($str)
    {
        $this->value($str);

        return $this;
    }



    /**
     * Renders original string as a slug.
     *
     * Under the hood, uses or not history. First call translate the string, 
     * second call take value already translated before.
     * 
     * @access public
     * @return string
     */
    public function render()
    {
        /*
        $str = transliterator_transliterate(
            "Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();",
            $this->str
        );
         */

        if(is_null($this->str_out))
        {
            $str_prov = $this->str;

            if(count($this->arr_rules))
            {
                $str_prov = strtr($str_prov, $this->arr_rules);
            }
            
            $str_prov = transliterator_transliterate(
                "Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;  Lower();",
                $str_prov
            );
            $str = trim(preg_replace('/[^a-z0-9-]+/', '-', $str_prov), '-');

            if(!$this->use_history)
            {
                $this->str_out = $str;
                return $this->str_out;
            }

            if(!in_array($str, self::$arr_history))
            {
                self::$arr_history[] = $str;
                $this->str_out = $str;
            }
            else
            {
                $int_start = 2;
                $str_prov = $str.'-'.$int_start;

                while(in_array($str_prov, self::$arr_history))
                {
                    $str_prov = $str.'-'.$int_start;
                    $int_start++;
                }


                if(!in_array($str_prov, self::$arr_history))
                {
                    self::$arr_history[] = $str_prov;
                    $this->str_out = $str_prov;
                }
            }

            return $this->str_out;
        }

        return $this->str_out;
    }



    /**
     * Render the slug string into string context. 
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
