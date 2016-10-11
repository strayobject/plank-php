<?php
declare(strict_types=1);

namespace Phpkanban\Board\Controller;

use Aerys\{Request, Response};
use Phpkanban\Board\Entity\{Board, BoardStore};

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
