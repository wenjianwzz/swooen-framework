<?php
/**
 */
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Connection;
use Swooen\Communication\Route\Handler\HandlerFactory;
use Swooen\Communication\Route\Hook\HandlerHook;
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
        $handler = $this->make(Handler::class);
        $writer = $this->make(Writer::class);
        try {
            $factory = $this->make(\Swooen\Communication\ConnectionFactory::class);
            assert($factory instanceof \Swooen\Communication\ConnectionFactory);
            $router = Router::makeByContainer($this);
            $handlerFactory = $this->make(HandlerFactory::class);
            assert($handlerFactory instanceof HandlerFactory);
        } catch (\Throwable $t) {
            $handler->report($t, $logger);
            $handler->render($t, $writer);
        }
        $factory->onConnection(function(Connection $conn) use ($logger, $router, $handlerFactory) {
            // 连接建立完成，开始使用连接的错误处理
            $handler = $conn->has(Handler::class)?$conn->get(Handler::class):$this->make(Handler::class);
            $writer = $conn->getWriter();
            $reader = $conn->getReader();
            while ($reader->hasNext()) {
                try {
                    $package = $reader->next();
                    $route = $router->dispatch($package);
                    $action = $route->getAction();
                    $handlerContext = $handlerFactory->createContext($this, $conn, $route, $router, $package, $writer);
                    /**
                     * @var HandlerHook[]
                     */
                    $hookers = array_map([$handlerContext, 'make'], $route->getHooks());
                    foreach($hookers as $hooker) {
                        $package = $hooker->before($handlerContext, $route, $package, $conn);
                        if (empty($package)) {
                            // 退出处理流程
                            break;
                        }
                    }
                    if ($package) {
                        $action = $handlerFactory->parse($action);
                        $returnPackage = $handlerContext->call($action, $route->getParams());
                        for ($i = count($hookers)-1; $i >= 0; --$i) {
                            $hooker = $hookers[$i];
                            $returnPackage = $hooker->after($handlerContext, $route, $conn, $returnPackage);
                        }
                        if ($returnPackage) {
                            $conn->getWriter()->send($returnPackage);
                        }
                    }
                } catch (\Throwable $t) {
                    $handler->report($t, $logger);
                    $handler->render($t, $writer);
                }
            }
        });
        $factory->start();
    }
}
