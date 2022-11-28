<?php
namespace Swooen\Console;

use Swooen\Handle\HandleContext;
use Swooen\Handle\PackageHandler;
use Swooen\Handle\Writer\Writer;
use Swooen\Package\Package;

/**
 * 命令执行器
 * @author WZZ
 */
class CommandHandler extends PackageHandler {

	public function handle(HandleContext $context, Package $package, Writer $writer, callable $next) {
		if (!$package instanceof ConsolePackage) {
			throw new \RuntimeException('命令行上下文配置错误');
		}
		$callable = $package->getCallable();
		$context->call($callable);
		$next($context, $package, $writer);
	}
}
