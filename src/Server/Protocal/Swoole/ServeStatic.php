<?php
namespace Swooen\Http;

use Symfony\Component\Mime\MimeTypes;

/**
 * 处理HTTP
 * 
 * @author WZZ
 *        
 */
class ServeStatic {
	
	public static function serve($publicDir, \Swooen\Http\Request $req, \Swooen\Http\Writer\Writer $writer) {
		$uri = $req->getPathInfo();
		$method = $req->getMethod();
		if ('GET' === $method) {
			$uri = ltrim($uri, '/');
			if (empty($uri)) {
				$uri = 'index.html';
			}
			$path = rtrim($publicDir, '/ ') . '/' . $uri;
			if (is_file($path) and is_readable($path)) {
				$mime = (new MimeTypes())->getMimeTypes(pathinfo($path, PATHINFO_EXTENSION));
				$mime or $mime = 'application/bin';
				if ($mime) {
					$writer->header('Content-Type', is_array($mime)?reset($mime):$mime);
				}
				$writer->sendfile($path);
				return true;
			}
		}
		return false;
	}

}
