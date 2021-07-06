<?php
/**
 */
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Route\Handler\HandlerFactory;
use Swooen\Communication\Route\Router;
use Swooen\Communication\StdoutWriter;
use Swooen\Communication\Writer;
use Swooen\Container\Container;
use Swooen\Exception\Handler;

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
        $this->resourceDir = $this->basePath('resources');
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
        // 默认使用标准输出
        $this->bind(Writer::class, StdoutWriter::class);
    }

    public function basePath($path = null) {
        return $this->basePath.($path ? '/'.$path : $path);
    }

    public function resourcePath($path = null) {
        return $this->resourceDir.($path ? '/'.$path : $path);
    }

    /**
     * 启动服务，开始监听并处理数据
     */
    public function run() {
        $logger = $this->has(LoggerInterface::class)?$this->get(LoggerInterface::class):null;
        try {
            $factory = $this->make(\Swooen\Communication\ConnectionFactory::class);
            assert($factory instanceof \Swooen\Communication\ConnectionFactory);
            $conn = $factory->make();
            // 连接建立完成，开始使用连接的错误处理
            $handler = $conn->has(Handler::class)?$conn->get(Handler::class):$this->make(Handler::class);
            $writer = $conn->has(Writer::class)?$conn->get(Writer::class):$this->make(Writer::class);
            $handlerFactory = $conn->has(HandlerFactory::class)?$conn->get(HandlerFactory::class):$this->make(HandlerFactory::class);
            assert($handlerFactory instanceof HandlerFactory);
            $router = Router::makeByContainer($this);
            while ($conn->hasNext()) {
                try {
                    $package = $conn->next();
                    $route = $router->dispatch($package);
                    $action = $route->getAction();
                    $handlerContext = $handlerFactory->createContext($this, $conn, $route, $router);
                    $handlerContext->instance(Writer::class, $writer);
                    $action = $handlerFactory->parse($action);
                    $handlerContext->call($action, $route->getParams());
                } catch (\Throwable $t) {
                    $handler->report($t, $logger);
                    $handler->render($t, $writer);
                }
            }
        } catch (\Throwable $t) {
            try {
                $handler = $this->make(Handler::class);
                $writer = $this->make(Writer::class);
                $handler->report($t, $logger);
                $handler->render($t, $writer);
            } catch (\Throwable $t) {
                // 什么也不干
            }
        }
    }
}
