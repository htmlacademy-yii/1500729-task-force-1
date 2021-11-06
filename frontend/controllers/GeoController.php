<?php


namespace frontend\controllers;


use GuzzleHttp\Client;

use Yii;
use yii\web\Response;

class GeoController extends SecuredController
{
    public function actionIndex($query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/'
        ]);
        $response = $client->request('GET', '1.x', [
            'query' => ['apikey' => Yii::$app->params['yandexGeocoder'],
                'geocode' => $query,
                'format' => 'json']
        ]);
        $content = $response->getBody()->getContents();
        $response_data = json_decode($content, true);
        $data = [];
        foreach ($response_data['response']['GeoObjectCollection']['featureMember'] as $item) {
            $data[] = ["adress" => $item['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'],
                'coordinates' => $item['GeoObject']['Point']['pos']];
        }

        return $this->asJson($data);
    }
}
