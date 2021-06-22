<?php
namespace Swooen\Http\Parser;
use \Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\ParameterBag;

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
