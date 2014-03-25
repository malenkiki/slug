# slug

Slug creator, with history and language transliteration.

## How to install it

You can clone this repository or use [composer](https://getcomposer.org/):

```json
{
    "require": {
        "malenki/slug": "dev-master",
    }
}
```

## Requirements

You must have [Intl PHP extension](http://www.php.net/intl) and [mbstring PHP extension](http://www.php.net/mbstring).

## How to use it

### Basic usage

Very simple:

```php
use \Malenki\Slug;

$s = new Slug('Some string!');

echo $s->render();
//or
echo $s; // toString available, give "some-string"
```

### Add custom rules

You can add your own rules to replace some chars:

```php
use \Malenki\Slug;

$s = new Slug('One example string, again!');

$s->rule('!', '-wouah');

echo $s; // "one-example-string-again-wouah"
```

You can define void slug object to use many string later, usefull if you define rules, to use them again and again:

```php
use \Malenki\Slug;

$s = new Slug();

$s->rule('!', '-wouah')->rule('?', '-huh');
$s->v('Genius!');
echo $s; // "genius-wouah"
$s->v('Genius?');
echo $s; // "genius-huh"
```

### History

By default, Slug use history into running script context, if a generated slug is already present, then add number with increment to it:

```php
use \Malenki\Slug;

$s = new Slug('one-string');
echo $s; // "one-string"
$s = new Slug('one-string');
echo $s; // "one-string-2"
$s = new Slug('one-string');
echo $s; // "one-string-3"
```

But you can disable this behaviour:

```php
use \Malenki\Slug;

$s = new Slug('one-string');
echo $s->noHistory(); // "one-string"
$s = new Slug('one-string');
echo $s->noHistory(); // "one-string"
$s = new Slug('one-string');
echo $s->noHistory(); // "one-string"

// or

$s = new Slug();
echo $s->noHistory()->v('one-string'); // "one-string"
echo $s->noHistory()->v('one-string'); // "one-string"
echo $s->noHistory()->v('one-string'); // "one-string"
```

You can use predefined history of slug too, usefull if you have a lot of them in DB for example:

```php
$s = new Slug();
Slug::history(array('one-string', 'another-one'));
echo $s->v('one-string'); // "one-string-2"
```

### Non-ASCII characters

Use other language than english is possible too:

```php
// some french
$s = new Slug('C’est rigolo d’écrire en français !');
echo $s; // "c-est-rigolo-d-ecrire-en-francais"

// some greek
$s = new Slug('Τα ελληνικά σου είναι καλύτερα απο τα Γαλλικά μου!');
echo $s; // "ta-ellenika-sou-einai-kalytera-apo-ta-gallika-mou"
```

So, enjoy!
