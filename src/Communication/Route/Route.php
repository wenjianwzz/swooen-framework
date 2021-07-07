<?php
namespace Swooen\Communication\Route;
use Swooen\Communication\Route\Hook\HandlerHook;
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

    protected $hooks = [];

    public function __construct($path, $action) {
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
    public function setParam($name, $value) : self {
        $this->params[$name] = $value;
        return $this;
    }

    public function getParam($name, $default=NULL) {
        return isset($this->params[$name])?$this->params[$name]:$default;
    }

    public function getParams() {
        return $this->params;
    }


    /**
     * Get the value of action
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Get the value of path
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Get the value of hooks
     * @return string[]
     */
    public function getHooks() {
        return $this->hooks;
    }

    /**
     * Set the value of hooks
     * @param string[] $hooks
     */
    public function setHooks($hooks): self {
        $this->hooks = $hooks;
        return $this;
    }
}
