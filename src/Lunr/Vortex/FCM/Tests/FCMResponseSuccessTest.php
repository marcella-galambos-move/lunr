<?php

/**
 * This file contains the FCMResponseSuccessTest class.
 *
 * PHP Version 5.4
 *
 * @package    Lunr\Vortex\FCM
 * @author     Dinos Theodorou <dinos@m2mobi.com>
 * @copyright  2013-2016, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Vortex\FCM\Tests;

use Lunr\Vortex\PushNotificationStatus;

/**
 * This class contains tests for successful FCM dispatches.
 *
 * @covers Lunr\Vortex\FCM\FCMResponse
 */
class FCMResponseSuccessTest extends FCMResponseTest
{

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        parent::setUpSuccess();
    }

    /**
     * Test that the status is set as error.
     */
    public function testStatusIsError()
    {
        $this->assertSame(PushNotificationStatus::SUCCESS, $this->get_reflection_property_value('status'));
    }

    /**
     * Test that the http code is set from the Response object.
     */
    public function testHttpCodeIsSetCorrectly()
    {
        $this->assertSame(200, $this->get_reflection_property_value('http_code'));
    }

    /**
     * Test parse_gcm_failures() parses a response with a failure.
     *
     * @covers Lunr\Vortex\FCM\FCMResponse::parse_gcm_failures
     */
    public function testParseFCMFailuresWithOneFailure()
    {
        $file = file_get_contents(TEST_STATICS . '/Vortex/gcm_response_error.json');

        $this->set_reflection_property_value('result', $file);

        $method = $this->get_accessible_reflection_method('parse_gcm_failures');
        $result = $method->invoke($this->class);

        $this->assertArrayHasKey('messages', $result);
    }

    /**
     * Test parse_gcm_failures() parses a response without a failure.
     *
     * @covers Lunr\Vortex\FCM\FCMResponse::parse_gcm_failures
     */
    public function testParseFCMFailuresWithoutFailure()
    {
        $file = file_get_contents(TEST_STATICS . '/Vortex/gcm_response.json');

        $this->set_reflection_property_value('result', $file);

        $method = $this->get_accessible_reflection_method('parse_gcm_failures');
        $result = $method->invoke($this->class);

        $this->assertEquals(1, count($result));
    }

}

?>
