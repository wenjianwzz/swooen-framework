<?php
namespace Swooen\Server\Generic\Package;

/**
 * @author WZZ
 */
class Reader {

	/**
	 * 解析下一个数据包
	 * @return HttpRequestPackage
	 */
	public function package(\Symfony\Component\HttpFoundation\Request $request) {
		$contentType = $request->getContentType();
		$content = $request->getContent();
		if (stripos($contentType, 'json') !== false) {
			$data = json_decode($content, true);
			return new HttpDataPackage($request, $data??[]);
		}
		return new HttpRawPackage($request, $content);
	}
}
