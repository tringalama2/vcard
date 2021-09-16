<?php

// required to load
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Tringalama2\VCard\VCardException;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * VCard Exception Test.
 */
class VCardExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(VCardException::class);
        throw new VCardException('Testing the VCard error.');
    }
}
