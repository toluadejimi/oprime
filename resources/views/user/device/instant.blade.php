@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> __('Dashboard'),'buttons'=>[
]])
@endsection
@section('content')

@if(Auth::user()->updated_at == null)

<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Alert!</strong> Please update your password on your profile page . <a class="text-white" href="profile">Click
    here to update</a>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif


@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
@if (session()->has('message'))
<div class="alert alert-success">
  {{ session()->get('message') }}
</div>
@endif
@if (session()->has('error'))
<div class="alert alert-danger">
  {{ session()->get('error') }}
</div>
@endif






<div class="card card-stats">
  <!-- Card body -->
  <div class="card-body">
    <div class="row">
      <div class="col">
        <h5 style="color: blue !important;" class="card-title text-uppercase text-muted mb-2">Welcome {{
          Auth::user()->name }}, </h5 <p> Welcome to Oprime, Verify all Countries and Services Numbers </p>
      </div>
    </div>
  </div>
</div>


<div class="row">


  <div class="col-xl-7 col-md-6">
    <div class="card">
      <div class"card-body">

        <div class="container p-3">
          <div class="alert alert-dark alert-dismissible fade show" role="alert">
            Country - <strong>{{ $c_selected }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="row">
            <div class="col-xl-4 col-md-6 mr-2">


              <label class="my-3">Choose country</label>
              <input class="form-control text-dark" type="text" id="search1" placeholder="Search country">
              <div style="height:200px; width:250%; overflow-y: scroll;">


                <table class="table table-borderless" id="table1">
                  <thead>



                  </thead>
                  <tbody>


                    @forelse ($countries as $item)

                    <tr>


                      <td><span class="flag-icon flag-icon-{{ $item->flag }}"> </span> <a class="text-dark"
                          href="search?code={{ $item->code}}">{{ Str::upper($item->name) ?? "Name"
                          }}</a></td>

                    </tr>

                    @empty
                    <tr colspan="20" class="text-center">No Record Found</tr>
                    @endforelse


                    </tr>
                  </tbody>
                </table>

              </div>
            </div>

            <div class="d-flex" style="height: 200px;">
              <div class="vr"></div>
            </div>



            <div class="col-xl-4 col-md-6">
              <label class="my-3">Choose Service</label>

              <input class="form-control text-dark" type="text" id="search2" placeholder="Search Services">

              <div style="height:200px; width:140%; overflow-y: scroll;">

                <table class="table table-borderless table-responsive" id="table2">
                  <thead>
                    <tr>


                    </tr>
                  </thead>
                  <tbody>


                    <tr>

                      @forelse ($services as $item)

                      @php

                      $rate = env('RATE');
                      $result = $item->price * $rate + env('CHARGE');

                      @endphp



                    <tr>
                      <td><strong class="badge badge-primary"> NGN {{ number_format($result) }}</strong> | <a
                          class="text-dark"
                          href="number?code={{ $item->code}}&service={{ $item->slug }}&p_code={{ $item->price }}">{{
                          Str::upper($item->service) ?? "Name"
                          }} | <strong class="badge badge-warning"> {{ $item->count }}</strong> </a></td>
                    </tr>




                    @empty
                    <tr colspan="20" class="text-center">No Record Found</tr>
                    @endforelse


                    </tr>
                  </tbody>
                </table>

              </div>
            </div>

          </div>

        </div>
      </div>




    </div>
  </div>



  <div class="col-xl-5 col-md-6">
    <div class="card">
      <div class"card-body">

        <style>
          body {
            background: #eee;
          }

          .chat-list {
            padding: 0;
            font-size: .8rem;
          }

          .chat-list li {
            margin-bottom: 10px;
            overflow: auto;
            color: #ffffff;
          }

          .chat-list .chat-img {
            float: left;
            width: 48px;
          }

          .chat-list .chat-img img {
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
            border-radius: 50px;
            width: 100%;
          }

          .chat-list .chat-message {
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
            border-radius: 50px;
            background: #5a99ee;
            display: inline-block;
            padding: 10px 20px;
            position: relative;
          }

          .chat-list .chat-message:before {
            content: "";
            position: absolute;
            top: 15px;
            width: 0;
            height: 0;
          }

          .chat-list .chat-message h5 {
            margin: 0 0 5px 0;
            font-weight: 600;
            line-height: 100%;
            font-size: .9rem;
          }

          .chat-list .chat-message p {
            line-height: 18px;
            margin: 0;
            padding: 0;
          }

          .chat-list .chat-body {
            margin-left: 20px;
            float: left;
            width: 70%;
          }

          .chat-list .in .chat-message:before {
            left: -12px;
            border-bottom: 20px solid transparent;
            border-right: 20px solid #5a99ee;
          }

          .chat-list .out .chat-img {
            float: right;
          }

          .chat-list .out .chat-body {
            float: right;
            margin-right: 20px;
            text-align: right;
          }

          .chat-list .out .chat-message {
            background: #fc6d4c;
          }

          .chat-list .out .chat-message:before {
            right: -12px;
            border-bottom: 20px solid transparent;
            border-left: 20px solid #fc6d4c;
          }

          .card .card-header:first-child {
            -webkit-border-radius: 0.3rem 0.3rem 0 0;
            -moz-border-radius: 0.3rem 0.3rem 0 0;
            border-radius: 0.3rem 0.3rem 0 0;
          }

          .card .card-header {
            background: #17202b;
            border: 0;
            font-size: 1rem;
            padding: .65rem 1rem;
            position: relative;
            font-weight: 600;
            color: #ffffff;
          }

          .content {
            margin-top: 40px;
          }
        </style>



        <div class="container content">
          <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
              @forelse ($sms as $data )
              <div class="card">


                <div class="card-header">{{ $data->phone_no ?? "Choose a country and service to receive sms" }} |  {{ $data->service }}  |   <div
                    class="mr-3" id="countdown{{ $data->id }}"> <span class="text-red" id="timer{{ $data->id }}">{{ $data->time ?? 50 }}</span>s</div>
                </div>

                <div class="card-body height3">
                  <ul class="chat-list">
                    <li class="in">

                      <div class="chat-img">
                        <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
                      </div>

                      <div class="chat-body mb-3">
                        <div class="chat-message">
                          <h5>Incoming SMS</h5>
                          <p class="text-white">{{ $data->response ?? "Waiting for sms" }}</p>
                          {{-- <div id="myDiv">Waiting for sms</div> --}}
                        </div>
                      </div>
                    </li>


                  </ul>

                  <div class="row mt-4">
                    <a  href="delete?order_id={{ $data->order_id }}" class="btn btn-sm btn-danger text-white">Delete</a>
                    <a id="startButton{{ $data->id }}" href="recheck?order_id={{ $data->order_id }}" class="btn btn-sm btn-success text-white">Recheck
                      SMS</a>
                  </div>
                </div>

                <script>
                  let countdownInterval;
                  let remainingTime = {{ $data->time ?? 50 }}; // Initial countdown time in seconds
          
                  function startCountdown() {
                      countdownInterval = setInterval(function () {
                          remainingTime--;
          
                          if (remainingTime < 0) {
                              clearInterval(countdownInterval);
                              document.getElementById('countdown{{ $data->id }}').textContent = 'Time Expired';
          
                              // Send a POST request when the countdown reaches zero
                              sendPostRequest();
                          } else {
                              document.getElementById('timer{{ $data->id }}').textContent = remainingTime;
                          }
                      }, 1000); // Update the countdown every 1 second
                  }
          
                  function sendPostRequest() {
                      // Create an XMLHttpRequest or use the fetch API to send a POST request
                      const xhr = new XMLHttpRequest();
                      xhr.open('POST', 'https://example.com/api/endpoint', true);
          
                      // Set up any necessary headers or data for the POST request
                      // xhr.setRequestHeader('Content-Type', 'application/json');
                      // const data = JSON.stringify({ key: 'value' });
          
                      xhr.onreadystatechange = function () {
                          if (xhr.readyState === 4 && xhr.status === 200) {
                              console.log('POST request was successful.');
                          }
                      };
          
                      // Send the POST request
                      xhr.send(/*data*/);
                  }
          
                  // Start the countdown and send the POST request on page load
                  window.addEventListener('load', startCountdown);
              </script>


        

              </div>
              @empty

              <div class="card-header">{{"Choose a country and service to receive sms" }}</div>
              <div class="card-body height3">

              </div>



              @endforelse
            </div>

          </div>
        </div>






        {{-- @if($phone_no != null)
        <script>
          document.getElementById('soundBtn').style.visibility='hidden';

            function performSound(){
            var soundButton = document.getElementById("soundBtn");
            soundButton.click();
            }

          

       

          // Function to refresh the div's content
            function refreshDiv() {
              var div = document.getElementById("myDiv");
              var xhr = new XMLHttpRequest();
      
              // Define the URL you want to fetch data from
              var url = "{{ url('') }}/api/check-number";
      
              xhr.open("POST", url, true);
              xhr.setRequestHeader("Content-Type", "application/json");
      
              // Define the POST data
              var postData = JSON.stringify({
                  number: {{ $phone_no }},
                  user_id: {{ Auth::user()->id }},
              });

      
              xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                  if (xhr.status === 200) {
                      // If the response is successful and status is not 0, update the div with the response text
                      if (xhr.responseText !== 'null') {
                      
                        div.innerHTML = xhr.responseText;

                      } else {
                       
                          div.innerHTML = "Waiting for sms";
                      }
                  } else {
                      // Handle error cases
                      div.innerHTML = "Error: Unable to fetch data";
                  }
              }
              };
      
              xhr.send(postData);

              console.log(xhr);
            }

            


            // Call the refreshDiv function every second (1000 milliseconds)
            setInterval(refreshDiv, 3000);


        </script>
        @endif --}}




      </div>
    </div>




  </div>
</div>
</div>



<div class="col-xl-12 col-md-6 p-3">
  <h5 class="p-3">Recent SMS Transactions</h5>

  <div class="card">



    <div class"card-body">


      <table class="table table-responsive table-borderless">

        <thead>
          <tr>
            <th>Order ID</th>
            <th>Code</th>
            <th>Phone Number</th>
            <th>SMS Message</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date/Time</th>

          </tr>
        </thead>


        @foreach ($sms_history as $data)

        <tbody>

          <tr>

            <td>
              {{ $data->order_id }}
            </td>
            <td>
              {{ $data->code }}
            </td>
            <td>
              {{ $data->phone_no }}
            </td>
            <td>
              {{ $data->response ?? "Waiting for sms"}}
            </td>

            <td>
              {{ number_format($data->amount, 2) }}
            </td>




            <td>
              {{$data->created_at}}
            </td>


          </tr>


        </tbody>

        @endforeach








      </table>




    </div>
  </div>
</div>

















@endsection
@push('js')
<script src="{{ asset('assets/vendor/chart.js/dist/chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/canvas-confetti/confetti.browser.min.js') }}"></script>
@endpush
@push('bottomjs')
<script src="{{ asset('assets/js/pages/user/dashboard.js') }}"></script>
@endpush