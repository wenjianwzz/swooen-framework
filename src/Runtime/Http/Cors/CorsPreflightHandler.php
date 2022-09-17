<?php
namespace Swooen\Runtime\Http\Cors;

use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\RawPackage;
use Swooen\Communication\Route\Route;
use Swooen\Runtime\Http\Writer\HttpWriter;

/**
 * 跨域预检
 */
class CorsPreflightHandler {

    protected $checker;

    protected $headers = [];

    protected $methods = [];

    protected $maxAge;

    public function __construct(OriginChecker $checker, $methods, $headers, $maxAge=86400) {
        $this->checker = $checker;
        $this->headers = $headers;
        $this->methods = $methods;
        $this->maxAge = $maxAge;
    }

    public function __invoke(Route $route, Package $package, Connection $connection) {
        $origin = $package->meta('origin');
        $writer = $connection->getWriter();
        if ($origin and $this->checker->allow($origin) and $writer instanceof HttpWriter) {
            // 处理跨域头部
            $headers = array_merge($route->getMeta('cors-headers', []), $this->headers);
            $methods = array_merge($route->getMeta('cors-methods', []), $this->methods);
            $writer->header('Access-Control-Allow-Methods', join(', ', $methods));
            $writer->header('Access-Control-Allow-Headers', join(', ', $headers));
            $writer->header('Access-Control-Allow-Credentials', 'true');
            $writer->header('Access-Control-Max-Age', $this->maxAge);
            $writer->header('Access-Control-Allow-Origin', $origin);
        }
        return new RawPackage('', []);
    }

}