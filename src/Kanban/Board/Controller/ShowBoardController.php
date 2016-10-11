<?php
declare(strict_types=1);

namespace Phpkanban\Board\Controller;

use Aerys\{Request, Response};
use Phpkanban\Board\Entity\{Board, BoardStore};

class ShowBoardController
{
    public function __construct()
    {
    }

    public function __invoke(Request $request, Response $response, array $args) : void
    {
        $b = $this->addBoard(new Board($args['name'], ""));
        $response->end($this->getBaseView($b, $this->getBoardView($b)));
    }

    private function addBoard(Board $board) : Board
    {
        return BoardStore::addBoard($board);
    }

    private function getBoardView(Board $board) : string
    {
        $data = '<ul class="tasks" id="categoryListWrapper">';
        foreach ($board->getTasks() as $task) {
            $data .= '<li><span title="'.$task->getDescription().'">'.$task->getTitle().'</span></li>';
        }
        $data .= '</ul>';

        return $data;
    }

    private function getBaseView(Board $board, string $boardHtml) : string
    {
        return '
            <!DOCTYPE html>
            <html>
            <head>
            <title>Plank - '.$board->getName().'</title>
            </head>
            <body>
                <h1>Plank</h1>
                <div class="content" id="main">
                    <h2>Board: '.$board->getName().'</h2>
                    <div id="categoryList">'.$boardHtml.'</div>
                    <!-- <form id="addCat">
                        <input type="text" name="category" id="category"/>
                        <input type="button" id="submit" value="submit"/>
                    </form> -->
                    <form id="addTask">
                        <label for="title">title: </label><input type="text" name="title" id="title" required/><br/>
                        <label for="description">description: </label><input type="text" name="description" id="description"/><br/>
                        <input type="button" id="submitTask" value="submit"/>
                    </form>
                </div>
                <script>
                    var boardUrl = "'.$board->getUrl().'"
                </script>
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="/js/board.js"></script>
            </body>
            </html>
        ';
    }
}
