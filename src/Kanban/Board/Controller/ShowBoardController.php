<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\App\{Exception\ItemNotFoundException, Transformer\ExceptionTransformer};
use Plank\Kanban\Board\{Entity\Board, Entity\BoardRepository, Transformer\BoardTransformer};
use League\Fractal\{Manager, Resource\Item};

class ShowBoardController
{
    private $boardRepo;
    private $outputManager;

    public function __construct(BoardRepository $boardRepo, Manager $outputManager)
    {
        $this->boardRepo = $boardRepo;
        $this->outputManager = $outputManager;
    }

    public function __invoke(Request $request, Response $response, array $args): void
    {
        /**
         * @todo fetch board if belongs to current user only
         */
        try {
            $board = $this->boardRepo->getBoard($args['id']);
            $resource = new Item($board, new BoardTransformer(), 'board');
        } catch (ItemNotFoundException $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(404);
        } catch (\Exception $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(400);
        }

        $data = $this->outputManager->createData($resource)->toJson();
        $response->end($data);
    }
}
