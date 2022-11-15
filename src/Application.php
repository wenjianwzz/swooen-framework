<?php
namespace Swooen;

use Psr\Log\LoggerInterface;
use Swooen\Container\Container;
use Swooen\Server\ServerBooter;

class Application extends Container {

    
	protected $appName = '';
    
    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    protected $resourceDir;

    protected $storagePath;

    protected $configPath;

    public function __construct($appName, $basePath) {
        $this->basePath = rtrim($basePath, '/\\ ');
        $this->resourceDir = $this->basePath('resources');
		empty($appName) and $appName = basename($this->basePath());
		$this->appName = $appName;
		$this->useStoragePath($this->basePath('storage'));
        $this->useConfigPath($this->basePath('config'));
        $this->instance(self::class, $this);
        $this->instance(parent::class, $this);
    }

    public function basePath($path = null) {
        return $this->basePath.($path ? '/'.ltrim($path, '/'): $path);
    }

    public function resourcePath($path = null): string {
        return $this->resourceDir.($path ? '/'.ltrim($path, '/'): $path);
    }
		
	public function getAppName(): string {
		return $this->appName;
	}

	public function useStoragePath(string $path) {
		$this->storagePath = rtrim($path, '/');
	}

	/**
     * 获取文件存储路径
	 */
	public function storagePath($path = null): string {
		return $this->storagePath . ($path ? '/'.ltrim($path, '/'): $path);
	}

	public function useConfigPath(string $path) {
		$this->configPath = rtrim($path, '/');
	}

	/**
     * 获取配置文件路径
	 */
	public function configPath($path): string {
		return $this->configPath . ($path ? '/'.ltrim($path, '/'): $path);
	}

}
