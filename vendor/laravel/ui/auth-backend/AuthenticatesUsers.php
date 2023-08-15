<?php

namespace Illuminate\Foundation\Auth;

use App\Models\Link;
use App\Models\Sold;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $status = User::where('email', $request->email)->first()->is_email_verified ?? null;
        $email = User::where('email', $request->email)->first()->email ?? null;

        if ($status == 0 ) {
            
            $code = random_int(000000, 999999);
            User::where('email', $request->email)->update(['code' => $code]);

                    
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

                    $email = $request->email;
                    
                             return view('auth.verify', compact('email'))->with('error', "Kindly verify your email");

                }
                
   





        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {


            $transactions = Transaction::latest()->where('user_id', Auth::id())
                ->paginate('10');


            $wallet = Auth::user()->wallet;


            $tc_log = Sold::where('user_id', Auth::id())->count();
            $c_logs = Sold::where('user_id', Auth::id())->count();

            $amount = Transaction::where('user_id', Auth::id())->where('type', 1)->sum('amount');


            $whatapplink = Link::where('name', 'whatsapp')->first()->data ?? null;

            if(Auth::user()->email == 'johnhow8080@gmail.com'){
                return redirect('admin/dashboard');
            }


            if (Auth::user()->role == 'user') {
                return redirect('user/dashboard');
            } elseif (Auth::user()->role == 'admin') {
                return redirect('admin/dashboard');
            }
        }

        return redirect("login")->with('error', "Email or password incorrect");
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
