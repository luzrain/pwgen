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
use PWGen\PWOptions;

final class PWRandomGenerator extends PWGenerator
{
    public function generate(PWOptions $options): string
    {
        $chars = self::PW_LOWERS;

        if ($options->isDigits) {
            $chars .= self::PW_DIGITS;
        }

        if ($options->isUppers) {
            $chars .= self::PW_UPPERS;
        }

        if ($options->isSymbols) {
            $chars .= self::PW_SYMBOLS;
        }

        if ($options->removeChars !== '') {
            $chars = str_replace(str_split($options->removeChars), '', $chars);
        }

        $password = [];
        $chars = str_split($chars);
        $len = count($chars);

        do {
            $featureFlags = clone $options;
            $i = 0;

            while ($i < $options->length) {
                $ch = $chars[random_int(0, $len - 1)];

                if ($options->isAmbiguous && str_contains(self::PW_AMBIGUOUS, $ch)) {
                    continue;
                }

                if ($options->isNoVowels && str_contains(self::PW_VOWELS, $ch)) {
                    continue;
                }

                $password[$i++] = $ch;

                if ($options->isDigits && str_contains(self::PW_DIGITS, $ch)) {
                    $featureFlags->isDigits = false;
                }

                if ($options->isUppers && str_contains(self::PW_UPPERS, $ch)) {
                    $featureFlags->isUppers = false;
                }

                if ($options->isSymbols && str_contains(self::PW_SYMBOLS, $ch)) {
                    $featureFlags->isSymbols = false;
                }
            }
        } while ($featureFlags->isUppers || $featureFlags->isDigits || $featureFlags->isSymbols);

        return implode('', $password);
    }
}
