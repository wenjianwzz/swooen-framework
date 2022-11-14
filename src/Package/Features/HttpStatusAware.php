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

    public function setHttpStatusCode(int $statusCode): self;

}

trait HttpStatusAwareFeature {

    /**
     * @var int
     */
    protected $httpStatusCode = 200;

	public function getHttpStatusCode(): int {
        return $this->httpStatusCode;
    }

	public function setHttpStatusCode(int $statusCode): self {
        $this->httpStatusCode = $statusCode;
        return $this;
    }
	
}
