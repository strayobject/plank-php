<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{Request, Response};
use Plank\Kanban\Board\Entity\{Board, BoardRepository};

class AddCategoryController
{
    private $boardRepo;

    public function __construct(BoardRepository $boardRepo)
    {
        $this->boardRepo = $boardRepo;
    }

    public function __invoke(Request $request, Response $response, array $args): void
    {
        // $boardUrl = $args['boardUrl'];
        // $category = $request->getParam('category');
        // $this->addCategory($boardUrl, $category);
        $response->setStatus(204);
        $response->end(null);
    }

    private function addCategory(): void
    {

    }
}
