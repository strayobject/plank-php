<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Middleware;

use Aerys\{InternalRequest, Middleware, Request, Response};
use Plank\Shared\User\Provider\UserProviderInterface;
use Plank\Kanban\App\Exception\ItemNotFoundException;

/**
 * @todo inject responder and make sure response is a valid json
 */
class BasicAuth implements Middleware
{
    private $userProvider;

    /**
     * @todo pass value into constructor
     * @var integer
     */
    private $cost = 13;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function do(InternalRequest $ireq): void
    {
        try {
            $auth = $this->parseRequest($ireq);
            $user = $this->userProvider->fetchUserByAuth($auth['email'], $auth['password']);
            $ireq->locals['user'] = $user;
        } catch (\Exception $e) {
            $ireq->locals['authResponse'] = $e->getMessage();
        } catch (ItemNotFoundException $e) {
            $ireq->locals['authResponse'] = 'Invalid credentials.';
        }
    }

    public function __invoke(Request $req, Response $res): void
    {
        if (empty($req->getLocalVar('authResponse'))) {
            return;
        }

        $res->setStatus(401);
        $res->setHeader('www-authenticate', 'Basic realm="PlankApi"');
        $res->end('We will have json response here. '.$req->getLocalVar('authResponse'));
    }

    /**
     * @param  InternalRequest $ireq
     * @return array
     */
    private function parseRequest(InternalRequest $ireq): array
    {
        if (!isset($ireq->headers['authorization'])) {
            throw new \OutOfBoundsException('Missing authorization key in the header.');
        }

        $authString = base64_decode(substr($ireq->headers['authorization'][0], 6));
        list($email, $password) = explode(':', $authString);

        if (empty($email) || empty($password)) {
            throw new \OutOfBoundsException('Missing parts of required authorization data.');
        }

        return [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->cost]),
        ];
    }
}
