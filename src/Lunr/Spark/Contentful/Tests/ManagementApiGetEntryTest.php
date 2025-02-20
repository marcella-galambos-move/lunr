<?php

/**
 * This file contains the ManagementApiGetEntryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2015 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Spark\Contentful\Tests;

use WpOrg\Requests\Exception\Http\Status400 as RequestsExceptionHTTP400;
use WpOrg\Requests\Requests;

/**
 * This class contains the tests for the ManagementApi.
 *
 * @covers Lunr\Spark\Contentful\ManagementApi
 */
class ManagementApiGetEntryTest extends ManagementApiTestCase
{

    /**
     * Test that get_entry() if there was a request error.
     *
     * @covers Lunr\Spark\Contentful\ManagementApi::get_entry
     */
    public function testGetEntryOnRequestError(): void
    {
        $this->cache->expects($this->once())
                    ->method('getItem')
                    ->with('contentful.management_token')
                    ->willReturn($this->item);

        $this->item->expects($this->once())
                   ->method('get')
                   ->willReturn('Token');

        $url     = 'https://api.contentful.com/entries/123456';
        $headers = [ 'Authorization' => 'Bearer Token' ];

        $this->http->expects($this->once())
                   ->method('request')
                   ->with($url, $headers, [], Requests::GET)
                   ->willReturn($this->response);

        $this->response->expects($this->once())
                       ->method('throw_for_status')
                       ->willThrowException(new RequestsExceptionHTTP400('Bad request', $this->response));

        $this->logger->expects($this->once())
                     ->method('warning')
                     ->with(
                         'Contentful API Request ({request}) failed: {message}',
                         [ 'message' => '400 Bad request', 'request' => $url ]
                     );

        $this->expectException('WpOrg\Requests\Exception\Http\Status400');
        $this->expectExceptionMessage('Bad request');

        $this->class->get_entry('123456');
    }

    /**
     * Test that get_entry() on success
     *
     * @covers Lunr\Spark\Contentful\ManagementApi::get_entry
     */
    public function testGetEntryOnSuccessfulRequest(): void
    {
        $this->cache->expects($this->once())
                    ->method('getItem')
                    ->with('contentful.management_token')
                    ->willReturn($this->item);

        $this->item->expects($this->once())
                   ->method('get')
                   ->willReturn('Token');

        $url     = 'https://api.contentful.com/entries/123456';
        $headers = [ 'Authorization' => 'Bearer Token' ];

        $this->http->expects($this->once())
                   ->method('request')
                   ->with($url, $headers, [], Requests::GET)
                   ->willReturn($this->response);

        $this->response->status_code = 200;

        $body = [
            'fields' => [ 'id' => '123456' ],
        ];

        $this->response->body = json_encode($body);

        $result = $this->class->get_entry('123456');

        $this->assertSame($body, $result);
    }

}

?>
