<?php

use Swooen\Batis\PBatis;
use Swooen\Batis\Pool\PDOConfig;
use Swooen\Batis\Pool\SwoolePool;

require_once __DIR__.'/../vendor/autoload.php';
$pool = new SwoolePool(new PDOConfig('host.office.nordri.com', '3306', 'dev', 'SRWhCqQYVd6xIWfQ', 'mzt-event', 'utf8mb4'));
$batis = new PBatis($pool);
// for ($i=0; $i < 100; $i++) { 
//     go(function() use ($batis) {
//         $transaction = $batis->transaction();
//         $ret = $transaction->select('select * from `event` limit 1;', []);
//     });
// }
go(function() use ($batis) {
    $transaction = $batis->transaction();
    // var_dump($transaction->insertRows('event', [['event' => 'test1'], ['event' => 'test2']]));
    var_dump($transaction->updateRow('client_event_log', ['updateTime' => time()], ['eventId' => 12728]));
    var_dump($transaction->selectWhere('event', ['eventId' => 12728]));
});