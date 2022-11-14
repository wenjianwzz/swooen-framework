<?php
namespace Swooen\Server\Generic\Package;

use Swooen\Handle\Writer\StdoutWriter;
use Swooen\Package\Features\DataArray;

class HttpWriter extends StdoutWriter {

    /**
     * 将package的inputs转换成字符串
     */
    public function pack(DataArray $package) {
        return json_encode($package->allData(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
	
	public function writable(): bool {
        return true;
    }
	
	public function writeMeta(string $name, string $value) {
        header("{$name}: {$value}");
		return true;
    }

}
