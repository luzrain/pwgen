<?php

/**
 * Port of the famous GNU/Linux Password Generator ("pwgen") to PHP.
 * This file may be distributed under the terms of the GNU Public License.
 * Copyright (C) 2001, 2002 by Theodore Ts'o <tytso@alum.mit.edu>
 * Copyright (C) 2009 by Superwayne <superwayne@superwayne.org>
 */

declare(strict_types=1);

namespace PWGen\Generator;

use PWGen\PWOptions;

abstract class PWGenerator
{
    protected const PW_DIGITS = '0123456789';
    protected const PW_UPPERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected const PW_LOWERS = 'abcdefghijklmnopqrstuvwxyz';
    protected const PW_SYMBOLS = '!"#$%&\'()*+,-.\/:;<=>?@[\\]^_`{|}~';
    protected const PW_AMBIGUOUS = 'B8G6I1l0OQDS5Z2';
    protected const PW_VOWELS = '01aeiouyAEIOUY';

    abstract public function generate(PWOptions $options): string;
}
