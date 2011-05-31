<?php
require_once './i5Conn.php';
class i5Pgm extends i5Conn{

    /**
     * resource from i5_program_prepare
     * @var Program
     */
    protected $pgm;

    /**
     * Open program resource
     * @param string $pgm real name of libray and program. For example, 'YOURLIB/YOURPGM'
     * @param array $description defines input/output parameters
     * @throws Exception
     */
    function __construct($pgm, $description = array()) {
        if(!isset($this->pgm) || !is_resource($this->pgm)){
            if(!isset(self::$conn)){
                try {
                    parent::__construct();
                } catch (Exception $e) {
                    throw new Exception($e);
                }
            }
            $program = i5_program_prepare($pgm, $description, self::$conn);
            if(!$program){
                throw new Exception(i5_errormsg(), i5_errno());
            }
            $this->pgm = $program;
        }
    }

    function __destruct() {
        $this->close();
        parent::__destruct();
    }

    /**
     * Close program resource
     * @throws Exception
     */
    function close() {
        if(isset($this->pgm) && is_resource($this->pgm)){
            $ret = i5_program_close($this->pgm);
            if(!$ret){
                throw new Exception(i5_errormsg(), i5_errno());
            }
            unset($this->pgm);
        }
    }

    /**
     * Call program
     * @param array $in input parameters
     * @param array $out output parameters
     * @throws Exception
     * @return array
     */
    function call($in = array(), $out = array()) {
        if(!isset($this->pgm) || !is_resource($this->pgm)){
            throw new Exception('Program is not defined');
        }
        $ret = i5_program_call($this->pgm, $in, $out);
        if(!$ret){
            throw new Exception(i5_errormsg(), i5_errno());
        }
        $retValue = array();
        foreach ($out as $outKey => $outValue) {
            $retValue[$outValue] = $$outValue;
        }
        return $retValue;
    }
}