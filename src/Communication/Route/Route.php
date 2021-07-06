<?php
namespace Swooen\Communication\Route;

/**
 * 路由
 * 
 * @author WZZ
 *        
 */
class Route {

    protected $path;

    protected $action;

    protected $params = [];

    public function __construct($path, callable $action) {
        $this->path = $path;
        $this->action = $action;
    }

    public function __clone() {
        $this->params = array_merge($this->params);
    }

    public static function create($path, $action): self {
        return new static($path, $action);
    }

    /**
     * Set the value of params
     *
     * @return  self
     */ 
    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    /**
     * 添加参数
     */
    public function setParam($name, $value) {
        $this->params[$name] = $value;
    }

    public function getParam($name, $default=NULL) {
        return isset($this->params[$name])?$this->params[$name]:$default;
    }


    /**
     * Get the value of action
     */
    public function getAction(): callable {
        return $this->action;
    }

    /**
     * Get the value of path
     */
    public function getPath() {
        return $this->path;
    }
}
