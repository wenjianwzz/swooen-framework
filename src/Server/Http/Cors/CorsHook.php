<?php
namespace Swooen\Server\Http\Cors;

use Swooen\Communication\Connection;
use Swooen\Communication\Package;
use Swooen\Communication\Route\Handler\HandlerContext;
use Swooen\Communication\Route\Hook\HandlerHook;
use Swooen\Communication\Route\Route;
use Swooen\Server\Http\Writer\HttpWriter;

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