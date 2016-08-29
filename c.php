<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9505, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send(json_encode(['event' => 'match::created', 'payload' => ['_id' => '123']]));
echo $client->recv();
$client->close();