<?php
/*
Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use \Malenki\Slug;

class SlugTest extends PHPUnit_Framework_TestCase
{
    public function testStringThatMustNotChange()
    {
        $s = new Slug('azerty');
        $this->assertEquals('azerty', $s->render());
        $this->assertEquals('azerty', "$s");
    }


    public function testStringThatShouldBeChanged()
    {
        $s = new Slug('John\'s car');
        $this->assertEquals('john-s-car', "$s");

        $s = new Slug('C’est génial d’écrire en français !');
        $this->assertEquals('c-est-genial-d-ecrire-en-francais', "$s");
        
        $s = new Slug('Τα ελληνικά σου είναι καλύτερα απο τα Γαλλικά μου!');
        $this->assertEquals('ta-ellenika-sou-einai-kalytera-apo-ta-gallika-mou', $s->render());

    }


    public function testDefaultHistory()
    {
        $s = new Slug('Voici une chaîne');
        $this->assertEquals('voici-une-chaine', "$s");
        $s = new Slug('Voici une chaîne');
        $this->assertEquals('voici-une-chaine-2', "$s");
        $s = new Slug('Voici une chaîne');
        $this->assertEquals('voici-une-chaine-3', "$s");
        $s = new Slug('Voici une chaîne');
        $this->assertEquals('voici-une-chaine-4', "$s");
    }

    public function testDisableDefaultHistory()
    {
        $s = new Slug('Voici une chaîne');
        $s->noHistory();
        $this->assertEquals('voici-une-chaine', "$s");
        $s = new Slug('Voici une chaîne');
        $s->noHistory();
        $this->assertEquals('voici-une-chaine', "$s");
        $s = new Slug('Voici une chaîne');
        $s->noHistory();
        $this->assertEquals('voici-une-chaine', "$s");
        $s = new Slug('Voici une chaîne');
        $s->noHistory();
        $this->assertEquals('voici-une-chaine', "$s");
    }

    public function testCustomRules()
    {
        $s = new Slug();
        $s->noHistory()->rule('1', 'une')->v('Voici 1 chaîne');
        $this->assertEquals('voici-une-chaine', "$s");
    }
}
