<?php
namespace Swooen\Communication;

class StdoutWriter implements Writer {

	public function send(Package $package) {
        if ($metas = $package->metas()) {
            array_map(function($name, $value) {
                $this->writeMeta($name, $value);
            }, array_keys($metas), $metas);
        }
        if ($package->isString()) {
            return $this->end($package->getString());
        } else {
            return $this->end($this->pack($package));
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

	/**
	 * 结束写入
	 */
	public function end(string $content=null) {
        if ($content) {
            $this->write($content);
        }
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
