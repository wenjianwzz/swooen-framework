<?php
namespace Swooen\Http;

use Illuminate\Support\Arr;
use Swooen\Http\Parser\JsonParser;
use Swooen\Http\Parser\ParserInterface;

/**
 */
class Request extends \Symfony\Component\HttpFoundation\Request {

	/**
	 * @var ParserInterface[]
	 */
	protected $contentParsers = [];

	public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
		parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
		$this->registerContentParser(new JsonParser());
	}

	public function registerContentParser(ParserInterface ...$parser) {
		array_unshift($this->contentParsers, ...$parser);
	}
	
	/**
	 * 
	 * @param \Swoole\Http\Request $request
	 * @return \Swooen\Http\Request
	 */
	public static function createFromSwoole(\Swoole\Http\Request $request) {
		$uri = $request->server['request_uri'].(isset($request->server['query_string'])?'?'.$request->server['query_string']:'');
		$method = $request->server['request_method'];
		$parameters = $request->post ?: [];
		$cookies = $request->cookie ?: [];
		$files = $request->files ?: [];
		$server = static::_server($request);
		$content = $request->rawcontent();
		return \Swooen\Http\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
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

	public function input($key=null, $default=null) {
		$body = $this->parse();
		$params = array_merge($this->request->all(), $this->query->all());
		if (empty($key)) {
			return array_merge($params, $body);
		}
		if (Arr::has($body, $key)) {
			return Arr::get($body, $key, $default);
		}
		return Arr::get($params, $key, $default);
	}

	public function parse() {
		foreach ($this->contentParsers as $parser) {
			if ($parser->accept($this->getContentType())) {
				return $parser->parse($this->getContent());
			}
		}
		return [];
	}
	
}
