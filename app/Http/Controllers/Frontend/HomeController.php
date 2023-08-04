<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\Option;
use App\Models\Plan;
use App\Traits\Seo;
use Cache;
use Mail;

class HomeController extends Controller
{
    use Seo;

    /**
     * Display a home page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('auth.login');

        // $brands = Category::where('type','brand')->where('status',1)->latest()->get();



        // $testimonials =  Post::where('type','testimonial')->with('preview','excerpt')->latest()->get();

        // $faqs = Post::where('type','faq')->where('featured',1)->where('lang',app()->getLocale())->with('excerpt')->latest()->get();

        // $plans = Plan::where('status',1)->where('is_featured',1)->latest()->get();

        // $this->metadata('seo_home');

        // $home = get_option('home-page',true,true);
        // $features_area =  $home->brand->status ?? 'active';
        // $brand_area = $home->brand->status ?? 'active';
        // $account_area = $home->account_area->status ?? 'active';

        // $heading = str_replace('<strong>', "<span>", $home->heading->title ?? '');
        // $heading = str_replace('</strong>', "</span>", $heading ?? '');  

        // return view('frontend.index',compact('brands','testimonials','faqs','plans','home','features_area','brand_area','account_area','heading'));
    }


    /**
     * Display  team page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function team()
    {
        $teams = Post::where('type', 'team')->with('excerpt', 'preview')->latest()->get()->map(function ($query) {
            $data['name']     = $query->title;
            $data['position'] = $query->slug;
            $data['avatar']   = $query->preview->value ?? '';
            $data['socials']  = json_decode($query->excerpt->value ?? '');

            return $data;
        });
        $faqs = Post::where('type', 'faq')->where('featured', 1)->where('lang', app()->getLocale())->with('excerpt')->latest()->get();

        $this->metadata('seo_team');

        return view('frontend.team', compact('teams', 'faqs'));
    }


    /**
     * Display  about page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        $about = get_option('about', true);
        $counter = get_option('counter', true);
        $descriptions = explode('<br>', $about->description ?? '');
        $facilities = explode(',', $about->facilities ?? '');

        $features = Post::where('type', 'feature')->where('featured', 1)->where('lang', app()->getLocale())->with('preview', 'excerpt')->latest()->take(6)->get();

        $teams = Post::where('type', 'team')->with('excerpt', 'preview')->latest()->get()->map(function ($query) {
            $data['name']     = $query->title;
            $data['position'] = $query->slug;
            $data['avatar']   = $query->preview->value ?? '';
            $data['socials']  = json_decode($query->excerpt->value ?? '');

            return $data;
        });

        $faqs = Post::where('type', 'faq')->where('featured', 1)->where('lang', app()->getLocale())->with('excerpt')->latest()->get();

        $plans = Plan::where('status', 1)->where('is_featured', 1)->latest()->get();

        $this->metadata('seo_about');

        return view('frontend.about', compact('about', 'counter', 'descriptions', 'facilities', 'features', 'teams', 'faqs', 'plans'));
    }


    /**
     * Display  faq page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        $faqs = Post::where('type', 'faq')->where('lang', app()->getLocale())->with('excerpt')->latest()->get();

        $this->metadata('seo_faq');

        return view('frontend.faq', compact('faqs'));
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function page($slug)
    {
        $page = Post::where('status', 1)->where('type', 'page')->with('seo', 'description')->where('slug', $slug)->first();
        abort_if(empty($page), 404);

        $seo = json_decode($page->seo->value ?? '');

        $meta['title'] = $seo->title ?? '';
        $meta['description'] = $seo->description ?? '';
        $meta['tags'] = $seo->tags ?? '';

        $this->pageMetaData($meta);

        return view('frontend.page', compact('page'));
    }





    public function create(request $request)
    {

        $em = User::where('email', $request->email)->first()->email ?? null;

        $email = $request->email;
        $code = random_int(000000, 999999);



        if ($em == $request->email) {
            return back()->with('error', 'Email has been taken');
        }


        $usr = new User();
        $usr->name = $request->name;
        $usr->email = $request->email;
        $usr->code = $code;
        $usr->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $usr->save();



        $data = array(

            'fromsender' => 'admin@oprime.com.ng', 'Oprime',
            'subject' => "OTP Verification Code",
            'toreceiver' => $request->email,
            'code' => $code,
            'name' => $request->name,



        );
        Mail::send('mails.verify', ["data1" => $data], function ($message) use ($data) {
            $message->from($data['fromsender']);
            $message->to($data['toreceiver']);
            $message->subject($data['subject']);
        });
        $message =  "New User Created |" . $request->email . "|" . $request->name;
        send_notification($message);

        return view('auth.verify', compact('email'))->with('message', 'Account has been created please verify your account');
    }

    public function verify(request $request)
    {
        $email = $request->email;
        return view('auth.verify', compact('email'));
    }


    public function change_email(request $request)
    {
        return view('auth.change');
    }


    public function email_change(request $request)
    {

        $old_email = $request->email;
        $new_email = $request->new_email;

        $get_email = User::where('email', $old_email)->first()->email ?? null;

        if ($old_email == $get_email) {

            $old_email = $request->email;
            $email = $request->new_email;

            User::where('email', $old_email)->update(['email' => $new_email]);
            return view('auth.verify', compact('email'));
        } else {

            return back()->with('error', 'Your email can not be found on our system, Kindly register new account');
        }









        return view('auth.change');
    }




    public function verify_code(request $request)
    {


        $code = $request->code;
        $email = $request->email;

        $usr = User::where('email', $email)->first() ?? null;
        if ($usr->code == $code) {

            $email = $request->email;
            User::where('email', $email)->update([

                'is_email_verified' => 1,
                'status' => 1

            
            ]);

            $usermessage = "Your account has been fully verified. Login to continue";
            $message = "User Verifired on Oprime site | $request->name";
            send_notification($message);

            return redirect('login')->with('message', "$usermessage");
        }

        $email = $request->email;
         $request->session()->flash('successMsg','OTP code invalid, please try again!'); 
        return view('auth.verify')->with('email', $email)->with('error', 'OTP code invalid, please try again');
    }


    public function resend_code(request $request)
    {


        $code = random_int(000000, 999999);
        $email = $request->email;


        User::where('email', $email)->update(['code' => $code]);


        $data = array(

            'fromsender' => 'admin@oprime.com.ng', 'Oprime',
            'subject' => "OTP Verification Code",
            'toreceiver' => $request->email,
            'code' => $code,
            'name' => $request->name,


        );


        Mail::send('mails.verify', ["data1" => $data], function ($message) use ($data) {
            $message->from($data['fromsender']);
            $message->to($data['toreceiver']);
            $message->subject($data['subject']);
        });



        return view('auth.verify', compact('email'))->with('message', "OTP code is has been resent to $email");
    }
}
