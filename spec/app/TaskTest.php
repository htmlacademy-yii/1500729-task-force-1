<?php

namespace app;

use taskforce\app\Task;
use PHPUnit\Framework\TestCase;
use taskforce\exceptions\ActionException;
use taskforce\exceptions\StatusException;

class TaskTest extends TestCase

{
    protected Task $fixture;
    protected int $author;
    protected int $executor;
    protected int $other;

    protected function setUp(): void
    {

        $this->author = 1;
        $this->executor = 2;
        $this->other = 3;
        $this->fixture = new Task($this->author, $this->executor);
    }

    /**
     * @dataProvider additionProvider
     * @param $a - выбранное действие
     * @param $b - предполагаемый статус
     * @throws actionException
     */
    public function testGetNewStatus($a, $b)
    {
        $this->assertEquals($b, $this->fixture->getNewStatus($a));

    }

    public function additionProvider(): array
    {
        return [
            ['action_done', 'done'],
            ['action_cancel', 'cancel'],
            ['action_decline', 'failed'],
            ['action_choose', 'in_work']
        ];
    }

    /**
     * @dataProvider getActiveActionsProvider
     * @param $a - статус задачи
     * @param $b - id пользователя(заказчик, исполнитель или третье лицо)
     * @param $c - предполагаемые доступные действия из этого статуса или уведомления что их нет
     * @throws statusException
     */
    public function testGetActiveActions($a, $b, $c)
    {
        $this->assertEqualsCanonicalizing($c, $this->fixture->getActiveActions($a, $b));
    }

    public function getActiveActionsProvider(): array
    {
        return [
            ['new', 1, ['action_cancel', 'action_choose']],
            ['new', 4, ['action_respond']],
            ['new', 3, ['action_respond']],
            ['in_work', 1, ['action_done']],
            ['in_work', 2, ['action_decline']],
            ['in_work', 3, []],
            ['done', 1, []]
        ];
    }

    public function testGetTaskMap()
    {
        $map = [
            'new' => 'Новая задача',
            'cancel' => 'Задача отменена',
            'in_work' => 'В работе',
            'done' => 'Задача выполнена',
            'failed' => 'Задача провалена',
            'action_cancel' => 'Отменить задачу',
            'action_respond' => 'Взять в работу',
            'action_done' => 'Выполнить задачу',
            'action_decline' => 'Отказаться',
            'action_choose' => 'Выбрать исполнителя'
        ];
        $this->assertEqualsCanonicalizing($map, $this->fixture->getTaskMap());
    }
}
