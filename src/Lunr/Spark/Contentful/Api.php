<?php

/**
 * This file contains low level API methods for Contentful.
 *
 * SPDX-FileCopyrightText: Copyright 2015 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Spark\Contentful;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use WpOrg\Requests\Session;

/**
 * Low level Contentful API methods for Spark
 *
 * @property string $access_token     The access token for Contentful
 * @property string $management_token The management token for Contentful
 */
class Api
{

    /**
     * Contentful URL.
     * @var string
     */
    protected const URL = 'https://www.contentful.com';

    /**
     * Shared instance of the credentials cache
     * @var CacheItemPoolInterface
     */
    protected readonly CacheItemPoolInterface $cache;

    /**
     * Shared instance of a Logger class.
     * @var LoggerInterface
     */
    protected readonly LoggerInterface $logger;

    /**
     * Shared instance of the Requests\Session class.
     * @var Session
     */
    protected readonly Session $http;

    /**
     * Space ID
     * @var string
     */
    protected string $space;

    /**
     * Environment
     * @var string
     */
    protected string $environment;

    /**
     * Constructor.
     *
     * @param CacheItemPoolInterface $cache  Shared instance of the credentials cache
     * @param LoggerInterface        $logger Shared instance of a Logger class.
     * @param Session                $http   Shared instance of the Requests\Session class.
     */
    public function __construct(CacheItemPoolInterface $cache, LoggerInterface $logger, Session $http)
    {
        $this->cache       = $cache;
        $this->logger      = $logger;
        $this->http        = $http;
        $this->space       = '';
        $this->environment = '';
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->space);
        unset($this->environment);
    }

    /**
     * Get access to shared credentials.
     *
     * @param string $key Credentials key
     *
     * @return string|null Value of the chosen key
     */
    public function __get(string $key): ?string
    {
        switch ($key)
        {
            case 'access_token':
            case 'management_token':
                return $this->cache->getItem('contentful.' . $key)->get();
            default:
                return NULL;
        }
    }

    /**
     * Set shared credentials.
     *
     * @param string $key   Key name
     * @param string $value Value to set
     *
     * @return void
     */
    public function __set(string $key, string $value): void
    {
        switch ($key)
        {
            case 'access_token':
            case 'management_token':
                $item = $this->cache->getItem('contentful.' . $key);

                $item->expiresAfter(600);
                $item->set($value);

                $this->cache->save($item);
                break;
            default:
                break;
        }
    }

    /**
     * Set contentful space ID.
     *
     * @param string $space_id Content space ID
     *
     * @return static $self Self Reference
     */
    public function set_space_id(string $space_id): static
    {
        $this->space = $space_id;

        return $this;
    }

    /**
     * Set contentful environment.
     *
     * @param string $environment Content environment
     *
     * @return static $self Self Reference
     */
    public function set_environment(string $environment): static
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    protected function get_base_url(): string
    {
        $url = static::URL;

        if (!empty($this->space))
        {
            $url .= '/spaces/' . $this->space;
        }

        if (!empty($this->environment))
        {
            $url .= '/environments/' . $this->environment;
        }

        return $url;
    }

}

?>
