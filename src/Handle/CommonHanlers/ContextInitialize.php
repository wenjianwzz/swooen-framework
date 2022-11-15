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
use Wenjianwzz\Tool\Util\Str;

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
            $requestId = time().'-'. Str::random(5);
            $context->instance('REQUES_ID', $requestId);
            if ($config->has('logging')) {
                $provider = $this->app->make(LogProvider::class);
                $context->provider($provider);
            }
            if ($context->has(\Monolog\Logger::class)) {
                $context->call(function(\Monolog\Logger $logger, $requestId) {
                    $logger->pushProcessor(function($record) use ($requestId) {
                        $record['extra'] = array_merge($record['extra'], compact('requestId'));
                        return $record;
                    });
                }, compact('requestId'));
            }
        } catch (\Throwable $t) {
        }
        $next($context, $package, $writer);
    }

}
