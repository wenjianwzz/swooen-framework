<?php
namespace Swooen\Batis\Pool;

class PDOConfig {

    protected $host;

    protected $port;

    protected $db;

    protected $user;

    protected $password;

    protected $charset;

    protected $pdotype;

    public function __construct($host, $port, $user, $password, $db, $charset, $pdotype='mysql') {
        $this->host = $host;
        $this->port = $port;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
        $this->charset = $charset;
        $this->pdotype = $pdotype;
    }

    public function getDSN() {
        return "{$this->pdotype}:host={$this->host};port={$this->port};dbname={$this->db};charset={$this->charset}";
    }

    /**
     * Set the value of host
     */
    public function setHost($host): self {
        $this->host = $host;
        return $this;
    }


    /**
     * Set the value of port
     */
    public function setPort($port): self {
        $this->port = $port;
        return $this;
    }


    /**
     * Set the value of db
     */
    public function setDb($db): self {
        $this->db = $db;
        return $this;
    }


    /**
     * Set the value of user
     */
    public function setUser($user): self {
        $this->user = $user;
        return $this;
    }


    /**
     * Set the value of password
     */
    public function setPassword($password): self {
        $this->password = $password;
        return $this;
    }


    /**
     * Set the value of charset
     */
    public function setCharset($charset): self {
        $this->charset = $charset;
        return $this;
    }

    /**
     * Get the value of host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the value of port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Get the value of db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of charset
     */
    public function getCharset()
    {
        return $this->charset;
    }
}
