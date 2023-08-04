@component('mail::message')

Dear {{ $data1['name'] }},<br><br>

{{ __('Below are the details of your log') }}
<br><br>
{{ __('Log Data') }} <br>
<strong>{{ $data1['logdata'] }}</strong>.<br><br>
{{ __('Area Code ') }}  <br>
<strong>{{ $data1['area_code'] }}</strong>.<br><br>

<h3 style="color: red;"> DONT USE VPN TO LOGIN THE GOOGLE VOICE  !!! DONT SAY I DIDNT WARN YOU</h3>
  <h2 style="color: red;"> HOLD DONT COPY THE LOG YET KINDLY READ THE RULES FIRST !!!</h2>
  <br>
  <button style="color:white; border:none; border-radius:20px; padding: 8px; background-color: red;"  type="button">  <a style="color: #E5F7FE;" class="nav-link {{ Request::is('user/rules*') ? 'active' : '' }}" href="{{ url('https://oprime.com.ng/wordpress/our-rules') }}">
      <i class="fi fi-rs-paper-plane"></i>
      <span class="nav-link-text">{{ __('CLICK ME TO READ MY RULES DONT SAY I DIDNT TELL YOU') }}</span>
    </a></button>

<br>
<br>
<br>

{{ __('Thank You') }}
 <br><br>
{{ config('app.name') }}
