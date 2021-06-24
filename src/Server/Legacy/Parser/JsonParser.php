<?php
namespace Swooen\Server\Legacy\Parser;
use \Illuminate\Support\Str;

/**
 * JSON请求解析
 */
class JsonParser implements ParserInterface {

    public function accept($contentType) : bool {
        return Str::contains($contentType, ['json']);
    }

    public function parse($content) {
        return json_decode($content, true);
    }

}
