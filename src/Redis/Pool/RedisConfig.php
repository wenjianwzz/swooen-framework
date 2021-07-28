<?php
namespace Swooen\Redis\Pool;

class RedisConfig {

    protected $host;

    protected $port;

    protected $dbIndex;

    protected $auth;

    protected $timeout;

    protected $readTimeout;

    protected $retryInterval;

    public function __construct($host, $port, $dbIndex, $auth, $timeout=30, $readTimeout=30, $retryInterval=1) {
        $this->host = $host;
        $this->port = $port;
        $this->dbIndex = $dbIndex;
        $this->auth = $auth;
        $this->timeout = $timeout;
        $this->readTimeout = $readTimeout;
        $this->retryInterval = $retryInterval;
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


    /**
     * Get the value of retryInterval
     */
    public function getRetryInterval()
    {
        return $this->retryInterval;
    }

    /**
     * Set the value of retryInterval
     */
    public function setRetryInterval($retryInterval): self
    {
        $this->retryInterval = $retryInterval;

        return $this;
    }
}
