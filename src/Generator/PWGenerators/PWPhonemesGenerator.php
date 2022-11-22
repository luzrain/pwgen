<?php

/**
 * Port of the famous GNU/Linux Password Generator ("pwgen") to PHP.
 * This file may be distributed under the terms of the GNU Public License.
 * Copyright (C) 2001, 2002 by Theodore Ts'o <tytso@alum.mit.edu>
 * Copyright (C) 2009 by Superwayne <superwayne@superwayne.org>
 */

declare(strict_types=1);

namespace PWGen\Generator\PWGenerators;

use PWGen\Generator\PWGenerator;
use PWGen\Generator\PWElement;
use PWGen\PWOptions;

final class PWPhonemesGenerator extends PWGenerator
{
    public function generate(PWOptions $options): string
    {
        $password = [];

        do {
            $featureFlags = clone $options;
            $c = 0;
            $prev = null;
            $first = true;
            $shouldBe = random_int(0, 1) ? PWElement::VOWEL : PWElement::CONSONANT;

            while ($c < $options->length) {
                $element = PWElement::createRandom();
                $str = (string) $element;
                $len = strlen($str);
                $flags = $element->flags;

                /* Filter on the basic type of the next element */
                if (($flags & $shouldBe) === 0) {
                    continue;
                }

                /* Handle the NOT_FIRST flag */
                if ($first && $element->isNotFirst()) {
                    continue;
                }

                /* Don't allow VOWEL followed a Vowel/Dipthong pair */
                if ($prev?->isVowel() && $element->isVowel() && $element->isDiphthong()) {
                    continue;
                }

                /* Don't allow us to overflow the buffer */
                if ($len > $options->length - $c) {
                    continue;
                }

                /*
                 * OK, we found an element which matches our criteria,
                 * let's do it!
                 */
                for ($j = 0; $j < $len; $j++) {
                    $password[$c + $j] = substr($str, $j, 1);
                }

                /* Handle PW_UPPERS */
                if ($options->isUppers && ($first || $element->isConsonant()) && (random_int(0, 9) < 2)) {
                    $password[$c] = strtoupper($password[$c]);
                    $featureFlags->isUppers = false;
                }

                /* Handle the AMBIGUOUS flag */
                if ($options->isAmbiguous && str_contains(self::PW_AMBIGUOUS, $password[$c])) {
                    continue;
                }

                $c += $len;

                /* Time to stop? */
                if ($c >= $options->length) {
                    break;
                }

                /* Handle PW_DIGITS */
                if ($options->isDigits && !$first && (random_int(0, 9) < 3)) {
                    do {
                        $ch = strval(random_int(0, 9));
                    } while ($options->isAmbiguous && str_contains(self::PW_AMBIGUOUS, $ch));

                    $password[$c++] = $ch;
                    $featureFlags->isDigits = false;

                    $first = true;
                    $prev = null;
                    $shouldBe = random_int(0, 1) ? PWElement::VOWEL : PWElement::CONSONANT;

                    continue;
                }

                /* Handle PW_SYMBOLS */
                if ($options->isSymbols && !$first && (random_int(0, 9) < 2)) {
                    do {
                        $ch = self::PW_SYMBOLS[random_int(0, strlen(self::PW_SYMBOLS) - 1)];
                    } while ($options->isAmbiguous && str_contains(self::PW_AMBIGUOUS, $ch));

                    $password[$c++] = $ch;
                    $featureFlags->isSymbols = false;
                }

                /* OK, figure out what the next element should be */
                if ($shouldBe === PWElement::CONSONANT) {
                    $shouldBe = PWElement::VOWEL;
                } else { // should_be == VOWEL
                    if ($prev?->isVowel() || $element->isDiphthong() || (random_int(0, 9) > 3)) {
                        $shouldBe = PWElement::CONSONANT;
                    } else {
                        $shouldBe = PWElement::VOWEL;
                    }
                }

                $prev = $element;
                $first = false;
            }
        } while ($featureFlags->isUppers || $featureFlags->isDigits || $featureFlags->isSymbols);

        return implode('', $password);
    }
}
