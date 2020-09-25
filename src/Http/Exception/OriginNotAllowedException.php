<?php
namespace Swooen\Http\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OriginNotAllowedException extends HttpException {

    public function __construct(\Throwable $previous = null, int $code = 0, array $headers = []) {
        parent::__construct(601, 'Origin Not Allowed', $previous, $headers, $code);
    }
}
