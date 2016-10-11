<?php
declare(strict_types=1);

namespace Phpkanban\Board\Controller;

use Aerys\{Request, Response};
use Phpkanban\Board\Entity\{Board, BoardStore};

class AddCategoryController
{
    private $boardStore;

    public function __construct()
    {
        $this->boardStore = new BoardStore();
    }

    public function __invoke(Request $request, Response $response, array $args) : void
    {
        $boardUrl = $args['boardUrl'];
        $category = $request->getParam('category');
        $this->addCategory($boardUrl, $category);
        $response->setStatus(204);
        $response->end(null);
    }

    private function addCategory(string $boardUrl, string $category) : void
    {
        $this
            ->boardStore
            ->getBoard($boardUrl)
            ->addCategory($board)
        ;
    }
}
