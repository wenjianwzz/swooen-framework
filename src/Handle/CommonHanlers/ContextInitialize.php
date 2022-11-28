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
        $contextId = time().'-'. Str::random(5);
        $context->instance('CONTEXT_ID', $contextId);
        $providers = $config->get('app.handle.context.providers', []);
        foreach ($providers as $provider) {
            $context->provider($provider);
        }
        if ($context->has(\Monolog\Logger::class)) {
            $context->call(function(\Monolog\Logger $logger, $contextId) {
                $logger->pushProcessor(function($record) use ($contextId) {
                    $record['extra'] = array_merge($record['extra'], compact('contextId'));
                    return $record;
                });
            }, compact('contextId'));
        }
    }

    public function handle(HandleContext $context, Package $package, Writer $writer, ?callable $next) {
        $this->init($context, $package, $writer);
        $next($context, $package, $writer);
    }

}
