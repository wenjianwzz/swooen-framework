<?php
namespace Swooen\Http\Writer;

/**
 * 控制Socket的响应
 */
class Writer {
    
    public function write($content) {
        echo $content;
    }

    public function header($name, $value) {
        header("{$name}: {$value}");
    }

    public function cookie(string $name, $value = "", int $expire = 0, $path = "" , $domain = "" , bool $secure = false, $httponly = false) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function status($code) {
        http_response_code($code);
    }

    public function sendfile($path) {
        readfile($path);
    }

    public function end() {}

    /**
     * 发送并关闭
     */
    public function response($response) {
        if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
            foreach ($response->headers->allPreserveCase() as $name => $values) {
                if ('Set-Cookie' === $name) {
                    continue;
                }
            	foreach ($values as $value) {
            		$this->header($name, $value);
            	}
            }
            foreach ($response->headers->getCookies() as $cookie) {
                $this->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
            }
			$this->status($response->getStatusCode());
            $this->write($response->getContent());
		} else if ($response) {
			$this->write((string)$response);
        }
        $this->end();
    }

}
