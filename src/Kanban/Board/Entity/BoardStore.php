<?php
declare(strict_types=1);

namespace Phpkanban\Board\Entity;


class BoardStore implements \JsonSerializable
{
    private static $boards = [];

    public function __construct()
    {
    }

    public function jsonSerialize() : array
    {
        return [
            'boards' => self::$boards,
        ];
    }

    public static function getBoards() : array
    {
        return self::$boards;
    }

    public static function addBoard(Board $board) : Board
    {
        if (isset(self::$boards[$board->getUrl()])) {
            return self::$boards[$board->getUrl()];
        }

        self::$boards[$board->getUrl()] = $board;

        return $board;
    }

    public static function getBoard(string $url) : Board
    {
        return self::$boards[$url];
    }
}
