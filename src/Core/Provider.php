<?php
namespace Swooen\Core;

abstract class Provider {

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(Container $container) {}
}