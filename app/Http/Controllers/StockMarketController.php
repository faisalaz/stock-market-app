<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Rules\SymbolExists;
use Illuminate\Support\Facades\Config;


class StockMarketController extends Controller
{
    
    public function index()
    {
        return view('stock.index');
    }

    public function fetchData(Request $request)
    {
        $validatedData = $request->validate(['symbol' => ['required', new SymbolExists],
            'start_date' => ['required', 'date', 'before_or_equal:end_date', 'before_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', 'before_or_equal:today'],
            'email' => 'required|email',
        ], [
            'symbol.required' => 'Company Symbol is required',
            'start_date.required' => 'Start Date is required',
            'start_date.date' => 'Start Date must be a valid date',
        ]);

        $symbol = $validatedData['symbol'];
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];
        try{
            $client = new Client([
                'headers' => [
                    'X-RapidAPI-Key' => Config::get('services.api.key'),
                    'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com',
                ],
            ]);
            $response = $client->get("https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=$symbol&region=US&from=$startDate&to=$endDate");
            $data = json_decode($response->getBody(), true)['prices'];
        }

        catch (\Exception $e) {
            // Log the exception or handle it appropriately
            $data = [];
        }

        return view('stock.result', compact('data'));
    }
}
