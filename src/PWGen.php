<?php

/**
 * Port of the famous GNU/Linux Password Generator ("pwgen") to PHP.
 * This file may be distributed under the terms of the GNU Public License.
 * Copyright (C) 2001, 2002 by Theodore Ts'o <tytso@alum.mit.edu>
 * Copyright (C) 2009 by Superwayne <superwayne@superwayne.org>
 */

declare(strict_types=1);

namespace PWGen;

use PWGen\Generator\PWGenerator;
use PWGen\Generator\PWGenerators\PWPhonemesGenerator;
use PWGen\Generator\PWGenerators\PWRandomGenerator;

final class PWGen
{
    private PWOptions $options;
    private string $password;

    /**
     * @param int    $length      Length of the generated password. Default: 8
     * @param bool   $capitalize  Include at least one capital letter in the password.
     * @param bool   $numerals    Include at least one number in the password.
     * @param bool   $symbols     Include at least one special symbol in the password.
     * @param bool   $secure      Generate completely random passwords.
     * @param bool   $ambiguous   Don't include ambiguous characters in the password.
     * @param bool   $noVowels    Do not use any vowels to avoid accidental nasty words.
     * @param string $removeChars Remove characters from the set of characters to generate passwords.
     */
    public function __construct(
        int $length = 8,
        bool $capitalize = true,
        bool $numerals = true,
        bool $symbols = false,
        bool $secure = false,
        bool $ambiguous = false,
        bool $noVowels = false,
        string $removeChars = '',
    ) {
        if ($length <= 0) {
            $length = 8;
        }

        $this->options = new PWOptions(
            length: $length,
            isUppers: $capitalize,
            isDigits: $numerals,
            isSymbols: $symbols,
            isSecure: $secure,
            isAmbiguous: $ambiguous,
            isNoVowels: $noVowels,
            removeChars: $removeChars,
        );

        $this->generate();
    }

    public function generate(): void
    {
        if ($this->options->length <= 2) {
            $this->options->isUppers = false;
        }

        if ($this->options->length <= 1) {
            $this->options->isDigits = false;
        }

        $this->password = $this->creteGenerator()->generate($this->options);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    private function creteGenerator(): PWGenerator
    {
        if ($this->options->isSecure || $this->options->isNoVowels || $this->options->length < 5) {
            return new PWRandomGenerator();
        }

        return new PWPhonemesGenerator();
    }
}
