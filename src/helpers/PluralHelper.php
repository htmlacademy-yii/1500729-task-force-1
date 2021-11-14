<?php
namespace taskforce\helpers;

use Yii;

class PluralHelper
{
    public static function Plural(array $words, ?int $count) {
        if (!$count) {
            $count = 0;
        }
        return Yii::$app->i18n->format(
            '{n, plural, =0{0 '.$words[0]. '} =1{1 '.$words[1]. '}
                                    one{# '.$words[2].'} few{# '.$words[3].'}
                                     many{# '.$words[4].'} other{# '.$words[5].'}}',
            ['n' => $count],
            'ru_RU');
    }
}
