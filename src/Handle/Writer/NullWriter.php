<?php
namespace Swooen\Handle\Writer;

use Swooen\Package\Package;

class NullWriter implements Writer {

	public function send(Package $package): bool {
        return true;
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

}
