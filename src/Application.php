<?php
/**
 */
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Handler\HandlerFactory;
use Swooen\Communication\Route\Hook\HandlerHook;
use Swooen\Communication\Route\Router;
use Swooen\Communication\StdoutWriter;
use Swooen\Communication\Writer;
use Swooen\Container\Container;
use Swooen\Exception\Handler;
use Swooen\Server\Booter;

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
    public function run(Booter $booter) {
        $logger = $this->has(LoggerInterface::class)?$this->get(LoggerInterface::class):null;
        $writer = $this->make(Writer::class);
        try {
            $factory = $this->make(\Swooen\Communication\ConnectionFactory::class);
            assert($factory instanceof \Swooen\Communication\ConnectionFactory);
            $router = Router::makeByContainer($this);
            $handlerFactory = $this->make(HandlerFactory::class);
            assert($handlerFactory instanceof HandlerFactory);
        } catch (\Throwable $t) {
            $errHandler = $this->make(Handler::class);
            $errHandler->report($t, $logger);
            $errHandler->render($t, $writer);
            throw $t;
        }
        $factory->onConnection(function(Connection $conn) use ($logger, $router, $handlerFactory) {
            $writer = $conn->getWriter();
            $conn->listenPackage(function(Package $package, Connection $connection) use ($router, $writer, $handlerFactory, $logger) {
                try {
                    $route = $router->dispatch($package);
                    $action = $route->getAction();
                    $handlerContext = $handlerFactory->createContext($this, $connection, $route, $router, $package, $writer);
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
                        $action = $handlerFactory->parse($action);
                        $returnPackage = $handlerContext->call($action, $route->getParams());
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
            echo_memory_usage();
        });
    }
}
