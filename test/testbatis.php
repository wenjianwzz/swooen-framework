<?php

use Swooen\Data\PBatis;
use Swooen\Data\Pool\PDOConfig;
use Swooen\Data\Pool\SimplePool;
use Swooen\Data\Pool\SwoolePool;

require_once __DIR__.'/../vendor/autoload.php';
$pool = new SwoolePool(new PDOConfig('host.office.nordri.com', '3306', 'dev', 'SRWhCqQYVd6xIWfQ', 'mzt-event', 'utf8mb4'));
$batis = new PBatis($pool);
for ($i=0; $i < 100; $i++) { 
    go(function() use ($batis) {
        $transaction = $batis->transaction();
        $ret = $transaction->select('select * from `event` limit 1;', []);
    });
}