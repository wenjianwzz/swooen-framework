<?php
namespace Swooen\Server\Swoole\Http;

/**
 * @author WZZ
 */
class SwooleHttpRequestPacker {

	/**
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public static function packRequest(\Swoole\Http\Request $sreq) {
		$uri = $sreq->server['request_uri'].(isset($sreq->server['query_string'])?'?'.$sreq->server['query_string']:'');
		$method = $sreq->server['request_method'];
		$parameters = $sreq->post ?: [];
		$cookies = $sreq->cookie ?: [];
		$files = $sreq->files ?: [];
		$server = static::_server($sreq);
		$content = $sreq->rawcontent();
		return \Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
	}

	protected static function _server(\Swoole\Http\Request $request) {
		$ret = [];
		foreach ($request->header as $key=>$val) {
			$key = strtoupper(str_replace('-', '_', $key));
			$ret['HTTP_'.$key] = $val;
			$ret[$key] = $val;
		}
		foreach ($request->server as $key=>$val) {
			$key = strtoupper(str_replace('-', '_', $key));
			$ret[$key] = $val;
		}
		return $ret;
	}
}
