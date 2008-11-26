<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2002-2008, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2008 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: Assert.php 3973 2008-11-08 07:51:31Z sb $
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Util/Class.php';
require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/Util/Type.php';
require_once 'PHPUnit/Util/XML.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

if (!class_exists('PHPUnit_Framework_Assert', FALSE)) {

/**
 * A set of assert methods.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2008 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 * @abstract
 */
abstract class PHPUnit_Framework_Assert
{
    /**
     * @var integer
     */
    protected static $count = 0;

    /**
     * Asserts that an array has a specified key.
     *
     * @param  mixed  $key
     * @param  array  $array
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertArrayHasKey($key, array $array, $message = '')
    {
        if (!(is_integer($key) || is_string($key))) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_ArrayHasKey($key);

        self::assertThat($array, $constraint, $message);
    }

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param  mixed  $key
     * @param  array  $array
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertArrayNotHasKey($key, array $array, $message = '')
    {
        if (!(is_integer($key) || is_string($key))) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_ArrayHasKey($key)
        );

        self::assertThat($array, $constraint, $message);
    }

    /**
     * Asserts that a haystack contains a needle.
     *
     * @param  mixed   $needle
     * @param  mixed   $haystack
     * @param  string  $message
     * @since  Method available since Release 2.1.0
     */
    public static function assertContains($needle, $haystack, $message = '')
    {
        if (is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Iterator) {
            $constraint = new PHPUnit_Framework_Constraint_TraversableContains($needle);
        }

        else if (is_string($haystack)) {
            $constraint = new PHPUnit_Framework_Constraint_StringContains($needle);
        }

        else {
            throw new InvalidArgumentException;
        }

        self::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains a needle.
     *
     * @param  mixed   $needle
     * @param  string  $haystackAttributeName
     * @param  mixed   $haystackClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertAttributeContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertContains(
          $needle,
          self::readAttribute($haystackClassOrObject, $haystackAttributeName),
          $message
        );
    }

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param  mixed   $needle
     * @param  mixed   $haystack
     * @param  string  $message
     * @since  Method available since Release 2.1.0
     */
    public static function assertNotContains($needle, $haystack, $message = '')
    {
        if (is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Iterator) {
            $constraint = new PHPUnit_Framework_Constraint_Not(
              new PHPUnit_Framework_Constraint_TraversableContains($needle)
            );
        }

        else if (is_string($haystack)) {
            $constraint = new PHPUnit_Framework_Constraint_Not(
              new PHPUnit_Framework_Constraint_StringContains($needle)
            );
        }

        else {
            throw new InvalidArgumentException;
        }

        self::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain a needle.
     *
     * @param  mixed   $needle
     * @param  string  $haystackAttributeName
     * @param  mixed   $haystackClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertAttributeNotContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertNotContains(
          $needle,
          self::readAttribute($haystackClassOrObject, $haystackAttributeName),
          $message
        );
    }

    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @param  string  $type
     * @param  mixed   $haystack
     * @param  boolean $isNativeType
     * @param  string  $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertContainsOnly($type, $haystack, $isNativeType = NULL, $message = '')
    {
        if (!(is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Iterator)) {
            throw new InvalidArgumentException;
        }

        if ($isNativeType == NULL) {
            $isNativeType = PHPUnit_Util_Type::isType($type);
        }

        self::assertThat(
          $haystack,
          new PHPUnit_Framework_Constraint_TraversableContainsOnly(
            $type, $isNativeType
          ),
          $message
        );
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains only values of a given type.
     *
     * @param  string  $type
     * @param  string  $haystackAttributeName
     * @param  mixed   $haystackClassOrObject
     * @param  boolean $isNativeType
     * @param  string  $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertAttributeContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = NULL, $message = '')
    {
        self::assertContainsOnly(
          $type,
          self::readAttribute($haystackClassOrObject, $haystackAttributeName),
          $isNativeType,
          $message
        );
    }

    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @param  string  $type
     * @param  mixed   $haystack
     * @param  boolean $isNativeType
     * @param  string  $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertNotContainsOnly($type, $haystack, $isNativeType = NULL, $message = '')
    {
        if (!(is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Iterator)) {
            throw new InvalidArgumentException;
        }

        if ($isNativeType == NULL) {
            $isNativeType = PHPUnit_Util_Type::isType($type);
        }

        self::assertThat(
          $haystack,
          new PHPUnit_Framework_Constraint_Not(
            new PHPUnit_Framework_Constraint_TraversableContainsOnly(
              $type, $isNativeType
            )
          ),
          $message
        );
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain only values of a given type.
     *
     * @param  string  $type
     * @param  string  $haystackAttributeName
     * @param  mixed   $haystackClassOrObject
     * @param  boolean $isNativeType
     * @param  string  $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertAttributeNotContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = NULL, $message = '')
    {
        self::assertNotContainsOnly(
          $type,
          self::readAttribute($haystackClassOrObject, $haystackAttributeName),
          $isNativeType,
          $message
        );
    }

    /**
     * Asserts that two variables are equal.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @param  float   $delta
     * @param  integer $maxDepth
     * @param  boolean $canonicalizeEol
     */
    public static function assertEquals($expected, $actual, $message = '', $delta = 0, $maxDepth = 10, $canonicalizeEol = FALSE)
    {
        $constraint = new PHPUnit_Framework_Constraint_IsEqual(
          $expected, $delta, $maxDepth, $canonicalizeEol
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is equal to an attribute of an object.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @param  float   $delta
     * @param  integer $maxDepth
     * @param  boolean $canonicalizeEol
     */
    public static function assertAttributeEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0, $maxDepth = 10, $canonicalizeEol = FALSE)
    {
        self::assertEquals(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message,
          $delta,
          $maxDepth,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that two variables are not equal.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @param  float   $delta
     * @param  integer $maxDepth
     * @param  boolean $canonicalizeEol
     * @since  Method available since Release 2.3.0
     */
    public static function assertNotEquals($expected, $actual, $message = '', $delta = 0, $maxDepth = 10, $canonicalizeEol = FALSE)
    {
        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_IsEqual(
            $expected, $delta, $maxDepth, $canonicalizeEol
          )
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is not equal to an attribute of an object.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @param  float   $delta
     * @param  integer $maxDepth
     * @param  boolean $canonicalizeEol
     */
    public static function assertAttributeNotEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0, $maxDepth = 10, $canonicalizeEol = FALSE)
    {
        self::assertNotEquals(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message,
          $delta,
          $maxDepth,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that a value is greater than another value.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertGreaterThan($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::greaterThan($expected), $message);
    }

    /**
     * Asserts that an attribute is greater than another value.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeGreaterThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertGreaterThan(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertGreaterThanOrEqual($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::greaterThanOrEqual($expected), $message);
    }

    /**
     * Asserts that an attribute is greater than or equal to another value.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeGreaterThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertGreaterThanOrEqual(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that a value is smaller than another value.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertLessThan($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::lessThan($expected), $message);
    }

    /**
     * Asserts that an attribute is smaller than another value.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeLessThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertLessThan(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertLessThanOrEqual($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::lessThanOrEqual($expected), $message);
    }

    /**
     * Asserts that an attribute is smaller than or equal to another value.
     *
     * @param  mixed   $expected
     * @param  string  $actualAttributeName
     * @param  string  $actualClassOrObject
     * @param  string  $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeLessThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertLessThanOrEqual(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @param  string  $expected
     * @param  string  $actual
     * @param  string  $message
     * @param  boolean $canonicalizeEol
     * @since  Method available since Release 3.2.14
     */
    public static function assertFileEquals($expected, $actual, $message = '', $canonicalizeEol = FALSE)
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        self::assertEquals(
          file_get_contents($expected),
          file_get_contents($actual),
          $message,
          0,
          10,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that the contents of one file is not equal to the contents of
     * another file.
     *
     * @param  string  $expected
     * @param  string  $actual
     * @param  string  $message
     * @param  boolean $canonicalizeEol
     * @since  Method available since Release 3.2.14
     */
    public static function assertFileNotEquals($expected, $actual, $message = '', $canonicalizeEol = FALSE)
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        self::assertNotEquals(
          file_get_contents($expected),
          file_get_contents($actual),
          $message,
          0,
          10,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @param  string  $expectedFile
     * @param  string  $actualString
     * @param  string  $message
     * @param  boolean $canonicalizeEol
     * @since  Method available since Release 3.3.0
     */
    public static function assertStringEqualsFile($expectedFile, $actualString, $message = '', $canonicalizeEol = FALSE)
    {
        self::assertFileExists($expectedFile, $message);

        self::assertEquals(
          file_get_contents($expectedFile),
          $actualString,
          $message,
          0,
          10,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @param  string  $expectedFile
     * @param  string  $actualString
     * @param  string  $message
     * @param  boolean $canonicalizeEol
     * @since  Method available since Release 3.3.0
     */
    public static function assertStringNotEqualsFile($expectedFile, $actualString, $message = '', $canonicalizeEol = FALSE)
    {
        self::assertFileExists($expectedFile, $message);

        self::assertNotEquals(
          file_get_contents($expectedFile),
          $actualString,
          $message,
          0,
          10,
          $canonicalizeEol
        );
    }

    /**
     * Asserts that a file exists.
     *
     * @param  string $filename
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertFileExists($filename, $message = '')
    {
        if (!is_string($filename)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_FileExists;

        self::assertThat($filename, $constraint, $message);
    }

    /**
     * Asserts that a file does not exist.
     *
     * @param  string $filename
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertFileNotExists($filename, $message = '')
    {
        if (!is_string($filename)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_FileExists
        );

        self::assertThat($filename, $constraint, $message);
    }

    /**
     * Asserts that a condition is true.
     *
     * @param  boolean $condition
     * @param  string  $message
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public static function assertTrue($condition, $message = '')
    {
        self::assertThat($condition, self::isTrue(), $message);
    }

    /**
     * Asserts that a condition is false.
     *
     * @param  boolean  $condition
     * @param  string   $message
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public static function assertFalse($condition, $message = '')
    {
        self::assertThat($condition, self::isFalse(), $message);
    }

    /**
     * Asserts that a variable is not NULL.
     *
     * @param  mixed  $actual
     * @param  string $message
     */
    public static function assertNotNull($actual, $message = '')
    {
        self::assertThat($actual, self::logicalNot(self::isNull()), $message);
    }

    /**
     * Asserts that a variable is NULL.
     *
     * @param  mixed  $actual
     * @param  string $message
     */
    public static function assertNull($actual, $message = '')
    {
        self::assertThat($actual, self::isNull(), $message);
    }

    /**
     * Asserts that a class has a specified attribute.
     *
     * @param  string $attributeName
     * @param  string $className
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassHasAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName) || !is_string($className) || !class_exists($className, FALSE)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_ClassHasAttribute($attributeName);

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @param  string $attributeName
     * @param  string $className
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassNotHasAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName) || !is_string($className) || !class_exists($className)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_ClassHasAttribute($attributeName)
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class has a specified static attribute.
     *
     * @param  string $attributeName
     * @param  string $className
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassHasStaticAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName) || !is_string($className) || !class_exists($className)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_ClassHasStaticAttribute($attributeName);

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @param  string $attributeName
     * @param  string $className
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassNotHasStaticAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName) || !is_string($className) || !class_exists($className)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_ClassHasStaticAttribute($attributeName)
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that an object has a specified attribute.
     *
     * @param  string $attributeName
     * @param  object $object
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertObjectHasAttribute($attributeName, $object, $message = '')
    {
        if (!is_string($attributeName) || !is_object($object)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_ObjectHasAttribute($attributeName);

        self::assertThat($object, $constraint, $message);
    }

    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param  string $attributeName
     * @param  object $object
     * @param  string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertObjectNotHasAttribute($attributeName, $object, $message = '')
    {
        if (!is_string($attributeName) || !is_object($object)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_ObjectHasAttribute($attributeName)
        );

        self::assertThat($object, $constraint, $message);
    }

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param  mixed  $expected
     * @param  mixed  $actual
     * @param  string $message
     */
    public static function assertSame($expected, $actual, $message = '')
    {
        if (is_bool($expected) && is_bool($actual)) {
            self::assertEquals($expected, $actual, $message);
        } else {
            $constraint = new PHPUnit_Framework_Constraint_IsIdentical($expected);

            self::assertThat($actual, $constraint, $message);
        }
    }

    /**
     * Asserts that a variable and an attribute of an object have the same type
     * and value.
     *
     * @param  mixed  $expected
     * @param  string $actualAttributeName
     * @param  object $actualClassOrObject
     * @param  string $message
     */
    public static function assertAttributeSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertSame(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @param  mixed  $expected
     * @param  mixed  $actual
     * @param  string $message
     */
    public static function assertNotSame($expected, $actual, $message = '')
    {
        if (is_bool($expected) && is_bool($actual)) {
            self::assertNotEquals($expected, $actual, $message);
        } else {
            $constraint = new PHPUnit_Framework_Constraint_Not(
              new PHPUnit_Framework_Constraint_IsIdentical($expected)
            );

            self::assertThat($actual, $constraint, $message);
        }
    }

    /**
     * Asserts that a variable and an attribute of an object do not have the
     * same type and value.
     *
     * @param  mixed  $expected
     * @param  string $actualAttributeName
     * @param  object $actualClassOrObject
     * @param  string $message
     */
    public static function assertAttributeNotSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertNotSame(
          $expected,
          self::readAttribute($actualClassOrObject, $actualAttributeName),
          $message
        );
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param  string $expected
     * @param  mixed  $actual
     * @param  string $message
     */
    public static function assertType($expected, $actual, $message = '')
    {
        if (is_string($expected)) {
            if (PHPUnit_Util_Type::isType($expected)) {
                $constraint = new PHPUnit_Framework_Constraint_IsType($expected);
            }

            else if (class_exists($expected) || interface_exists($expected)) {
                $constraint = new PHPUnit_Framework_Constraint_IsInstanceOf(
                  $expected
                );
            }

            else {
                throw new InvalidArgumentException;
            }
        } else {
            throw new InvalidArgumentException;
        }

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param  string $expected
     * @param  mixed  $actual
     * @param  string $message
     * @since  Method available since Release 2.2.0
     */
    public static function assertNotType($expected, $actual, $message = '')
    {
        if (is_string($expected)) {
            if (PHPUnit_Util_Type::isType($expected)) {
                $constraint = new PHPUnit_Framework_Constraint_Not(
                  new PHPUnit_Framework_Constraint_IsType($expected)
                );
            }

            else if (class_exists($expected) || interface_exists($expected)) {
                $constraint = new PHPUnit_Framework_Constraint_Not(
                  new PHPUnit_Framework_Constraint_IsInstanceOf($expected)
                );
            }

            else {
                throw new InvalidArgumentException;
            }
        } else {
            throw new InvalidArgumentException;
        }

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a string matches a given regular expression.
     *
     * @param  string $pattern
     * @param  string $string
     * @param  string $message
     */
    public static function assertRegExp($pattern, $string, $message = '')
    {
        if (!is_string($pattern) || !is_string($string)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_PCREMatch($pattern);

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @param  string $pattern
     * @param  string $string
     * @param  string $message
     * @since  Method available since Release 2.1.0
     */
    public static function assertNotRegExp($pattern, $string, $message = '')
    {
        if (!is_string($pattern) || !is_string($string)) {
            throw new InvalidArgumentException;
        }

        $constraint = new PHPUnit_Framework_Constraint_Not(
          new PHPUnit_Framework_Constraint_PCREMatch($pattern)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that two XML files are equal.
     *
     * @param  string $expectedFile
     * @param  string $actualFile
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile);
        self::assertFileExists($actualFile);

        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->load($expectedFile);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->load($actualFile);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML files are not equal.
     *
     * @param  string $expectedFile
     * @param  string $actualFile
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile);
        self::assertFileExists($actualFile);

        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->load($expectedFile);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->load($actualFile);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param  string $expectedFile
     * @param  string $actualXml
     * @param  string $message
     * @since  Method available since Release 3.3.0
     */
    public static function assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        self::assertFileExists($expectedFile);

        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->load($expectedFile);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->loadXML($actualXml);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param  string $expectedFile
     * @param  string $actualXml
     * @param  string $message
     * @since  Method available since Release 3.3.0
     */
    public static function assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        self::assertFileExists($expectedFile);

        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->load($expectedFile);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->loadXML($actualXml);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param  string $expectedXml
     * @param  string $actualXml
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->loadXML($expectedXml);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->loadXML($actualXml);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param  string $expectedXml
     * @param  string $actualXml
     * @param  string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        $expected = new DOMDocument;
        $expected->preserveWhiteSpace = FALSE;
        $expected->loadXML($expectedXml);

        $actual = new DOMDocument;
        $actual->preserveWhiteSpace = FALSE;
        $actual->loadXML($actualXml);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that a hierarchy of DOMNodes matches.
     *
     * @param DOMNode $expectedNode
     * @param DOMNode $actualNode
     * @param boolean $checkAttributes
     * @param string  $message
     * @author Mattis Stordalen Flister <mattis@xait.no>
     * @since  Method available since Release 3.3.0
     */
    public static function assertEqualXMLStructure(DOMNode $expectedNode, DOMNode $actualNode, $checkAttributes = FALSE, $message = '')
    {
        self::assertEquals(
          $expectedNode->tagName,
          $actualNode->tagName,
          $message
        );

        if ($checkAttributes) {
            self::assertEquals(
              $expectedNode->attributes->length,
              $actualNode->attributes->length,
              sprintf(
                '%s%sNumber of attributes on node "%s" does not match',
                $message,
                !empty($message) ? "\n" : '',
                $expectedNode->tagName
              )
            );

            for ($i = 0 ; $i < $expectedNode->attributes->length; $i++) {
                $expectedAttribute = $expectedNode->attributes->item($i);
                $actualAttribute   = $actualNode->attributes->getNamedItem($expectedAttribute->name);

                if (!$actualAttribute) {
                    self::fail(
                      sprintf(
                        '%s%sCould not find attribute "%s" on node "%s"',
                        $message,
                        !empty($message) ? "\n" : '',
                        $expectedAttribute->name,
                        $expectedNode->tagName
                      )
                    );
                }
            }
        }

        PHPUnit_Util_XML::removeCharacterDataNodes($expectedNode);
        PHPUnit_Util_XML::removeCharacterDataNodes($actualNode);

        self::assertEquals(
          $expectedNode->childNodes->length,
          $actualNode->childNodes->length,
          sprintf(
            '%s%sNumber of child nodes of "%s" differs',
            $message,
            !empty($message) ? "\n" : '',
            $expectedNode->tagName
          )
        );

        for ($i = 0; $i < $expectedNode->childNodes->length; $i++) {
            self::assertEqualXMLStructure(
              $expectedNode->childNodes->item($i),
              $actualNode->childNodes->item($i),
              $checkAttributes,
              $message
            );
        }
    }

    /**
     * Assert the presence, absense, or count of elements in a document matching
     * the CSS $selector, regardless of the contents of those elements.
     *
     * The first argument, $selector, is the CSS selector used to match
     * the elements in the $actual document.
     *
     * The second argument, $count, can be either boolean or numeric.  When boolean,
     * it asserts for presence of elements matching the selector (TRUE) or absense
     * of elements (FALSE).  When numeric, it asserts the count of elements
     *
     * assertSelectCount("#binder", true, $xml);  // any?
     * assertSelectCount(".binder", 3, $xml);     // exactly 3?
     *
     * @param  array   $selector
     * @param  integer $count
     * @param  mixed   $actual
     * @param  string  $message
     * @param  boolean $isHtml
     * @since  Method available since Release 3.3.0
     * @author Mike Naberezny <mike@maintainable.com>
     * @author Derek DeVries <derek@maintainable.com>
     */
    public static function assertSelectCount($selector, $count, $actual, $message = '', $isHtml = TRUE)
    {
        self::assertSelectEquals($selector, TRUE, $count, $actual, $message, $isHtml);
    }

    /**
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", true, $xml); // any?
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", 3, $xml);    // exactly 3?
     *
     * @param  array   $selector
     * @param  string  $pattern
     * @param  integer $count
     * @param  mixed   $actual
     * @param  string  $message
     * @param  boolean $isHtml
     * @since  Method available since Release 3.3.0
     * @author Mike Naberezny <mike@maintainable.com>
     * @author Derek DeVries <derek@maintainable.com>
     */
    public static function assertSelectRegExp($selector, $pattern, $count, $actual, $message = '', $isHtml = TRUE)
    {
        self::assertSelectEquals($selector, "regexp:$pattern", $count, $actual, $message, $isHtml);
    }

    /**
     * assertSelectEquals("#binder .name", "Chuck", true,  $xml);  // any?
     * assertSelectEquals("#binder .name", "Chuck", false, $xml);  // none?
     *
     * @param  array   $selector
     * @param  string  $content
     * @param  integer $count
     * @param  mixed   $actual
     * @param  string  $message
     * @param  boolean $isHtml
     * @since  Method available since Release 3.3.0
     * @author Mike Naberezny <mike@maintainable.com>
     * @author Derek DeVries <derek@maintainable.com>
     */
    public static function assertSelectEquals($selector, $content, $count, $actual, $message = '', $isHtml = TRUE)
    {
        $tags = PHPUnit_Util_XML::cssSelect($selector, $content, $actual, $isHtml);

        // assert specific number of elements
        if (is_numeric($count)) {
            $counted = $tags ? count($tags) : 0;
            self::assertEquals($count, $counted);

        // assert any elements exist if true, assert no elements exist if false
        } else if (is_bool($count)) {
            $any = count($tags) > 0 && $tags[0] instanceof DOMNode;

            if ($count) {
                self::assertTrue($any, $message);
            } else {
                self::assertFalse($any, $message);
            }

        // check for range number of elements
        } else if (is_array($count) && (isset($count['>']) || isset($count['<']) ||
                isset($count['>=']) || isset($count['<=']))) {
            $counted = $tags ? count($tags) : 0;

            if (isset($count['>'])) {
                self::assertTrue($counted > $count['>'], $message);
            }

            if (isset($count['>='])) {
                self::assertTrue($counted >= $count['>='], $message);
            }

            if (isset($count['<'])) {
                self::assertTrue($counted < $count['<'], $message);
            }

            if (isset($count['<='])) {
                self::assertTrue($counted <= $count['<='], $message);
            }

        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * Evaluate an HTML or XML string and assert its structure and/or contents.
     *
     * The first argument ($matcher) is an associative array that specifies the
     * match criteria for the assertion:
     *
     *  - `id`           : the node with the given id attribute must match the corresponsing value.
     *  - `tag`          : the node type must match the corresponding value.
     *  - `attributes`   : a hash. The node's attributres must match the corresponsing values in the hash.
     *  - `content`      : The text content must match the given value.
     *  - `parent`       : a hash. The node's parent must match the corresponsing hash.
     *  - `child`        : a hash. At least one of the node's immediate children must meet the criteria described by the hash.
     *  - `ancestor`     : a hash. At least one of the node's ancestors must meet the criteria described by the hash.
     *  - `descendant`   : a hash. At least one of the node's descendants must meet the criteria described by the hash.
     *  - `children`     : a hash, for counting children of a node. Accepts the keys:
     *    - `count`        : a number which must equal the number of children that match
     *    - `less_than`    : the number of matching children must be greater than this number
     *    - `greater_than` : the number of matching children must be less than this number
     *    - `only`         : another hash consisting of the keys to use to match on the children, and only matching children will be counted
     *
     * <code>
     * // Matcher that asserts that there is an element with an id="my_id".
     * $matcher = array('id' => 'my_id');
     *
     * // Matcher that asserts that there is a "span" tag.
     * $matcher = array('tag' => 'span');
     *
     * // Matcher that asserts that there is a "span" tag with the content
     * // "Hello World".
     * $matcher = array('tag' => 'span', 'content' => 'Hello World');
     *
     * // Matcher that asserts that there is a "span" tag with content matching the
     * // regular expression pattern.
     * $matcher = array('tag' => 'span', 'content' => '/Try P(HP|ython)/');
     *
     * // Matcher that asserts that there is a "span" with an "list" class attribute.
     * $matcher = array(
     *   'tag'        => 'span',
     *   'attributes' => array('class' => 'list')
     * );
     *
     * // Matcher that asserts that there is a "span" inside of a "div".
     * $matcher = array(
     *   'tag'    => 'span',
     *   'parent' => array('tag' => 'div')
     * );
     *
     * // Matcher that asserts that there is a "span" somewhere inside a "table".
     * $matcher = array(
     *   'tag'      => 'span',
     *   'ascestor' => array('tag' => 'table')
     * );
     *
     * // Matcher that asserts that there is a "span" with at least one "em" child.
     * $matcher = array(
     *   'tag'   => 'span',
     *   'child' => array('tag' => 'em')
     * );
     *
     * // Matcher that asserts that there is a "span" containing a (possibly nested)
     * // "strong" tag.
     * $matcher = array(
     *   'tag'        => 'span',
     *   'descendant' => array('tag' => 'strong')
     * );
     *
     * // Matcher that asserts that there is a "span" containing 5-10 "em" tags as
     * // immediate children.
     * $matcher = array(
     *   'tag'      => 'span',
     *   'children' => array(
     *     'less_than'    => 11,
     *     'greater_than' => 4,
     *     'only'         => array('tag' => 'em')
     *   )
     * );
     *
     * // Matcher that asserts that there is a "div", with an "ul" ancestor and a "li"
     * // parent (with class="enum"), and containing a "span" descendant that contains
     * // an element with id="my_test" and the text "Hello World".
     * $matcher = array(
     *   'tag'        => 'div',
     *   'ancestor'   => array('tag' => 'ul'),
     *   'parent'     => array(
     *     'tag'        => 'li',
     *     'attributes' => array('class' => 'enum')
     *   ),
     *   'descendant' => array(
     *     'tag'   => 'span',
     *     'child' => array(
     *       'id'      => 'my_test',
     *       'content' => 'Hello World'
     *     )
     *   )
     * );
     *
     * // Use assertTag() to apply a $matcher to a piece of $html.
     * $this->assertTag($matcher, $html);
     *
     * // Use assertTag() to apply a $matcher to a piece of $xml.
     * $this->assertTag($matcher, $xml, '', FALSE);
     * </code>
     *
     * The second argument ($actual) is a string containing either HTML or
     * XML text to be tested.
     *
     * The third argument ($message) is an optional message that will be
     * used if the assertion fails.
     *
     * The fourth argument ($html) is an optional flag specifying whether
     * to load the $actual string into a DOMDocument using the HTML or
     * XML load strategy.  It is TRUE by default, which assumes the HTML
     * load strategy.  In many cases, this will be acceptable for XML as well.
     *
     * @param  array   $matcher
     * @param  string  $actual
     * @param  string  $message
     * @param  boolean $isHtml
     * @since  Method available since Release 3.3.0
     * @author Mike Naberezny <mike@maintainable.com>
     * @author Derek DeVries <derek@maintainable.com>
     */
    public static function assertTag($matcher, $actual, $message = '', $isHtml = TRUE)
    {
        $dom     = PHPUnit_Util_XML::load($actual, $isHtml);
        $tags    = PHPUnit_Util_XML::findNodes($dom, $matcher);
        $matched = count($tags) > 0 && $tags[0] instanceof DOMNode;

        self::assertTrue($matched, $message);
    }

    /**
     * This assertion is the exact opposite of assertTag().  Rather than asserting
     * that $matcher results in a match, it asserts that $matcher does not match.
     *
     * @param  array   $matcher
     * @param  string  $actual
     * @param  string  $message
     * @param  boolean $isHtml
     * @since  Method available since Release 3.3.0
     * @author Mike Naberezny <mike@maintainable.com>
     * @author Derek DeVries <derek@maintainable.com>
     */
    public static function assertNotTag($matcher, $actual, $message = '', $isHtml = TRUE)
    {
        $dom     = PHPUnit_Util_XML::load($actual, $isHtml);
        $tags    = PHPUnit_Util_XML::findNodes($dom, $matcher);
        $matched = count($tags) > 0 && $tags[0] instanceof DOMNode;

        self::assertFalse($matched, $message);
    }

    /**
     *
     *
     * @param  mixed                        $value
     * @param  PHPUnit_Framework_Constraint $constraint
     * @param  string                       $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertThat($value, PHPUnit_Framework_Constraint $constraint, $message = '')
    {
        self::$count++;

        if (!$constraint->evaluate($value)) {
            $constraint->fail($value, $message);
        }
    }

    /**
     * Logical AND.
     *
     * @return PHPUnit_Framework_Constraint_And
     * @since  Method available since Release 3.0.0
     */
    public static function logicalAnd()
    {
        $constraints = func_get_args();

        $constraint = new PHPUnit_Framework_Constraint_And;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     * Logical OR.
     *
     * @return PHPUnit_Framework_Constraint_Or
     * @since  Method available since Release 3.0.0
     */
    public static function logicalOr()
    {
        $constraints = func_get_args();

        $constraint = new PHPUnit_Framework_Constraint_Or;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     * Logical NOT.
     *
     * @param  PHPUnit_Framework_Constraint $constraint
     * @return PHPUnit_Framework_Constraint_Not
     * @since  Method available since Release 3.0.0
     */
    public static function logicalNot(PHPUnit_Framework_Constraint $constraint)
    {
        return new PHPUnit_Framework_Constraint_Not($constraint);
    }

    /**
     * Logical XOR.
     *
     * @return PHPUnit_Framework_Constraint_Xor
     * @since  Method available since Release 3.0.0
     */
    public static function logicalXor()
    {
        $constraints = func_get_args();

        $constraint = new PHPUnit_Framework_Constraint_Xor;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     *
     *
     * @return PHPUnit_Framework_Constraint_IsAnything
     * @since  Method available since Release 3.0.0
     */
    public static function anything()
    {
        return new PHPUnit_Framework_Constraint_IsAnything;
    }

    /**
     *
     *
     * @return PHPUnit_Framework_Constraint_IsTrue
     * @since  Method available since Release 3.3.0
     */
    public static function isTrue()
    {
        return new PHPUnit_Framework_Constraint_IsTrue;
    }

    /**
     *
     *
     * @return PHPUnit_Framework_Constraint_IsFalse
     * @since  Method available since Release 3.3.0
     */
    public static function isFalse()
    {
        return new PHPUnit_Framework_Constraint_IsFalse;
    }

    /**
     *
     *
     * @return PHPUnit_Framework_Constraint_IsNull
     * @since  Method available since Release 3.3.0
     */
    public static function isNull()
    {
        return new PHPUnit_Framework_Constraint_IsNull;
    }

    /**
     *
     *
     * @param  PHPUnit_Framework_Constraint $constraint
     * @param  string                       $attributeName
     * @return PHPUnit_Framework_Constraint_Attribute
     * @since  Method available since Release 3.1.0
     */
    public static function attribute(PHPUnit_Framework_Constraint $constraint, $attributeName)
    {
        return new PHPUnit_Framework_Constraint_Attribute(
          $constraint, $attributeName
        );
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_TraversableContains
     * @since  Method available since Release 3.0.0
     */
    public static function contains($value)
    {
        return new PHPUnit_Framework_Constraint_TraversableContains($value);
    }

    /**
     *
     *
     * @param  string $type
     * @return PHPUnit_Framework_Constraint_TraversableContainsOnly
     * @since  Method available since Release 3.1.4
     */
    public static function containsOnly($type)
    {
        return new PHPUnit_Framework_Constraint_TraversableContainsOnly($type);
    }

    /**
     *
     *
     * @param  mixed $key
     * @return PHPUnit_Framework_Constraint_ArrayHasKey
     * @since  Method available since Release 3.0.0
     */
    public static function arrayHasKey($key)
    {
        return new PHPUnit_Framework_Constraint_ArrayHasKey($key);
    }

    /**
     *
     *
     * @param  mixed   $value
     * @param  float   $delta
     * @param  integer $maxDepth
     * @return PHPUnit_Framework_Constraint_IsEqual
     * @since  Method available since Release 3.0.0
     */
    public static function equalTo($value, $delta = 0, $maxDepth = 10)
    {
        return new PHPUnit_Framework_Constraint_IsEqual($value, $delta, $maxDepth);
    }

    /**
     *
     *
     * @param  string  $attributeName
     * @param  mixed   $value
     * @param  float   $delta
     * @param  integer $maxDepth
     * @return PHPUnit_Framework_Constraint_Attribute
     * @since  Method available since Release 3.1.0
     */
    public static function attributeEqualTo($attributeName, $value, $delta = 0, $maxDepth = 10)
    {
        return new PHPUnit_Framework_Constraint_Attribute(
          new PHPUnit_Framework_Constraint_IsEqual($value, $delta, $maxDepth),
          $attributeName
        );
    }

    /**
     *
     *
     * @return PHPUnit_Framework_Constraint_FileExists
     * @since  Method available since Release 3.0.0
     */
    public static function fileExists()
    {
        return new PHPUnit_Framework_Constraint_FileExists;
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_GreaterThan
     * @since  Method available since Release 3.0.0
     */
    public static function greaterThan($value)
    {
        return new PHPUnit_Framework_Constraint_GreaterThan($value);
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_Or
     * @since  Method available since Release 3.1.0
     */
    public static function greaterThanOrEqual($value)
    {
        return self::logicalOr(
          new PHPUnit_Framework_Constraint_IsEqual($value),
          new PHPUnit_Framework_Constraint_GreaterThan($value)
        );
    }

    /**
     *
     *
     * @param  string $attributeName
     * @return PHPUnit_Framework_Constraint_ClassHasAttribute
     * @since  Method available since Release 3.1.0
     */
    public static function classHasAttribute($attributeName)
    {
        return new PHPUnit_Framework_Constraint_ClassHasAttribute($attributeName);
    }

    /**
     *
     *
     * @param  string $attributeName
     * @return PHPUnit_Framework_Constraint_ClassHasStaticAttribute
     * @since  Method available since Release 3.1.0
     */
    public static function classHasStaticAttribute($attributeName)
    {
        return new PHPUnit_Framework_Constraint_ClassHasStaticAttribute($attributeName);
    }

    /**
     *
     *
     * @param  string $attributeName
     * @return PHPUnit_Framework_Constraint_ObjectHasAttribute
     * @since  Method available since Release 3.0.0
     */
    public static function objectHasAttribute($attributeName)
    {
        return new PHPUnit_Framework_Constraint_ObjectHasAttribute($attributeName);
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_IsIdentical
     * @since  Method available since Release 3.0.0
     */
    public static function identicalTo($value)
    {
        return new PHPUnit_Framework_Constraint_IsIdentical($value);
    }

    /**
     *
     *
     * @param  string $className
     * @return PHPUnit_Framework_Constraint_IsInstanceOf
     * @since  Method available since Release 3.0.0
     */
    public static function isInstanceOf($className)
    {
        return new PHPUnit_Framework_Constraint_IsInstanceOf($className);
    }

    /**
     *
     *
     * @param  string $type
     * @return PHPUnit_Framework_Constraint_IsType
     * @since  Method available since Release 3.0.0
     */
    public static function isType($type)
    {
        return new PHPUnit_Framework_Constraint_IsType($type);
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_LessThan
     * @since  Method available since Release 3.0.0
     */
    public static function lessThan($value)
    {
        return new PHPUnit_Framework_Constraint_LessThan($value);
    }

    /**
     *
     *
     * @param  mixed $value
     * @return PHPUnit_Framework_Constraint_Or
     * @since  Method available since Release 3.1.0
     */
    public static function lessThanOrEqual($value)
    {
        return self::logicalOr(
          new PHPUnit_Framework_Constraint_IsEqual($value),
          new PHPUnit_Framework_Constraint_LessThan($value)
        );
    }

    /**
     *
     *
     * @param  string $pattern
     * @return PHPUnit_Framework_Constraint_PCREMatch
     * @since  Method available since Release 3.0.0
     */
    public static function matchesRegularExpression($pattern)
    {
        return new PHPUnit_Framework_Constraint_PCREMatch($pattern);
    }

    /**
     *
     *
     * @param  string  $string
     * @param  boolean $case
     * @return PHPUnit_Framework_Constraint_StringContains
     * @since  Method available since Release 3.0.0
     */
    public static function stringContains($string, $case = TRUE)
    {
        return new PHPUnit_Framework_Constraint_StringContains($string, $case);
    }


    /**
     * Fails a test with the given message.
     *
     * @param  string $message
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public static function fail($message = '')
    {
        throw new PHPUnit_Framework_AssertionFailedError($message);
    }

    /**
     * Returns the value of an attribute of a class or an object.
     * This also works for attributes that are declared protected or private.
     *
     * @param  mixed   $classOrObject
     * @param  string  $attributeName
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function readAttribute($classOrObject, $attributeName)
    {
        if (!is_string($attributeName)) {
            throw new InvalidArgumentException;
        }

        if (is_string($classOrObject)) {
            if (!class_exists($classOrObject)) {
                throw new InvalidArgumentException;
            }

            return PHPUnit_Util_Class::getStaticAttribute(
              $classOrObject,
              $attributeName
            );
        }

        else if (is_object($classOrObject)) {
            return PHPUnit_Util_Class::getObjectAttribute(
              $classOrObject,
              $attributeName
            );
        }

        else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * Mark the test as incomplete.
     *
     * @param  string  $message
     * @throws PHPUnit_Framework_IncompleteTestError
     * @since  Method available since Release 3.0.0
     */
    public static function markTestIncomplete($message = '')
    {
        throw new PHPUnit_Framework_IncompleteTestError($message);
    }

    /**
     * Mark the test as skipped.
     *
     * @param  string  $message
     * @throws PHPUnit_Framework_SkippedTestError
     * @since  Method available since Release 3.0.0
     */
    public static function markTestSkipped($message = '')
    {
        throw new PHPUnit_Framework_SkippedTestError($message);
    }

    /**
     * Return the current assertion count.
     *
     * @return integer
     * @since  Method available since Release 3.3.3
     */
    public static function getCount()
    {
        return self::$count;
    }

    /**
     * Reset the assertion counter.
     *
     * @since  Method available since Release 3.3.3
     */
    public static function resetCount()
    {
        self::$count = 0;
    }
}

}
?>
