<?php
namespace Swooen\Package\Package\Features;

/**
 * 给对端发送状态码
 * 
 * @author WZZ
 */
interface StatusCodeNotice {

    /**
	 * 获取状态码
	 * @return string
	 */
	public function getStatusCode();

    const CODE_OK = 0;

}

trait StatusNoticeImpl {

    /**
     * @var int
     */
    protected $statusCode;

	public function getStatusCode(): int {
        return $this->statusCode;
    }
	
}
