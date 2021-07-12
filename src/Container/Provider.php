<?php
namespace Swooen\Container;

abstract class Provider {

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(Container $container) {}
}