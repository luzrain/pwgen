<?php

/**
 * Port of the famous GNU/Linux Password Generator ("pwgen") to PHP.
 * This file may be distributed under the terms of the GNU Public License.
 * Copyright (C) 2001, 2002 by Theodore Ts'o <tytso@alum.mit.edu>
 * Copyright (C) 2009 by Superwayne <superwayne@superwayne.org>
 */

declare(strict_types=1);

namespace PWGen;

final class PWOptions
{
    public function __construct(
        public int $length,
        public bool $isUppers,
        public bool $isDigits,
        public bool $isSymbols,
        public bool $isSecure,
        public bool $isAmbiguous,
        public bool $isNoVowels,
        public string $removeChars,
    ) {
    }
}
