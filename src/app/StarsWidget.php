<?php


namespace taskforce\app;




use yii\base\Widget;
use yii\helpers\Html;

class StarsWidget extends Widget
{
    public $stars;
    private array $count = [1,2,3,4,5];
    private string $i = '';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        foreach ($this->count as $value) {
            if ($this->stars < $value) {
                $class = ' class="star-disabled"';
            }
            $this->i = $this->i . '<span' . $class . '></span>';
        }
    }

    public function run()
    {
        parent::run(); // TODO: Change the autogenerated stub
        return $this->i;
    }
}
