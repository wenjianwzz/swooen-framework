<?php
namespace Swooen\Server\Http\Writer;

use Swooen\Communication\Package;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class JsonWriter extends HttpWriter {

    public function pack(Package $package) {
        return json_encode($package);
    }

}
