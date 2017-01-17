<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Parser;

use League\Fractal\{Scope, Resource\Item};
use Plank\Kanban\App\Responder\ResponderInterface;
use Plank\Kanban\Board\{Entity\BoardHydrator, Transformer\BoardTransformer};

class BoardDataParser
{
    const ACTION_NEW = 'new';
    const ACTION_UPDATE = 'update';

    private $responder;
    private $boardHydrator;

    public function __construct(
        BoardHydrator $boardHydrator,
        ResponderInterface $responder
    ) {
        $this->boardHydrator = $boardHydrator;
        $this->responder = $responder;
    }

    public function parse(string $data, string $userId, string $action): Scope
    {
        $parsed = json_decode($data, true);

        if ($action === self::ACTION_NEW) {
            unset($parsed['data']['id']);
        }

        $parsed['data']['ownerId'] = $userId;
        $board = $this->boardHydrator->hydrate($parsed['data']);
        $resource = new Item($board, new BoardTransformer(), 'board');

        return $this->responder->createData($resource)->getData();
    }

    public function parseNew(string $data, string $userId): Scope
    {
        return $this->parse($data, $userId, self::ACTION_NEW);
    }

    public function parseUpdate(string $data, string $userId): Scope
    {
        return $this->parse($data, $userId, self::ACTION_UPDATE);
    }
}
