<?php
namespace Swooen\Communication\Route\Loader;

/**
 * 路由加载器
 * @author WZZ
 *        
 */
class PHPFileLoader extends ArrayLoader {

    public function __construct(string ...$files) {
        parent::__construct();
        array_walk($files, [$this, 'add']);
    }
    
    public function add($path) {
        array_push($this->routes, ...require $path);
    }

    public function getRoutes() {
        return $this->routes;
    }
}
