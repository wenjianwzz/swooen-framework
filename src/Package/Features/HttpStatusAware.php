<?php
namespace Swooen\Package\Features;

/**
 * 给对端发送状态码
 * 
 * @author WZZ
 */
interface HttpStatusAware {

    /**
	 * 获取状态码
	 * @return string
	 */
	public function getHttpStatusCode();

}

trait HttpStatusAwareFeature {

    /**
     * @var int
     */
    protected $httpStatusCode;

	public function getHttpStatusCode(): int {
        return $this->httpStatusCode;
    }
	
}
