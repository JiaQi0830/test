<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class coinController extends Controller
{
    //
    public function index(){
        $coinResponse = Http::get('https://api.coingecko.com/api/v3/coins/list');
        $coinName = collect($coinResponse->json())->firstWhere('id', 'bitcoin');
        $USD_price = $this->getDetails('usd', 'bitcoin', 'current_price');
        $CNY_price = $this->getDetails('cny', 'bitcoin', 'current_price');
        $USD_priceChange = $this->getDetails('cny', 'bitcoin', 'price_change_24h');
        $USD_priceChangePercent = $this->getDetails('cny', 'bitcoin', 'price_change_percentage_24h');
        
        return view('coin',compact('coinName', 'USD_price', 'CNY_price', 'USD_priceChange', 'USD_priceChangePercent'));
    }

    private function getDetails($currency, $ids, $details){

        $priceResponse = Http::get("https://api.coingecko.com/api/v3/coins/markets?vs_currency={$currency}&ids={$ids}&order=market_cap_desc&per_page=100&page=1&sparkline=false");
        $result = collect($priceResponse->json())->first()[$details];

        return $result;
    }
}
