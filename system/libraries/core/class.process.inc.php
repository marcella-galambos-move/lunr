<?php

/**
 * Process control class
 * @author M2Mobi, Heinz Wiesinger
 */
class Process
{

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Destructor
     */
    public function __destruct()
    {

    }

    /**
     * Start multiple parallel child processes
     * WARNING: make sure to not reuse an already existing DB-Connection established by the parent
     * in the children. Best would be to not establish a DB-Connection in the parent at all.
     * WARNING: Do NOT use this for a web process!!!!
     * @param Integer $number Amount of child processes to start
     * @param Mixed $call The call that should be executed by the child processes
     *      either "function" or "array('class','method')"
     * @param Array $data Prepared array of data to be processed by the child processes
     *      this array should be size() = $number
     * @return Mixed Either false if run out of CLI context or an array of child process statuses
     */
    public static function fork($number, $call, &$data)
    {
        global $cli;
        if ($cli)
        {
            for($i = 0; $i < $number; ++$i)
            {
                $pids[$i] = pcntl_fork();

                if(!$pids[$i])
                {
                    // child process
                    if (is_array($data[$i]))
                    {
                        call_user_func_array($call,$data[$i]);
                    }
                    elseif (is_object($data[$i]) && ($data[$i] instanceof SplFixedArray))
                    {
                        call_user_func_array($call,$data[$i]->toArray());
                    }
                    else
                    {
                        call_user_func($call,$data[$i]);
                    }
                    exit();
                }
            }

            $result = new SplFixedArray($number);

            for($i = 0; $i < $number; ++$i)
            {
                pcntl_waitpid($pids[$i], $status, WUNTRACED);
                $result[$i] = $status;
            }

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

}

?>