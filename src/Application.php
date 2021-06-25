<?php
/**
 */
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Communication\StdoutWriter;
use Swooen\Communication\Writer;
use Swooen\Container\Container;
use Swooen\Exceptions\Handler;

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
            $package = $conn->next();
            try {
                
            } catch (\Throwable $t) {
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
