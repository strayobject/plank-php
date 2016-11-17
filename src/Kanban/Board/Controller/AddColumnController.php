<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\Board\Entity\{Board, BoardRepository, Column};

class AddColumnController
{
    private $boardRepo;

    public function __construct(BoardRepository $boardRepo)
    {
        $this->boardRepo = $boardRepo;
    }

    public function __invoke(Request $request, Response $response, array $args): void
    {
        $boardId = $args['id'];
        try {
            /**
             * @todo move this out of controller
             */
            $board = $this->boardRepo->getBoard($boardId);
            //$columnData = 
            $column = new Column();
            $board->addColumn($column);
            $response->setStatus(204);
        } catch (ItemNotFoundException $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(404);
        } catch (\Exception $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(400);
        }

        $response->end(null);
    }
}
