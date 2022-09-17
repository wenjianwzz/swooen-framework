<?php
namespace Swooen\Runtime\Http\Cors;

class OriginChecker {

    protected $origins = [];

    /**
     * @params $origins 允许的源站 ~xxx代表正则匹配
     */
    public function __construct($origins) {
        $this->origins = $origins;
    }

    public function allow($origin) : bool {
        foreach ($this->origins as $allow) {
            if (empty($allow)) {
                // pass
            } else if ('~' === $allow[0]) {
                // 正则匹配
                $pattern = substr($allow, 1);
                if (1 === preg_match($pattern, $origin)) {
					return true;
				}
            } else if ($origin===$allow) {
                return true;
            }
        }
        return false;
    }

}