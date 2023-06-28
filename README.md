# PWGen — generate pronounceable passwords with PHP

[![PHP ^8.0](https://img.shields.io/badge/PHP-^8.0-777bb3.svg?style=flat)](https://www.php.net/releases/8.0/)
[![Tests Status](https://img.shields.io/github/actions/workflow/status/luzrain/pwgen/tests.yaml?branch=master)](../../actions/workflows/tests.yaml)

PWGen is a library which aims to clone the GNU pwgen functionality.
The PWGen library generates passwords which are designed to be easily memorized by humans, while being as secure as possible.
Human-memorable passwords are never going to be as secure as completely random passwords.
In particular, passwords generated by pwgen without the `secure` option should not be used in places where the password  could be attacked via a brute-force attack.
On the other hand, completely randomly generated passwords have a tendency to be written down, and are subject to being compromised in that fashion.

## Installation
```
$ composer require luzrain/pwgen
```

## Usage
```php
<?php

use PWGen\PWGen;

/**
 * Available options in the PWGen constructor. All of them are optional.
 *
 * length:      Length of the generated password. Default: 8
 * capitalize:  Include at least one capital letter in the password.
 * numerals:    Include at least one number in the password.
 * symbols:     Include at least one special symbol in the password.
 * secure:      Generate completely random passwords.
 * ambiguous:   Don't include ambiguous characters in the password.
 * noVowels:    Do not use any vowels to avoid accidental nasty words.
 * removeChars: Remove characters from the set of characters to generate passwords.
 */
$pwgen = new PWGen(length: 12, symbols: true);
$password = (string) $pwgen;

/**
 * Regenerate password with given options
 */
$pwgen->generate();
$password = (string) $pwgen;
```

## License

PWGen may be distributed under the terms of the GPL License.  
Copyright © 2001, 2002 by Theodore Ts'o (C version)  
Copyright © 2009 by Superwayne (PHP port)  
