<?php
namespace Swooen\Communication\Route;

use Swooen\Communication\Route\Handler\HandlerFactory;

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

    protected $metas = [];

    protected $handlerFactory = HandlerFactory::class;

    public function __construct($path, $action, $hooks=[], $metas=[], $initParams=[]) {
        $this->path = $path;
        $this->action = $action;
        $this->hooks = $hooks;
        $this->metas = $metas;
        $this->params = $initParams;
    }

    /**
     * 使用指定的处理器工厂
     */
    public function withFactory($factoryClass): self {
        $this->handlerFactory = $factoryClass;
        return $this;
    }

    public function getFactory(): string {
        return $this->handlerFactory;
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

    /**
     * @return  self
     */ 
    public function setMetas($metas) {
        $this->metas = $metas;
        return $this;
    }

    /**
     * 添加参数
     */
    public function setMeta($name, $value) : self {
        $this->metas[$name] = $value;
        return $this;
    }

    public function getMeta($name, $default=NULL) {
        return isset($this->metas[$name])?$this->metas[$name]:$default;
    }

    /**
     * @return array
     */
    public function getMetas() {
        return $this->metas;
    }

}
