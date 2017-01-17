<?php

use PHPUnit\Framework\TestCase;

class BoardHydratorTest extends TestCase
{
    protected $idGen;
    protected $columnHydrator;
    protected $boardHydrator;

    public function setUp()
    {
        $this->idGen = new Ramsey\Uuid\UuidFactory();
        $this->columnHydrator = new Plank\Kanban\Board\Entity\ColumnHydrator($this->idGen);
        $this->boardHydrator = new Plank\Kanban\Board\Entity\BoardHydrator($this->idGen, $this->columnHydrator);
    }
    /**
     * @dataProvider singleItemWithIdData
     */
    public function testSingleItemHydratorSuccess($data)
    {
        $board = $this->boardHydrator->singleItemHydrate($data);
        $this->assertInstanceOf(Plank\Kanban\Board\Entity\Board::class, $board);
        $this->assertSame($data['id'], $board->getId());
        $this->assertSame($data['name'], $board->getName());
        $this->assertSame($data['description'], $board->getDescription());
        $this->assertSame($data['ownerId'], $board->getOwnerId());
    }

    /**
     * @dataProvider singleItemNoIdData
     */
    public function testSingleItemHydratorGeneratesId(array $data)
    {
        $board = $this->boardHydrator->singleItemHydrate($data);
        $this->assertInstanceOf(Plank\Kanban\Board\Entity\Board::class, $board);
        $this->assertNotEmpty($board->getId());

    }

    /**
     * @dataProvider singleItemWithIdData
     */
    public function testSingleItemHydratorGeneratesCorrectObject(array $data)
    {
        $board = $this->boardHydrator->singleItemHydrate($data);
        $this->assertInstanceOf(Plank\Kanban\Board\Entity\Board::class, $board);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHydratorThrowsOnUnsupportedDataType()
    {
        $board = $this->boardHydrator->hydrate('string is not supported');
    }

    public function singleItemWithIdData()
    {
        return [[
            [
                'id' => '427642e3-9f06-4266-8b7c-1285b0dc28f4',
                'ownerId' => '427642e3-9f06-4266-8b7c-1285b0dc28f3',
                'name' => 'Some Board',
                'description' => 'Some Desc',
                'participants' => [],
                'columns' => [],
            ],
        ]];
    }

    public function singleItemNoIdData()
    {
        return [[
            [
                'ownerId' => '427642e3-9f06-4266-8b7c-1285b0dc28f3',
                'name' => 'Some Board',
                'description' => 'Some Desc',
                'participants' => [],
                'columns' => [],
            ],
        ]];
    }
}
