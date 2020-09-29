<?php
/**
 */
namespace Swooen;

use Swooen\Core\Container;
use Swooen\Exceptions\Handler;
use Swooen\Http\ServePipeline;

class Application extends Container {
    
    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    protected $resourceDir;

    public function __construct($basePath) {
        $this->basePath = $basePath;
        $this->singleton(\Illuminate\Config\Repository::class);
        $this->resourceDir = $this->basePath('resources');
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

    public function basePath($path = null) {
        return $this->basePath.($path ? '/'.$path : $path);
    }

    public function resourcePath($path = null) {
        return $this->resourceDir.($path ? '/'.$path : $path);
    }

    public function configure($name) {
        $path = $this->getConfigurationPath($name);
        if ($path) {
            $this->call(function(\Illuminate\Config\Repository $config, $name, $path) {
                $config->set($name, require $path);
            }, ['path' => $path, 'name' => $name]);
        }
    }

    public function getConfigurationPath($name = null) {
        if (! $name) {
            $appConfigDir = $this->basePath('config').'/';
            if (file_exists($appConfigDir)) {
                return $appConfigDir;
            } elseif (file_exists($path = __DIR__.'/../config/')) {
                return $path;
            }
        } else {
            $appConfigPath = $this->basePath('config').'/'.$name.'.php';
            if (file_exists($appConfigPath)) {
                return $appConfigPath;
            } elseif (file_exists($path = __DIR__.'/../config/'.$name.'.php')) {
                return $path;
            }
        }
    }

	protected function createRouter(\Swooen\Http\Routes\RouteLoader $loader, $allowHost=['localhost(:\d+)?$', '\/\/127\.\d+\.\d+.\d+(:\d+)?$', '\/\/10\.\d+\.\d+.\d+(:\d+)?$', '\/\/192\.168\.\d+.\d+(:\d+)?$']) {
        $router = new \Swooen\Http\Routes\Router();
        $router->setCorsHosts($allowHost);
		foreach ($loader->getRoutes() as $route) {
			$router->addRoute($route);
        }
		return $router;
	}

    /**
     * 处理Http请求
     */
    public function handleHttp(\Swooen\Http\Request $req, Container $context=NULL) {
        \Swooen\Http\Request::setTrustedProxies(['192.168.0.0/16', '172.16.0.0/12', '10.0.0.0/8'], \Swooen\Http\Request::HEADER_X_FORWARDED_ALL);
        empty($context) and $context = $this;
        try {
            $this->call(function(\Swooen\Http\Request $req, \Swooen\Http\Writer\Writer $writer, Container $context, \Swooen\Http\Routes\RouteLoader $loader, \Swooen\Http\RequestContextProvider $provider) {
                $context->instance(\Swooen\Http\Routes\Router::class, $this->createRouter($loader))
                ->instance(\Swooen\Http\Request::class, $req)
                ->provider($provider)
                ->call([new ServePipeline(), 'serve'], []);
            }, ['context' => $context, 'req' => $req]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function handleError(\Throwable $e) {
        try {
            $this->call(function(Handler $handler, \Swooen\Http\Writer\Writer $writer, \Throwable $e) {
                $handler->report($e, $this);
                $handler->render($e, $writer, $this);
            }, ['e' => $e]);
        } catch (\Throwable $e2) {
            // 什么也不干
        }
    }

}
