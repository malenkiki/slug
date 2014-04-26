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

    public function testSomeLanguages()
    {
        $s = new Slug();
        $s->v('α β γ δ ε ζ η θ ι κ λ μ ν ξ ο π ρ ς σ τ υ φ χ ψ ω');
        $this->assertEquals('a-b-g-d-e-z-e-th-i-k-l-m-n-x-o-p-r-s-s-t-y-ph-ch-ps-o', "$s");
        $s->noHistory()->v('Α Β Γ Δ Ε Ζ Η Θ Ι Κ Λ Μ Ν Ξ Ο Π Ρ Σ Σ Τ Υ Φ Χ Ψ Ω');
        $this->assertEquals('a-b-g-d-e-z-e-th-i-k-l-m-n-x-o-p-r-s-s-t-y-ph-ch-ps-o', "$s");

        /*
        $s->noHistory()->v('⽇');
        var_dump($s->render());
        $s->noHistory()->v('а б в г д е ё ж з и й к л м н о п р с т у ф х ц ч ш щ ъ ы ь э ю я ѐ ђ ѓ ѕ і ї ј љ њ ћ ќ ѝ ў џ');
        var_dump($s->render());
         */
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

    public function testPredefinedHistory()
    {
        $arr = array('voici-une-autre-chaine', 'a-simple-string');
        Slug::history($arr);
        $s = new Slug('Voici une autre chaîne');
        $this->assertEquals('voici-une-autre-chaine-2', "$s");
        $s->v('A simple string!');
        $this->assertEquals('a-simple-string-2', "$s");
        $s->v('Yet another string!');
        $this->assertEquals('yet-another-string', "$s");
    }

    public function testCustomRules()
    {
        $s = new Slug();
        $s->noHistory()->rule('1', 'une')->v('Voici 1 chaîne');
        $this->assertEquals('voici-une-chaine', "$s");
        $s->noHistory()->rule('2', 'deux')->v('Voici 2 chaînes');
        $this->assertEquals('voici-deux-chaines', "$s");
        $s->noHistory()->rule('!', 'wouah')->v('Voici 2 chaînes !');
        $this->assertEquals('voici-deux-chaines-wouah', "$s");
    }
}
