<?php

/**
 * This file contains an extension for the base controller
 * class, which implements additional features specifically
 * required by the CLI.
 *
 * PHP Version 5.3
 *
 * @category   Libraries
 * @package    Core
 * @subpackage Libraries
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2010-2012, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Libraries\Core;

/**
 * CLI Controller class
 *
 * @category   Libraries
 * @package    Core
 * @subpackage Libraries
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 */
abstract class CliController implements ControllerInterface
{

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    /**
     * Destructor.
     */
    public function __destruct()
    {

    }

    /**
     * Handle unimplemented calls.
     *
     * @param String $name      Method name
     * @param array  $arguments Arguments passed to the method
     *
     * @return String JSON encoded error String
     */
    public function __call($name, $arguments)
    {
        return "not implemented!\n";
    }

    /**
     * Default method as defined in conf.application.inc.php.
     *
     * @return void
     */
    abstract public function index();

}

?>
