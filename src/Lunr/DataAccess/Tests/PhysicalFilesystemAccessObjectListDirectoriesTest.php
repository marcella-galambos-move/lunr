<?php

/**
 * This file contains the PhysicalFilesystemAccessObjectListDirectoriesTest class.
 *
 * PHP Version 5.4
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\DataAccess\Tests;

use Lunr\DataAccess\PhysicalFilesystemAccessObject;

/**
 * This class contains tests for directory related methods in the PhysicalFilesystemAccessObject.
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\DataAccess\PhysicalFilesystemAccessObject
 */
class PhysicalFilesystemAccessObjectListDirectoriesTest extends PhysicalFilesystemAccessObjectTest
{

    /**
     * Test listing directories in an accessible directory.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    public function testGetListOfDirectoriesInAccessibleDirectory()
    {
        $expected = [ 'folder1', 'folder2' ];

        $value = $this->class->get_list_of_directories($this->find_location);

        $this->assertInternalType('array', $value);

        sort($value);

        $this->assertEquals($expected, $value);
    }

    /**
     * Test listing directories in an inaccessible directory.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    public function testGetListOfDirectoriesInInaccessibleDirectory()
    {
        $directory = '/root';

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with("Couldn't open directory '{directory}': {message}",
                        array('message'   => "DirectoryIterator::__construct($directory): failed to open dir: Permission denied",
                              'directory' => $directory
                        )
                     );

        $value = $this->class->get_list_of_directories($directory);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

    /**
     * Test listing directories in an non-existant directory.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    public function testGetListOfDirectoriesInNonExistantDirectory()
    {
        $directory = '/tmp56474q';

        $error = "DirectoryIterator::__construct($directory): failed to open dir: No such file or directory";

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with("Couldn't open directory '{directory}': {message}",
                        array('message'   => $error,
                              'directory' => $directory
                        )
                     );

        $value = $this->class->get_list_of_directories($directory);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

    /**
     * Test listing directories in a file.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    public function testGetListOfDirectoriesInFile()
    {
        $directory = tempnam('/tmp', 'phpunit_');;

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with("Couldn't open directory '{directory}': {message}",
                        array('message'   => "DirectoryIterator::__construct($directory): failed to open dir: Not a directory",
                              'directory' => $directory
                        )
                     );

        $value = $this->class->get_list_of_directories($directory);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

    /**
     * Test listing directories in an invalid directory.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    Public function testGetListOfDirectoriesInNullDirectory()
    {
        $this->logger->expects($this->once())
                     ->method('warning')
                     ->with("{message}", array('message' => 'Directory name must not be empty.'));

        $value = $this->class->get_list_of_directories(NULL);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

    /**
     * Test listing directories in an invalid directory.
     *
     * @covers Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    Public function testGetListOfDirectoriesInObjectDirectory()
    {
        $directory = new \stdClass();

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with("Couldn't open directory '{directory}': {message}",
                        array('message'   => "DirectoryIterator::__construct() expects parameter 1 to be string, object given",
                              'directory' => $directory
                        )
                     );

        $value = $this->class->get_list_of_directories($directory);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

    /**
     * Test listing directories in an boolean directory.
     *
     * @dataProvider booleanNameProvider
     * @covers       Lunr\DataAccess\PhysicalFilesystemAccessObject::get_list_of_directories
     */
    Public function testGetListOfDirectoriesInBooleanDirectory($directory)
    {
        $this->logger->expects($this->never())
                     ->method('error');

        $value = $this->class->get_list_of_directories($directory);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

}

?>
