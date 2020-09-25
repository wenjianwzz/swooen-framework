<?php
namespace Swooen\View;

use Illuminate\Config\Repository;

class ViewServiceProvider extends \Swooen\Core\Provider {
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(\Swooen\Core\Container $container) {
		$container->singleton(\Twig\Loader\FilesystemLoader::class, function(Repository $config) {
			return new \Twig\Loader\FilesystemLoader($config->get('view.paths', []));
		});
		$container->singleton(\Twig\Environment::class, function(\Twig\Loader\FilesystemLoader $loader) {
			return new \Twig\Environment($loader);
		});
	}

	public function boot(\App\Core\Application $app, \Twig\Loader\FilesystemLoader $loader) {
		$loader->addPath($app->resourcePath('views/'));
	}

}
