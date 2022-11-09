<?php
namespace Swooen\Runtime\Http\Cors;

use Swooen\Package\Connection;
use Swooen\Package\Package\Package;
use Swooen\Package\Route\Handler\HandlerContext;
use Swooen\Package\Route\Hook\HandlerHook;
use Swooen\Package\Route\Route;
use Swooen\Runtime\Http\Writer\HttpWriter;

class CorsHook extends HandlerHook {

    protected $checker;

    public function __construct(OriginChecker $checker) {
        $this->checker = $checker;
    }

    public function before(HandlerContext $context, Route $route, Package $package, Connection $connection) {
        $origin = $package->meta('origin');
        $writer = $connection->getWriter();
        if ($origin and $this->checker->allow($origin) and $writer instanceof HttpWriter) {
            // 处理跨域头部
            $writer->header('Access-Control-Allow-Origin', $origin);
            $writer->header('Vary', 'Origin');
            $writer->header('Access-Control-Allow-Credentials', 'true');
        }
        return $package;
    }

}