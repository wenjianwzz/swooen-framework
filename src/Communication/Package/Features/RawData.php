<?php
namespace Swooen\Communication\Package\Features;

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
	
}

/**
 * 可供路由的
 * 
 * @author WZZ
 */
trait RawDataImpl {

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
	
}
