<?php

require_once __DIR__.'/../../vendor/autoload.php';

use r\Exceptions\{RqlException, RqlServerError};
use Ramsey\Uuid\Uuid;

$conn = r\connect('rdb', 28015);
$conn->useDb('plank');

$userIds = [
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
];

$boardIds = [
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
];

$columnIds = [
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
    Uuid::uuid4()->serialize(),
];

$users = [
    [
        'id' => $userIds[0],
        'firstName' => 'Mike',
        'lastName' => 'Zee',
        'alias' => 'Strayobject',
        'email' => 'code@strayobject.co.uk',
        'password' => '',
    ],
    [
        'id' => $userIds[1],
        'firstName' => 'Bob',
        'lastName' => 'Zee',
        'alias' => 'Strayobject',
        'email' => 'code@strayobject.co.uk',
        'password' => '',
    ],
    [
        'id' => $userIds[2],
        'firstName' => 'John',
        'lastName' => 'Zee',
        'alias' => 'Strayobject',
        'email' => 'code@strayobject.co.uk',
        'password' => '',
    ],
];

$boards = [
    [
        'id' => $boardIds[0],
        'ownerId' => $userIds[0],
        'participants' => [
            $userIds[1],
            $userIds[2],
        ],
        'name' => 'Todo',
        'description' => 'Todo list',
        'columns' => [
            [
                'id' => $columnIds[0],
                'name' => 'default',
                'order' => 0,
            ],
        ],
    ],
    [
        'id' => $boardIds[1],
        'ownerId' => $userIds[1],
        'participants' => [],
        'name' => 'Projects',
        'description' => '',
        'columns' => [
            [
                'id' => $columnIds[1],
                'name' => 'default',
                'order' => 0,
            ],
        ],
    ],
    [
        'id' => $boardIds[2],
        'ownerId' => $userIds[2],
        'participants' => [],
        'name' => 'Ideas',
        'description' => '',
        'columns' => [
            [
                'id' => $columnIds[2],
                'name' => 'default',
                'order' => 0,
            ],
        ],
    ],
];

$tags = [
    [
        'id' => Uuid::uuid4()->serialize(),
        'name' => 'Important',
        'color' => '#ff0000',
    ],
];


for ($i=0; $i < 15; $i++) {
    $boardColumnIdNumber = rand(0,2);
    $tasks[] = [
        'id' => Uuid::uuid4()->serialize(),
        'title' => 'Item '.$i,
        'description' => '',
        'order' => 0,
        'boardId' => $boardIds[$boardColumnIdNumber],
        'columnId' => $columnIds[$boardColumnIdNumber],
        'tags' => array_rand(
            [
                [],
                [
                    $tags[0]['id'],
                ],
            ],
            1
        ),
    ];
}


r\table('users')->insert($users)->run($conn);
r\table('boards')->insert($boards)->run($conn);
r\table('tasks')->insert($tasks)->run($conn);
r\table('tags')->insert($tags)->run($conn);

r\table('boards')->indexCreate('ownerId')->run($conn);
r\table('tasks')->indexCreate('boardId')->run($conn);
r\table('tasks')->indexCreate('columnId')->run($conn);
