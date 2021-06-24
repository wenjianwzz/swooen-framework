<?php
namespace Swooen\Http\Routes;

/**
 * 路由
 * 
 * @author WZZ
 *        
 */
class Route {

    protected $middlewares = [];

    protected $method = [];

    protected $uri = '';

    protected $action;

    protected $providers;

    protected $params = [];

    protected $corsEnable = false;

    protected $corsHeaders = [];

    public function __construct($method, $uri, $action, $middlewares = [], $providers = []) {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
        $this->middlewares = $middlewares;
        $this->providers = $providers;
    }

    public static function create($method, $uri, $action, $middlewares = [], $providers = []): self {
        return new static($method, $uri, $action, $middlewares, $providers);
    }

    /**
     * Get the value of middlewares
     */ 
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Get the value of method
     */ 
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the value of uri
     */ 
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get the value of action
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the value of providers
     */ 
    public function getProviders()
    {
        return $this->providers;
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

    public function param($name, $default=NULL) {
        return isset($this->params[$name])?$this->params[$name]:$default;
    }

    /**
     * Get the value of corsHeaders
     */ 
    public function getCorsHeaders()
    {
        return $this->corsHeaders;
    }

    /**
     * Set the value of corsHeaders
     *
     * @return  self
     */ 
    public function setCorsHeaders($corsHeaders)
    {
        $this->corsHeaders = $corsHeaders;

        return $this;
    }

    /**
     * Set the value of corsEnable
     *
     * @return  self
     */ 
    public function enableCors() {
        $this->corsEnable = true;
        return $this;
    }

    /**
     * Set the value of corsEnable
     *
     * @return  self
     */ 
    public function disableCors() {
        $this->corsEnable = false;
        return $this;
    }

    /**
     * Get the value of corsEnable
     */ 
    public function isCorsEnable()
    {
        return $this->corsEnable;
    }
}
