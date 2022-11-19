<?php
namespace Swooen\Handle\CommonHanlers;

use Swooen\Application;
use Swooen\Config\ConfigRepository;
use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Package\Package;
use Swooen\Handle\Writer\Writer;
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

    protected function init(HandleContext $context, Package $package, Writer $writer) {
        $config = $this->config;
        if (!$context->has(ConfigRepository::class)) {
            $context->instance(ConfigRepository::class, $config);
        }
        $requestId = time().'-'. Str::random(5);
        $context->instance('REQUES_ID', $requestId);
        $providers = $config->get('app.handle.context.providers', []);
        foreach ($providers as $provider) {
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
    }

    public function handle(HandleContext $context, Package $package, Writer $writer, ?callable $next) {
        try {
            $this->init($context, $package, $writer);
        } catch (\Throwable $t) {
        }
        $next($context, $package, $writer);
    }

}
