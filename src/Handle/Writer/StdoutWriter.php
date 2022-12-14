<?php
namespace Swooen\Handle\Writer;

use Swooen\Package\Features\DataArray;
use Swooen\Package\Features\Metas;
use Swooen\Package\Features\RawData;
use Swooen\Package\Package;

class StdoutWriter implements Writer {

	public function send(Package $package): bool {
        if ($package instanceof Metas) {
            foreach($package->allMetas() as $name => $value) {
                $this->writeMeta($name, $value);
            }
        }
        if ($package instanceof RawData) {
            return $this->write($package->getRawData());
        } else if ($package instanceof DataArray) {
            return $this->write($this->pack($package));
        }
        return true;
	}

    /**
     * 将package的inputs转换成字符串
     */
    public function pack(DataArray $package) {
        return json_encode($package->allData(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }
	
	public function writable(): bool {
        return true;
    }

	/**
	 * 结束写入
	 */
	public function end(?Package $package) {
        if ($package) {
            $this->send($package);
        }
	}

	public function write(string $content): bool {
        echo $content;
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
        echo "{$name}: {$value}". PHP_EOL;
		return true;
    }

}
