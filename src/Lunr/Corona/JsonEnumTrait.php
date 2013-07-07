<?php

/**
 * This file contains a trait for handling json enums.
 *
 * PHP Version 5.4
 *
 * @category   Libraries
 * @package    Corona
 * @subpackage Enums
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Corona;

/**
 * Date/Time related functions
 *
 * @category   Libraries
 * @package    Corona
 * @subpackage Enums
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 */
trait JsonEnumTrait
{

    /**
     * Set of json enums.
     * @var array
     */
    protected $json;

    /**
     * Store a set of json enums.
     *
     * @param array &$enums An array of json enums
     *
     * @return void
     */
    public function set_json_enums(&$enums)
    {
        $this->json =& $enums;
    }

}

?>
