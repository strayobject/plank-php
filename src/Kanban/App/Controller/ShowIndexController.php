<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Controller;

use Aerys\{Request, Response};

class ShowIndexController
{
    public function __construct()
    {

    }

    public function __invoke(Request $request, Response $response, array $args) : void
    {
        $response->end($this->getBaseView());
    }

    private function getBaseView() : string
    {
        return '
            <!DOCTYPE html>
            <html>
            <head>
            <title>Plank - Home</title>
            </head>
            <body>
                <h1>Plank</h1>
                <div class="content" id="boardList">
                    <h2>Available Boards</h2>
                </div>
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="/js/index.js"></script>
            </body>
            </html>
        ';
    }
}
