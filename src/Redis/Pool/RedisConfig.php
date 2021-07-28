<?php
namespace Swooen\Redis\Pool;

class RedisConfig {

    protected $host;

    protected $port;

    protected $dbIndex;

    protected $auth;

    protected $timeout;

    protected $readTimeout;

    public function __construct($host, $port, $dbIndex, $auth, $timeout=-1, $readTimeout=600) {
        $this->host = $host;
        $this->port = $port;
        $this->dbIndex = $dbIndex;
        $this->auth = $auth;
        $this->timeout = $timeout;
        $this->readTimeout = $readTimeout;
    }

    public function getHost() {
        return $this->host;
    }

    public function getPort() {
        return $this->port;
    }

    public function getDbIndex() {
        return $this->dbIndex;
    }

    public function getAuth() {
        return $this->auth;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function getReadTimeout() {
        return $this->readTimeout;
    }

}
