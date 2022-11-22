<?php

declare(strict_types=1);

namespace PWGen\Tests;

use PWGen\PWGen;
use PHPUnit\Framework\TestCase;

final class PWGenTest extends TestCase
{
    public function dataSetLength(): iterable
    {
        yield [-1, 8];
        yield [0, 8];
        yield [1, 1];
        yield [2, 2];
        yield [4, 4];
        yield [20, 20];
        yield [100, 100];
    }

    /**
     * @dataProvider dataSetLength
     */
    public function testSetLength(int $pwdLength, int $expectedLength): void
    {
        $pwgen = new PWGen(length: $pwdLength);
        $password = (string) $pwgen;

        $this->assertSame($expectedLength, strlen($password));
    }

    public function testGenerateSecure(): void
    {
        $pwgen = new PWGen(length: 20, secure: true);
        $password = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $password); // Alpha lower
        $this->assertMatchesRegularExpression('/[A-Z]/', $password); // Alpha upper
        $this->assertMatchesRegularExpression('/\\d/', $password); // Numerals
    }

    public function testGenerateNoNumerals(): void
    {
        $pwgen = new PWGen(length: 20, numerals: false);
        $pass = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $pass); // Alpha lower
        $this->assertMatchesRegularExpression('/[A-Z]/', $pass); // Alpha upper
        $this->assertDoesNotMatchRegularExpression('/[\\d]/', $pass); // NOT numerals
    }

    public function testGenerateNoUppers(): void
    {
        $pwgen = new PWGen(length: 20, capitalize: false);
        $pass = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $pass); // Alpha lower
        $this->assertDoesNotMatchRegularExpression('/[A-Z]/', $pass); // Alpha NOT upper
        $this->assertMatchesRegularExpression('/\\d/', $pass); // Numerals
    }

    public function testGenerateAmbiguousRandom(): void
    {
        $pwgen = new PWGen(length: 500, secure: true, ambiguous: true);
        $pass = (string) $pwgen;

        $this->assertDoesNotMatchRegularExpression('/[B8G6I1l0OQDS5Z2]/', $pass);
    }

    public function testGenerateAmbiguousPhonemes(): void
    {
        $pwgen = new PWGen(length: 500, secure: false, ambiguous: true);
        $pass = (string) $pwgen;

        $this->assertDoesNotMatchRegularExpression('/[B8G6I1l0OQDS5Z2]/', $pass);
    }

    public function testGenerateNoVovels(): void
    {
        $pwgen = new PWGen(length: 500, noVowels: true);
        $pass = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $pass); // Alpha lower
        $this->assertMatchesRegularExpression('/[A-Z]/', $pass); // Alpha upper
        $this->assertMatchesRegularExpression('/[\\d]/', $pass); // Numerals
        $this->assertDoesNotMatchRegularExpression('/[01aeiouyAEIOUY]/', $pass); // No Vovels
    }

    public function testGenerateSymbols(): void
    {
        $pwgen = new PWGen(length: 20, symbols: true);
        $pass = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $pass); // Alpha lower
        $this->assertMatchesRegularExpression('/[A-Z]/', $pass); // Alpha upper
        $this->assertMatchesRegularExpression('/[\\d]/', $pass); // Numerals
        $this->assertMatchesRegularExpression('/[!"#$%&\'()*+,-.\/:;<=>?@[\\]^_`{|}~]/', $pass); // Symbols
    }

    public function testGenerateRemoveChars(): void
    {
        $pwgen = new PWGen(length: 500, secure: true, removeChars: 'abcdef90');
        $pass = (string) $pwgen;

        $this->assertMatchesRegularExpression('/[a-z]/', $pass); // Alpha lower
        $this->assertMatchesRegularExpression('/[A-Z]/', $pass); // Alpha upper
        $this->assertMatchesRegularExpression('/[\\d]/', $pass); // Numerals
        $this->assertDoesNotMatchRegularExpression('/[abcdef90]/', $pass); // No removed chars
    }
}
