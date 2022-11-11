<?php
namespace Swooen\Package\Features;

/**
 * 可供路由的
 * 
 * @author WZZ
 */
interface RawData {

    /**
	 * 获取原始信息
	 * @return string
	 */
	public function getRawData();

    public function setRawData(string $rawData): self;
	
}

/**
 * 可供路由的
 * 
 * @author WZZ
 */
trait RawDataFeature {

    /**
     * @var string
     */
    protected $rawData;

    /**
	 * @return string
	 */
	public function getRawData(): string {
        return $this->rawData;
    }
	
    public function setRawData(string $rawData): self {
        $this->rawData = $rawData;
        return $this;
    }
}
