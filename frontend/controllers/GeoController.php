<?php

namespace frontend\controllers;

use GuzzleHttp\Client;
use Yii;
use yii\caching\TagDependency;
use yii\web\Response;

class GeoController extends SecuredController
{
    /**
     * @param string $query
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionIndex(string $query): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $redisQuery = md5($query);
        if ($redisData = Yii::$app->cache->get($redisQuery)) {
            return $this->asJson($redisData);
        } else {
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
            Yii::$app->cache->set($redisQuery, $data, 86400, new TagDependency(['tags' => 'geocoder']));
            return $this->asJson($data);
        }
    }

    /**
     * @param int $location_id
     */
    public function actionLocation(int $location_id): void
    {
        Yii::$app->session->set('location_id', $location_id);
    }
}
