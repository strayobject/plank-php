<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\Board\Entity\{Board, BoardRepository};

class ListBoardsController
{
    private $boardStore;

    public function __construct()
    {
        $this->boardStore = new BoardStore();
    }

    public function __invoke(Request $request, Response $response, array $args) : void
    {
        $response->end(json_encode($this->boardStore));
    }

    private function addBoard(Board $board) : void
    {
        $this->boardStore->addBoard($board);
    }
}
