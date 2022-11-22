<?php

/**
 * Port of the famous GNU/Linux Password Generator ("pwgen") to PHP.
 * This file may be distributed under the terms of the GNU Public License.
 * Copyright (C) 2001, 2002 by Theodore Ts'o <tytso@alum.mit.edu>
 * Copyright (C) 2009 by Superwayne <superwayne@superwayne.org>
 */

declare(strict_types=1);

namespace PWGen\Generator;

final class PWElement
{
    public const CONSONANT = 0x0001;
    public const VOWEL = 0x0002;
    public const DIPHTHONG = 0x0004;
    public const NOT_FIRST = 0x0008;

    private const ELEMENTS = [
        ['a', self::VOWEL],
        ['ae', self::VOWEL | self::DIPHTHONG],
        ['ah', self::VOWEL | self::DIPHTHONG],
        ['ai', self::VOWEL | self::DIPHTHONG],
        ['b', self::CONSONANT],
        ['c', self::CONSONANT],
        ['ch', self::CONSONANT | self::DIPHTHONG],
        ['d', self::CONSONANT],
        ['e', self::VOWEL],
        ['ee', self::VOWEL | self::DIPHTHONG],
        ['ei', self::VOWEL | self::DIPHTHONG],
        ['f', self::CONSONANT],
        ['g', self::CONSONANT],
        ['gh', self::CONSONANT | self::DIPHTHONG | self::NOT_FIRST],
        ['h', self::CONSONANT],
        ['i', self::VOWEL],
        ['ie', self::VOWEL | self::DIPHTHONG],
        ['j', self::CONSONANT],
        ['k', self::CONSONANT],
        ['l', self::CONSONANT],
        ['m', self::CONSONANT],
        ['n', self::CONSONANT],
        ['ng', self::CONSONANT | self::DIPHTHONG | self::NOT_FIRST],
        ['o', self::VOWEL],
        ['oh', self::VOWEL | self::DIPHTHONG],
        ['oo', self::VOWEL | self::DIPHTHONG],
        ['p', self::CONSONANT],
        ['ph', self::CONSONANT | self::DIPHTHONG],
        ['qu', self::CONSONANT | self::DIPHTHONG],
        ['r', self::CONSONANT],
        ['s', self::CONSONANT],
        ['sh', self::CONSONANT | self::DIPHTHONG],
        ['t', self::CONSONANT],
        ['th', self::CONSONANT | self::DIPHTHONG],
        ['u', self::VOWEL],
        ['v', self::CONSONANT],
        ['w', self::CONSONANT],
        ['x', self::CONSONANT],
        ['y', self::CONSONANT],
        ['z', self::CONSONANT],
    ];

    private function __construct(private string $string, public int $flags)
    {
    }

    public static function createRandom(): self
    {
        $elementId = random_int(0, count(self::ELEMENTS) - 1);
        $element = self::ELEMENTS[$elementId];

        return new self($element[0], $element[1]);
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function isConsonant(): bool
    {
        return (bool) ($this->flags & self::CONSONANT);
    }

    public function isVowel(): bool
    {
        return (bool) ($this->flags & self::VOWEL);
    }

    public function isDiphthong(): bool
    {
        return (bool) ($this->flags & self::DIPHTHONG);
    }

    public function isNotFirst(): bool
    {
        return (bool) ($this->flags & self::NOT_FIRST);
    }
}
