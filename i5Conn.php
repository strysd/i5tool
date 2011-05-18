<?php
class i5Conn{

    /**
     * resource from i5_connect
     * @var Connection
     */
    protected static $conn;

    /**
     * options for i5_connect
     * @var array
     */
    protected static $options = array(
        //TODO define Your options for i5_connect
        I5_OPTIONS_CODEPAGEFILE => '/usr/local/zendsvr/etc/jp_1399.cpg',
        I5_OPTIONS_INITLIBL => 'YOURLIB'
    );

    /**
     * connection parameters
     * @var string
     */
    private static $host;
    private static $user;
    private static $pass;
    private static $persist;

    /**
     * Open connection
     * @throws Exception
     */
    function __construct() {
        if(!isset(self::$conn) || !is_resource(self::$conn)){
            if(!isset(self::$host)){
                require_once 'Zend/Config/Ini.php';
                $config = new Zend_Config_Ini('setting.ini', 'conn');
                if(!isset($config->host) ||
                   !isset($config->user) ||
                   !isset($config->pass) ){
                    throw new Exception('Basic parameters for i5_connect are undefined');
                }
                self::$host = $config->host;
                self::$user = $config->user;
                self::$pass = $config->pass;
                self::$persist = $config->persist;
            }
            if(self::$persist){
                $connect = i5_pconnect(self::$host, self::$user,
                                       self::$pass, self::$options);
            } else {
                $connect =  i5_connect(self::$host, self::$user,
                                       self::$pass, self::$options);
            }
            if(!$connect){
                throw new Exception(i5_errormsg(), i5_errno());
            }
            self::$conn = $connect;
        }
    }

    /**
     * Close non-persistent connection
     * @see pclose() if You need to close persistent connection
     * @throws Exception
     */
    function __destruct() {
        if(self::$persist){
            return;
        }
        if(isset(self::$conn) && is_resource(self::$conn)){
            $ret = i5_close(self::$conn);
            if(!$ret){
                throw new Exception(i5_errormsg(), i5_errno());
            }
            $this->_clearStatics();
        }
    }

    protected function _clearStatics() {
        self::$conn = null;
        self::$host = null;
        self::$user = null;
        self::$pass = null;
        self::$persist = null;
    }

    /**
     * Close persistent connection
     * @throws Exception
     */
    function pclose() {
        if(!self::$persist){
            throw new Exception('Can not use this function for non-persistent connection');
        }
        if(isset(self::$conn) && is_resource(self::$conn)){
            $ret = i5_pclose(self::$conn);
            if(!$ret){
                throw new Exception(i5_errormsg(), i5_errno());
            }
            $this->_clearStatics();
        }
    }

    /**
     * Check if new connection
     * @throws Exception
     * @return string/int
     * @see i5_get_property()
     */
    function is_newConnection() {
        if(!self::$persist){
            throw new Exception('Can not use this function for non-persistent connection');
        }
        return i5_get_property(I5_NEW_CONNECTION, self::$conn);
    }
}