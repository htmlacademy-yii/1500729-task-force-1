<?php


namespace taskforce\app;


use yii\base\Widget;

class RatioWidget extends Widget
{
     public $ratio;
     private $class = '-rate big-rate';
     private $numberString;

     public function init()
     {
         parent::init(); // TODO: Change the autogenerated stub
         switch ($this->ratio) {
             case 1:
                 $this->numberString = 'one';
                 break;
             case 2:
                 $this->numberString = 'two';
                 break;
             case 3:
                 $this->numberString = 'three';
                 break;
             case 4:
                 $this->numberString = 'four';
                 break;
             case 5:
                 $this->numberString = 'five';
                 break;
         }
     }
     public function run()
     {
         parent::run(); // TODO: Change the autogenerated stub
         return '<p class="' . $this->numberString . '-rate big-rate">'. $this->ratio .'<span></span></p>';
     }
}