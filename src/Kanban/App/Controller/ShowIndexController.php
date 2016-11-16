<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Controller;

use Aerys\{Request, Response};

class ShowIndexController
{
    public function __construct($boardRepo, $outputManager)
    {
        $this->boardRepo = $boardRepo;
        $this->outputManager = $outputManager;
    }

    public function __invoke(Request $request, Response $response, array $args) : void
    {
        $str = '';
        $data = $this->boardRepo->getBoards();

        foreach ($data as $board) {
            $str .= '<p><a href="/b/'.$board->getId().'">'.$board->getName().'</a></p>';
        }

        $response->end($this->getBaseView($str));
    }

    private function getBaseView(string $str): string
    {
        return '
            <!DOCTYPE html>
            <html>
            <head>
            <title>Plank\Kanban - Home</title>
            </head>
            <body>
                <h1>Plank\Kanban</h1>
                <div class="content" id="boardList">
                    <h2>Available Boards</h2>
                    '.$str.'
                </div>
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="/js/index.js"></script>
            </body>
            </html>
        ';
    }
}
