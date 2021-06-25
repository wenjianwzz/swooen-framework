<?php
namespace Swooen\Config;

interface ConfigLoader {
    
    /**
     * 将配置加载到配置
     */
    public function load(ConfigRepository $loadToStore);
}