<?php

/**
 * This file contains the CentralAuthenticationStoreBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Spark\Tests;

/**
 * This class contains tests for the CentralAuthenticationStore class.
 *
 * @covers Lunr\Spark\CentralAuthenticationStore
 */
class CentralAuthenticationStoreBaseTest extends CentralAuthenticationStoreTestCase
{

    /**
     * Test that the store is an empty array by default.
     */
    public function testStoreIsEmptyByDefault(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('store'));
    }

    /**
     * Test add() creates the module index in the store if it does not yet exist.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::add
     */
    public function testAddCreatesModuleIndexIfNotExists(): void
    {
        $this->class->add('module', 'key', 'value');

        $this->assertArrayHasKey('module', $this->getReflectionPropertyValue('store'));
    }

    /**
     * Test add() adds a new value to the store.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::add
     */
    public function testAddAddsNewValue(): void
    {
        $this->class->add('module', 'key', 'value');

        $module = $this->getReflectionPropertyValue('store')['module'];

        $this->assertArrayHasKey('key', $module);
        $this->assertEquals('value', $module['key']);
    }

    /**
     * Test add() overwrites the old value in the store.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::add
     */
    public function testAddOverwritesOldValue(): void
    {
        $this->setReflectionPropertyValue('store', [ 'module' => [ 'key' => 'value1' ] ]);

        $this->class->add('module', 'key', 'value');

        $module = $this->getReflectionPropertyValue('store')['module'];

        $this->assertArrayHasKey('key', $module);
        $this->assertEquals('value', $module['key']);
    }

    /**
     * Test delete() removes a value from the store.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::delete
     */
    public function testDeleteUnsetsExistingIndex(): void
    {
        $this->setReflectionPropertyValue('store', [ 'module' => [ 'key' => 'value' ] ]);

        $this->class->delete('module', 'key');

        $module = $this->getReflectionPropertyValue('store')['module'];

        $this->assertArrayEmpty($module);
    }

    /**
     * Test delete() does not modify the store when the index requested to be deleted does not exist.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::delete
     */
    public function testDeleteDoesNothingWhenIndexDoesNotExist(): void
    {
        $this->setReflectionPropertyValue('store', [ 'module' => [ 'key' => 'value' ] ]);

        $before = $this->getReflectionPropertyValue('store');

        $this->class->delete('module', 'key1');

        $after = $this->getReflectionPropertyValue('store');

        $this->assertSame($before, $after);
    }

    /**
     * Test get() returns NULL when the module index does not exist.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::get
     */
    public function testGetReturnsNullWhenModuleIndexDoesNotExist(): void
    {
        $this->assertNull($this->class->get('module', 'key'));
    }

    /**
     * Test get() returns NULL when the index does not exist.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::get
     */
    public function testGetReturnsNullWhenIndexDoesNotExist(): void
    {
        $this->setReflectionPropertyValue('store', [ 'module' => [ 'key' => 'value' ] ]);

        $this->assertNull($this->class->get('module', 'key1'));
    }

    /**
     * Test get() returns the value when the index does exist.
     *
     * @covers Lunr\Spark\CentralAuthenticationStore::get
     */
    public function testGetReturnsValueWhenIndexExists(): void
    {
        $this->setReflectionPropertyValue('store', [ 'module' => [ 'key' => 'value' ] ]);

        $this->assertEquals('value', $this->class->get('module', 'key'));
    }

}

?>
