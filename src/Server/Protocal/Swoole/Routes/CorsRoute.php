<?php
namespace Swooen\Http\Routes;

/**
 * Cors路由
 * 
 * @author WZZ
 *        
 */
class CorsRoute extends Route {

    protected $corsMethod = [];
    
    /**
     * Get the value of corsHeaders
     */ 
    public function getCorsHeaders() {
        return $this->corsHeaders;
    }

    /**
     * Set the value of corsHeaders
     *
     * @return  self
     */ 
    public function setCorsHeaders($corsHeaders) {
        $this->corsHeaders = array_unique(array_merge($this->corsHeaders, $corsHeaders));
        return $this;
    }

    /**
     * Get the value of corsMethod
     */ 
    public function getCorsMethod() {
        return $this->corsMethod;
    }

    /**
     * Set the value of corsMethod
     *
     * @return  self
     */ 
    public function setCorsMethod($corsMethod) {
        $this->corsMethod = array_unique(array_merge($this->corsMethod, $corsMethod));;
        return $this;
    }
}
