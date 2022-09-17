<?php
/**
 */
namespace Swooen\Runtime;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Handler\HandlerContext;
use Swooen\Communication\Route\Handler\HandlerFactory;
use Swooen\Communication\Route\Hook\HandlerContextHook;
use Swooen\Communication\Route\Hook\HandlerHook;
use Swooen\Communication\Route\Route;
use Swooen\Communication\Route\Router;
use Swooen\Communication\StdoutWriter;
use Swooen\Communication\Writer;
use Swooen\Container\Container;
use Swooen\Exception\Handler;
use Swooen\Runtime\Booter;

class RuntimeContext extends Container {
    
    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

    public function basePath($path = null) {
        return $this->basePath.($path ? '/'.$path : $path);
    }

    /**
     * @return HandlerContext
     */
    protected function createHandlerContext(Connection $connection, Route $route, Router $router, Package $package, Writer $writer) {
        if ($this->has(HandlerContext::class)) {
            $context = $this->make(HandlerContext::class);
        } else {
            $context = HandlerContext::create();
        }
        $context->instance(Application::class, $this);
        $context->instance(\Swooen\Container\Container::class, $context);
        $context->instance(Connection::class, $connection);
        $context->instance(Route::class, $route);
        $context->instance(Router::class, $router);
        $context->instance(Package::class, $package);
        $context->instance(Writer::class, $writer);
        $context->instance(HandlerContext::class, $context);
        if ($this->has(HandlerContextHook::class)) {
            $this->make(HandlerContextHook::class)->onCreate($context);
        }
        return $context;
    }

    /**
     * 启动服务，开始监听并处理数据
     */
    public function run(Booter $booter) {
        $logger = $this->has(LoggerInterface::class)?$this->get(LoggerInterface::class):null;
        $writer = $this->make(Writer::class);
        try {
            $factory = $this->make(\Swooen\Communication\ConnectionFactory::class);
            assert($factory instanceof \Swooen\Communication\ConnectionFactory);
            $router = Router::makeByContainer($this);
        } catch (\Throwable $t) {
            $errHandler = $this->make(Handler::class);
            $errHandler->report($t, $logger);
            $errHandler->render($t, $writer);
            throw $t;
        }
        $factory->onConnection(function(Connection $conn) use ($logger, $router) {
            $writer = $conn->getWriter();
            $conn->listenPackage(function(Package $package, Connection $connection) use ($router, $writer, $logger) {
                try {
                    $route = $router->dispatch($package);
                    $handlerContext = $this->createHandlerContext($connection, $route, $router, $package, $writer);
                    $handlerFactory = $handlerContext->make($route->getFactory());
                    assert($handlerFactory instanceof HandlerFactory);
                    /**
                     * @var HandlerHook[]
                     */
                    $hookers = array_map([$handlerContext, 'make'], $route->getHooks());
                    foreach($hookers as $hooker) {
                        $package = $hooker->before($handlerContext, $route, $package, $connection);
                        if (empty($package)) {
                            break;
                        }
                    }
                    if ($package) {
                        $actionHandler = $handlerFactory->parse($route, $package);
                        $returnPackage = $handlerContext->call($actionHandler, $route->getParams());
                        for ($i = count($hookers)-1; $i >= 0; --$i) {
                            $hooker = $hookers[$i];
                            $returnPackage = $hooker->after($handlerContext, $route, $connection, $returnPackage);
                        }
                        if ($returnPackage) {
                            $connection->getWriter()->send($returnPackage);
                        }
                    }
                } catch (\Throwable $t) {
                    try {
                        $errHandler = (isset($handlerContext) && $handlerContext->has(Handler::class))?$handlerContext->get(Handler::class):$this->make(Handler::class);
                        $errHandler->report($t, $logger);
                        $errHandler->render($t, $writer);
                    } catch (\Throwable $th) {
                        try {
                            $writer->end('Critical Failure');
                        } catch (\Throwable $th) {}
                    }
                } finally {
                    if (isset($handlerContext)) {
                        $handlerContext->destroy();
                    }
                }
            });
            if ($conn instanceof \Swooen\Container\Container) {
                // 连接处理结束，摧毁释放资源
                $conn->destroy();
            }
        });
    }
}
