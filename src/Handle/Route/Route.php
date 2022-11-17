<?php
namespace Swooen\Handle\Route;

/**
 * 路由
 * 
 * @author WZZ
 *        
 */
class Route {

    protected $path;

    protected $actions;

    protected $params = [];

    protected $metas = [];

    public function __construct($path, array $actions, array $metas=[], array $initParams=[]) {
        $this->path = $path;
        $this->actions = $actions;
        $this->metas = $metas;
        $this->params = $initParams;
    }

    public static function create($path, array $actions, array $metas=[], array $initParams=[]): self {
        return new static($path, $actions, $metas, $initParams);
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

    /**
     * Set the value of params
     *
     * @return  self
     */ 
    public function setParams(array $params) {
        $this->params = array_merge($this->params, $params);
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
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Get the value of path
     */
    public function getPath() {
        return $this->path;
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
