<?php
namespace Swooen\Runtime\Http\Writer;

use Swooen\Package\Package;

/**
 * 传统请求响应下的处理
 * @author WZZ
 */
class JsonWriter extends HttpWriter {

    public function pack(Package $package) {
        $this->header('Content-Type', 'application/json');
        return json_encode($package->inputs());
    }

}
