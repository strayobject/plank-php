<?php
declare(strict_types=1);

namespace Phpkanban\Board\Controller;

use Aerys\{Request, Response, Websocket};
use Phpkanban\Board\Entity\BoardStore;
use Phpkanban\Task\Entity\Task;

class WsBoardController implements Websocket
{
    private $endpoint;
    private $connections;
    private $ips;

    public function __construct()
    {
        // normally we would inject this data into this object
        $this->origin = 'http://localhost:8080';
    }

    public function onStart(Websocket\Endpoint $endpoint)
    {
        // On server startup we get an instance of Aerys\Websocket\Endpoint, so we can send messages to clients.
        $this->endpoint = $endpoint;
        $this->connections = [];
        $this->boards = [];
    }

    public function onHandshake(Request $request, Response $response)
    {
        $uriParts = explode('/', $request->getUri());
        $boardUrl = array_pop($uriParts);
        // During handshakes, you should always check the origin header, otherwise any site will
        // be able to connect to your endpoint. Websockets are not restricted by the same-origin-policy!
        $origin = $request->getHeader('origin');

        if ($origin !== $this->origin) {
            $response->setStatus(403);
            $response->end("<h1>origin not allowed</h1>");
            return;
        }
        // returned values will be passed to onOpen, that way you can pass cookie values or the whole request object.
        return $boardUrl; //$request->getConnectionInfo()["client_addr"];
    }

    public function onOpen(int $clientId, $handshakeData)
    {
        // We keep one map for all connected clients.
        $this->connections[$clientId] = $handshakeData;
        // And another one for multiple clients with the same IP.
        $this->boards[$handshakeData][$clientId] = true;
    }
    public function onData(int $clientId, Websocket\Message $msg) {
        // yielding $msg buffers the complete payload into a single string. For very large payloads, you may want to
        // stream those instead of buffering them.
        $body = yield $msg;
        $payload = $body;
        $boardUrl = $this->connections[$clientId];
        $this->addTask($boardUrl, json_decode($body, true));
        $receivers = array_keys($this->boards[$boardUrl]);
        $this->endpoint->send($receivers, $payload);
    }
    public function onClose(int $clientId, int $code, string $reason) {
        // Always clean up data when clients disconnect, otherwise we'll leak memory.
        $board = $this->connections[$clientId];
        unset($this->connections[$clientId]);
        unset($this->boards[$board][$clientId]);
        if (empty($this->boards[$board])) {
            unset($this->boards[$board]);
        }
    }
    public function onStop() {
        // intentionally left blank
    }

    private function addTask($boardUrl, array $data)
    {
        // under normal circumstances we would validate the $data
        $task = new Task($data['title'], $data['description']);
        $b = BoardStore::getBoard($boardUrl);

        $b->addTask($task);
    }
}
