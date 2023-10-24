<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SmsHistory;
use App\Models\Sold;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Smstransaction;
use App\Models\Schedulemessage;
use App\Models\Contact;
use App\Models\Link;
use App\Models\OnlinesimCounrtry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;

class DashboardController extends Controller
{
    public function index(request $request)
    {
        
        $transactions = Transaction::latest()->where('user_id', Auth::id())
        ->paginate('10');


        $tc_log = Sold::where('user_id', Auth::id())->count();
        $c_logs = Sold::where('user_id', Auth::id())->count();


        $whatapplink = Link::where('name', 'whatsapp')->first()->data ?? null; 

        $adxtext = Menu::where('id', 2)->first()->data ?? null;
       
        return view('user.dashboard', compact('transactions', 'whatapplink', 'c_logs', 'tc_log', 'request', 'adxtext'));
    }

    public function dashboardData()
    {
        $data['devicesCount'] = Device::where('user_id',Auth::id())->count();
        $data['messagesCount'] = Smstransaction::where('user_id',Auth::id())->count();
        $data['contactCount'] = Contact::where('user_id',Auth::id())->count();
        $data['scheduleCount'] = Schedulemessage::where('status','pending')->where('user_id',Auth::id())->count();
        
        $data['devices'] = Device::where('user_id',Auth::id())->withCount('smstransaction')->orderBy('status','DESC')->latest()->get()->map(function($rq){
                $map['uuid']= $rq->uuid;
                $map['name']= $rq->name;
                $map['status']= $rq->status;
                $map['phone']= $rq->phone;
                $map['smstransaction_count']= $rq->smstransaction_count;
                return $map;
        });
        $data['messagesStatics'] = $this->getMessagesTransaction(7);
        $data['typeStatics'] = $this->messagesStatics(7);
        $data['chatbotStatics'] = $this->getChatbotTransaction(7);

        
        return response()->json($data);

    }

    public function getMessagesTransaction($days)
    {
       $statics= Smstransaction::query()->where('user_id',Auth::id())
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, count(*) smstransactions')
                ->groupBy('date')
                ->get();

        return $statics;
                
    }

    public function getChatbotTransaction($days)
    {
        $statics= Smstransaction::query()
                ->where('user_id',Auth::id())
                ->where('type','chatbot')
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('date(created_at) date, count(*) smstransactions')
                ->groupBy('date')
                ->get();

        return $statics;
    }

    public function messagesStatics($days)
    {
        $statics= Smstransaction::query()->where('user_id',Auth::id())
                ->whereDate('created_at', '>', Carbon::now()->subDays($days))
                ->orderBy('id', 'asc')
                ->selectRaw('type type, count(*) smstransactions')
                ->groupBy('type')
                ->get();

        return $statics;
    }

	public function instant()
	{

        $key = env('ONSIM');

        $data['countries'] = OnlinesimCounrtry::all();



        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onlinesim.io/api/getNumbersStats.php?apikey=$key&country=1&lang=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);


        $data['c_selected'] = "USA";

        $data['phone_no'] = null;


        $data['services'] = $var->services ?? null;

        $data['sms_history'] = SmsHistory::where('user_id', Auth::id())->get();

        
        // $countries = $var->countries;

        // foreach( $countries as $value){

        //     $model = new OnlinesimCounrtry();
        //     $model->name = $value->original;
        //     $model->code = $value->code;
        //     $model->save();


        // }



		return view('user.device.instant', $data);
	}


    public function search(request $request){



        $key = env('ONSIM');

        $data['countries'] = OnlinesimCounrtry::all();
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onlinesim.io/api/getNumbersStats.php?apikey=$key&country=$request->code&lang=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer T9x3DDM65LPgtZq-URtp49wG-wSP2XtK8-uV9r96Zd-Jmzq9U92yxTL8zz'
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);





        $data['c_selected'] = OnlinesimCounrtry::where('code', $request->code)->first()->name;
        $data['phone_no'] = null;
        $data['services'] = $var->services ?? null;
        $data['sms_history'] = SmsHistory::where('user_id', Auth::id())->get();

        return view('user.device.instant', $data);






    }




    public function get_number(request $request){



        $key = env('ONSIM');

        $data['countries'] = OnlinesimCounrtry::all();


        $rate = 1204;
        $result = $request->p_code * $rate;

        if($result > Auth::user()->wallet){

            return back()->with('error', 'Insufficient Funds, fund your wallet');

        }

        
        $h_amount = User::where('id', Auth::id())->decrement('wallet', $result);




        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onlinesim.io/api/getNum.php?apikey=$key&service=$request->service&country=$request->code&number=boolean&lang=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json'
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);

        $trx = new SmsHistory();
        $trx->order_id = $var->tzid;
        $trx->user_id = Auth::id();
        $trx->phone_no = $var->number;
        $trx->country = $var->country;
        $trx->service = $var->service;
        $trx->amount = $result;
        $trx->save();


        $data['phone_no']= $var->number;





        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onlinesim.io/api/getNumbersStats.php?apikey=$key&country=$request->code&lang=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer T9x3DDM65LPgtZq-URtp49wG-wSP2XtK8-uV9r96Zd-Jmzq9U92yxTL8zz'
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);



    


        $data['c_selected'] = OnlinesimCounrtry::where('code', $request->code)->first()->name;
        $data['phone'] = null;
        $data['services'] = $var->services ?? null;
        $data['sms_history'] = SmsHistory::where('user_id', Auth::id())->get();

        return view('user.device.instant', $data);






    }




    public function order_recheck(request $request){



        $key = env('ONSIM');

        $data['countries'] = OnlinesimCounrtry::all();


        $rate = 1204;
        $result = $request->p_code * $rate;

        if($result > Auth::user()->wallet){

            return back()->with('error', 'Insufficient Funds, fund your wallet');

        }

        
        $h_amount = User::where('id', Auth::id())->decrement('wallet', $result);




        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onlinesim.io/api/getState.php?apikey=$key&tzid=$request->orcer_id&message_to_code=0&orderby=asc&msg_list=1&clean=1&lang=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json'
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);


        dd($var);

        $trx = new SmsHistory();
        $trx->order_id = $var->tzid;
        $trx->user_id = Auth::id();
        $trx->phone_no = $var->number;
        $trx->country = $var->country;
        $trx->service = $var->service;
        $trx->amount = $result;
        $trx->save();


        $data['phone_no']= $var->number;





        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer T9x3DDM65LPgtZq-URtp49wG-wSP2XtK8-uV9r96Zd-Jmzq9U92yxTL8zz'
        ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);



    


        $data['c_selected'] = OnlinesimCounrtry::where('code', $request->code)->first()->name;
        $data['phone'] = null;
        $data['services'] = $var->services ?? null;
        $data['sms_history'] = SmsHistory::where('user_id', Auth::id())->get();

        return view('user.device.instant', $data);






    }













}
