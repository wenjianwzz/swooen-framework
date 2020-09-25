<?php
namespace Swooen\Http;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Swooen\Http\Routes\Router;
use Swooen\Http\Exception\OriginNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 处理管道
 * 
 * @author WZZ
 *        
 */
class ServePipeline {
	
	public function serve(\Swooen\Core\Container $container, \Swooen\Http\Request $req, \Swooen\Http\Writer\Writer $writer, Router $router) {
		$found = $this->route($req, $router);
		// 先处理Cors
		$this->handleCors($router->getCorsHosts(), $req->headers->get('Origin'), $writer);
		switch ($found[0]) {
			case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				throw new MethodNotAllowedHttpException($found[1], 'Method Not Allow');
			case \FastRoute\Dispatcher::NOT_FOUND:
				throw new NotFoundHttpException();
			case \FastRoute\Dispatcher::FOUND:
				$route = $found[1];
				$params = $found[2];
				assert($route instanceof Routes\Route);
				$route->setParams($params);
				$container->instance(Routes\Route::class, $route);
				// TODO Provider ，且只能加载一次
				foreach ($route->getMiddlewares() as $middlware) {
					if (!$this->step($middlware, $params, $container, $container->make(\Swooen\Http\Writer\Writer::class))) {
						return;
					}
				}
				$uses = $route->getAction();
				if (!is_callable($uses)) {
					if (is_string($uses) && ! Str::contains($uses, '@')) {
						$uses .= '@__invoke';
					}
					list($controller, $method) = explode('@', $uses);
					$controller = $container->make($controller);
					$uses = [$controller, $method];
				}
				$this->step($uses, $params, $container, $container->make(\Swooen\Http\Writer\Writer::class));
				break;

		}
	}

	public function step($call, $params, \Swooen\Core\Container $container, \Swooen\Http\Writer\Writer $writer) {
		if (is_callable($call)) {
			$ret = $container->call($call, $params);
		} else if (is_string($call)) {
			$obj = $container->make($call, $params);
			$ret = $container->call([$obj, 'handle'], $params);
		} else {
			throw new \Exception('unknown handler');
		}
		if (is_bool($ret)) {
			return $ret;
		} else if ($ret) {
			$writer->response($ret);
			return false;
		}
	}

	public function handleCors($originAllows, $origin, \Swooen\Http\Writer\Writer $writer) {
		if (empty($originAllows)) {
			return true;
		}
		$matched = empty($origin);
		if (!$matched) {
			foreach ($originAllows as $originAllow) {
				$pattern = '|'.$originAllow.'|i';
				if (1 === preg_match($pattern, $origin)) {
					$matched = true;
					break;
				}
			}
		}
		if (!$matched) {
			throw new OriginNotAllowedException();
		} else {
			$writer->header('Access-Control-Allow-Origin', $origin);
			$writer->header('Access-Control-Allow-Credentials', 'true');
		}
        return true;
		
	}

	public function route(\Swooen\Http\Request $req, Router $router) {
		return $router->dispatch($req->getMethod(), $req->getPathInfo());
	}

}
