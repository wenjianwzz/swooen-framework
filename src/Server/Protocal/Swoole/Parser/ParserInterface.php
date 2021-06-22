<?php
namespace Swooen\Http\Parser;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 解析报文
 */
interface ParserInterface {

    /**
     * 是否接受
     */
    public function accept($contentType) : bool;

    /**
     * 解析
     */
    public function parse($content);

}
