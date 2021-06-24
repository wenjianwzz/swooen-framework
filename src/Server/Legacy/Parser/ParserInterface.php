<?php
namespace Swooen\Server\Legacy\Parser;

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
     * @return array
     */
    public function parse($content);

}
