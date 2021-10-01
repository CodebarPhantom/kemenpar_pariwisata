<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Tourism\TourismInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use DB;

class OpenWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dinas:open-weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Weather By API Open Weather';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        
        $tourismInfos = TourismInfo::orderBy('updated_at','DESC')->limit(30)->get();

        DB::beginTransaction();
        try {     
            foreach ($tourismInfos as $tourismInfo) {
                try {
                    $getOpenWeather = [];

                    $httpClient = new Client(['base_uri' => env('OPENWEATHER_URL')]);
                    $response = $httpClient->request('GET', 'weather?lat='.$tourismInfo->latitude.'&lon='.$tourismInfo->longitude.'&appid='.env('OPENWEATHER_ID').'&units='.env('OPENWEATHER_UNITS').'&lang='.env('OPENWEATHER_LANG').'');
                    $codeResponse = $response->getStatusCode();
                    $dataWeather = json_decode($response->getBody());
                    
                    //Log::debug($dataWeather->weather[0]->description);
                   //dd('test');
                   $getOpenWeather = [
                        'description'=>$dataWeather->weather[0]->description,
                        'icon'=>'http://openweathermap.org/img/wn/'.$dataWeather->weather[0]->icon.'@2x.png',
                        'temp'=>$dataWeather->main->temp,
                        'feels_like'=>$dataWeather->main->feels_like,
                   ];
                    $tourismInfo->open_weather = json_encode($getOpenWeather);
                    $tourismInfo->save();
                } catch (ConnectException $e) {
                    Log::notice($e->getMessage());
        
                } catch (Exception $e) {
                    report($e);
                }
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            report($e);
        }

    }
}
