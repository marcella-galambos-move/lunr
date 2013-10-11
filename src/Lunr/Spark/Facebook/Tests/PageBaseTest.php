<?php

/**
 * This file contains the PageBaseTest class.
 *
 * PHP Version 5.4
 *
 * @category   Libraries
 * @package    Spark
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Spark\Facebook\Tests;

use Lunr\Spark\Facebook\Page;
use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains the tests for the Facebook Page class.
 *
 * @category   Libraries
 * @package    Spark
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\Spark\Facebook\Page
 */
class PageBaseTest extends PageTest
{

    /**
     * Test that the CentralAuthenticationStore class is passed correctly.
     */
    public function testCasIsSetCorrectly()
    {
        $this->assertPropertySame('cas', $this->cas);
    }

    /**
     * Test that the Curl class is passed correctly.
     */
    public function testCurlIsSetCorrectly()
    {
        $this->assertPropertySame('curl', $this->curl);
    }

    /**
     * Test that the Logger class is passed correctly.
     */
    public function testLoggerIsSetCorrectly()
    {
        $this->assertPropertySame('logger', $this->logger);
    }

    /**
     * Test that the default page ID is an empty String.
     */
    public function testPageIDIsEmptyString()
    {
        $this->assertPropertySame('page_id', '');
    }

    /**
     * Test that data is empty.
     */
    public function testDataIsEmptyByDefault()
    {
        $this->assertArrayEmpty($this->get_reflection_property_value('data'));
    }

    /**
     * Test that check_permissions is FALSE.
     */
    public function testCheckPermissionsIsFalseByDefault()
    {
        $this->assertFalse($this->get_reflection_property_value('check_permissions'));
    }

    /**
     * Test that set_page_id() sets the page ID.
     *
     * @covers Lunr\Spark\Facebook\Page::set_page_id
     */
    public function testSetPageIdSetsPageId()
    {
        $this->class->set_page_id('Lunr');

        $this->assertPropertyEquals('page_id', 'Lunr');
    }

    /**
     * Test that set_fields() sets fields if given an array.
     *
     * @covers Lunr\Spark\Facebook\Page::set_fields
     */
    public function testSetFieldsWithArraySetsFields()
    {
        $fields = [ 'email', 'first_name' ];

        $this->class->set_fields($fields);

        $this->assertPropertySame('fields', $fields);
    }

    /**
     * Test that set_fields() does not set fields if not given an array.
     *
     * @param mixed $value Non array value
     *
     * @dataProvider nonArrayProvider
     * @covers       Lunr\Spark\Facebook\Page::set_fields
     */
    public function testSetFieldsWithNonArrayDoesNotSetFields($value)
    {
        $this->class->set_fields($value);

        $this->assertArrayEmpty($this->get_reflection_property_value('fields'));
    }

    /**
     * Test that set_fields() sets check_permissions to TRUE if access_token is requested.
     *
     * @covers Lunr\Spark\Facebook\Page::set_fields
     */
    public function testSetFieldsWithAccessTokenFieldSetsCheckPermissionsTrue()
    {
        $fields = [ 'email', 'first_name', 'access_token' ];

        $this->class->set_fields($fields);

        $this->assertTrue($this->get_reflection_property_value('check_permissions'));
    }

    /**
     * Test that set_fields() sets check_permissions to FALSE if access_token is not requested.
     *
     * @covers Lunr\Spark\Facebook\Page::set_fields
     */
    public function testSetFieldsWithoutAccessTokenFieldSetsCheckPermissionsFalse()
    {
        $fields = [ 'email', 'first_name' ];

        $this->class->set_fields($fields);

        $this->assertFalse($this->get_reflection_property_value('check_permissions'));
    }

}

?>
