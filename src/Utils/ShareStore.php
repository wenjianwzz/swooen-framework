<?php
namespace Swooen\Utils;

/**
 * 共享数组容器
 * 
 * @author WZZ
 */
class ShareStore {

	protected $dir;
	
	public function __construct($dir) {
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		$this->dir = $dir;
	}
	
	protected function date_file($key) {
		$slot = crc32($key) % 1024;
		$dir = rtrim($this->dir, '/').'/slots/'.$slot;
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		return $dir.'/'.$key;
	}
	
	/**
	 * 从存储加载
	 * @param string $key
	 * @param callable $onValue($value, $key)
	 */
	public function get($key, callable $onValue) {
		if (!$this->has($key)) {
			return false;
		}
		$this->mutex($key, function($file, $unlock, $key) use ($onValue) {
			if (file_exists($file)) {
				$callback = function($filename, $content) use ($key, $onValue, $unlock) {
					$value = \Swoole\Serialize::unpack($content);
					$unlock();
					$onValue($value, $key);
				};
				if (!\Swoole\Async::readFile($file, $callback)) {
					$unlock();
				}
			} else {
				$unlock();
			}
		});
	}
	
	/**
	 * 写回到存储
	 */
	public function set($key, $value) {
		$this->mutex($key, function($file, $unlock, $key) use ($value) {
			$content = \Swoole\Serialize::pack($value);
			\Swoole\Async::writeFile($file, $content, $unlock);
		});
	}
	
	protected function mutex($key, callable $task) {
		$file = $this->date_file($key);
		$lock = new \Swoole\Lock(SWOOLE_FILELOCK, $file);
		if ($lock->lock()) {
			$task($file, [$lock, 'unlock'], $key);
		}
	}
	
	public function has($key) {
		$file = $this->date_file($key);
		return file_exists($file);
	}
	
	public function remove($key) {
		$this->mutex($key, function($file, $unlock, $key) {
			try {
				unlink($file);
				echo "rm {$file}\n";
			} finally {
				$unlock();
			}
		});
	}
	
	public function mutate($key, callable $mutate) {
		if (!$this->has($key)) {
			return false;
		}
		$this->mutex($key, function($file, $unlock, $key) use ($mutate) {
			if (!file_exists($file)) {
				$unlock();
				return;
			}
			$callback = function($filename, $content) use ($file, $mutate, $unlock) {
				$value = \Swoole\Serialize::unpack($content);
				$newVal = $mutate($value);
				$content = \Swoole\Serialize::pack($newVal);
				\Swoole\Async::writeFile($file, $content, $unlock);
			};
			if (!\Swoole\Async::readFile($file, $callback)) {
				$unlock();
			}
		});
	}
}
