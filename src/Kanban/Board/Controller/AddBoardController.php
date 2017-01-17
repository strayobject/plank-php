<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Controller;

use Aerys\{function parseBody, Request, Response};
use Plank\Shared\User\Entity\User;
use Plank\Kanban\Board\{Entity\BoardRepository, Parser\BoardDataParser};

class AddBoardController
{
    private $boardRepo;
    private $boardDataParser;

    public function __construct(
        BoardRepository $boardRepo,
        BoardDataParser $boardDataParser
    ) {
        $this->boardRepo = $boardRepo;
        $this->boardDataParser = $boardDataParser;
    }

    /**
     * @todo validate the data
     * @todo move the bulk of this into a separate class
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     */
    public function __invoke(Request $request, Response $response, array $args): \Generator
    {
        $user = $request->getLocalVar('user');
        $requestData = yield $request->getBody();
        $data = $this->boardDataParser->parseNew($requestData, $user->getId());
        $this->boardRepo->persist($data->toArray()['data']);
        $response->setStatus(201);

        return $data->getResource();
    }
}
