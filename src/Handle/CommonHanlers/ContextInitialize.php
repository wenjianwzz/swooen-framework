<?php
namespace Swooen\Handle\CommonHanlers;

use Psr\Log\LoggerInterface;
use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;
use Swooen\Provider\LogProvider;

/**
 * 初始化上下文
 * @author WZZ
 */
class ContextInitialize extends PackageHandler {

    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var Application
     */
    protected $app;

    public function __construct(ConfigRepository $config, Application $app) {
        $this->config = $config;
        $this->app = $app;
    }

    public function handle(HandleContext $context, Package $package, Writer $writer, ?callable $next) {
        try {
            $config = $this->config;
            $context->instance(ConfigRepository::class, $config);
            if ($config->has('logging')) {
                $provider = $this->app->make(LogProvider::class);
                $context->provider($provider);
            }
        } catch (\Throwable $t) {
        }
        $next($context, $package, $writer);
    }

}
