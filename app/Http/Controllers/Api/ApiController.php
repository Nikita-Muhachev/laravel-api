<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Resources\ApiResource;

class ApiController extends Controller
{

    const API_KEY = '7aee47130d2fbb9d6461faa29ece72f5';
    const API_URL = 'https://currate.ru/api/';
    /**
     * @var Model
     */
    protected $model;

    /**
     * @return mixed
     *
     */
    public function getExchangeRates()
    {

        $result = $this->getRates();
        if ($result['status'] !== '200') {
            return $this->sendError($result['message'], $result['status']);
        }
        $this->add($result);

        return (new ApiResource($result))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {

        $result = Api::all();
        if (!$result) {
            return $this->sendError(' Not found', 404);
        }
        return (new ApiResource($result))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @param array $request
     * @return mixed
     */
    protected function add($request)
    {
        $arFields = [];
        $i = 0;
        foreach ($request['data'] as $kay => $item) {
            $arFields[$i]['name'] = $kay;
            $arFields[$i]['value'] = $item;
            $arFields[$i]['created_at'] = date('Y-m-d H:i:s');
            $i++;
        }
        Api::insert($arFields);

        return true;
    }

    protected function getRates()
    {
        $pairs = implode(',', $this->getValut());
        $url = self::API_URL . '?get=rates&pairs=' . $pairs . '&key=' . self::API_KEY;
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ]);
        $response = $client->get($url);
        return $response->getBody()->getContents();
    }

    protected function getValut()
    {
        $result = [];
        $url = self::API_URL . '?get=currency_list&key=' . self::API_KEY;
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ]);
        $response = $client->get($url);
        $data = $response->getBody()->getContents();
        if ($data['status'] !== '200') {
            return $this->sendError($data['message'], $data['status']);
        }
        foreach ($data['data'] as $key => $elem) {
            if (strpos($elem, 'RUB') !== false) {
                $result[$key] = $elem;
            }
        }
        return $result;
    }
}