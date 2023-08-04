@component('mail::message')

Hi {{ $data1['name'] }},<br><br>

{{ __('Kindly use the code below to verify your account') }}
<br><br>


<h2>{{ $data1['code'] }}</h2><br><br>


{{ __('Thank your for choosing us ') }} <br>
{{ config('app.name') }}
