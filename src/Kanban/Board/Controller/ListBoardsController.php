<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\Board\Entity\{Board, BoardRepository};

class ListBoardsController
{
    private $boardRepo;

    public function __construct(BoardRepository $boardRepo)
    {
        $this->boardRepo = $boardRepo;
    }

    public function __invoke(Request $request, Response $response, array $args): void
    {
        
    }

    private function addBoard(Board $board): void
    {

    }
}
