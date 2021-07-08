<?php
namespace Swooen\Server\Swoole\Redis;

class RedisWriter implements Writer {

	/**
	 * 给对方发送数据包
	 * @return boolean
	 */
	public function send(Package $package) {
        if ($metas = $package->metas()) {
            array_map(function($name, $value) {
                $this->writeMeta($name, $value);
            }, array_keys($metas), $metas);
        }
        if ($package->isString()) {
            return $this->write($package->getString());
        } else {
            return $this->write($this->pack($package));
        }
	}

    /**
     * 将package的inputs转换成字符串
     */
    public function pack(Package $package) {
        return print_r($package->inputs(), true);
    }
	
	public function canWrite() {
        return true;
    }

	public function write(string $content) {
        echo $content;
		return true;
    }
	
	public function writeMeta(string $name, string $value) {
        echo $name, $value, PHP_EOL;
		return true;
    }

}
