<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\App\{Exception\ItemNotFoundException, Responder\ResponderInterface, Transformer\ExceptionTransformer};
use Plank\Kanban\Board\{Entity\Board, Entity\BoardRepository, Transformer\BoardTransformer};
use League\Fractal\{Manager, Resource\Collection, Resource\Item};

class ListBoardsController
{
    private $boardRepo;
    private $responder;

    public function __construct(BoardRepository $boardRepo, ResponderInterface $responder)
    {
        $this->boardRepo = $boardRepo;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, Response $response, array $args): void
    {
        $user = $request->getLocalVar('user');

        try {
            $boards = $this->boardRepo->getBoards($user->getId());
            $resource = new Collection($boards, new BoardTransformer(), 'board');
        } catch (ItemNotFoundException $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(404);
        }

        $this->responder->createAndFinish($response, $resource);
    }
}
