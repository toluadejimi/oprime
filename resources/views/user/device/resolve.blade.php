@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title'=>__('Buy Log'),
'buttons'=>[
[

]
]])
@endsection
@section('content')
<div class="row justify-content-center">
   <div class="col-12">


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
                 
                  <h5 class="card-title text-uppercase text-muted mb-2">Welcome {{ Auth::user()->name }}, </h5>
                  <p> Resolve transactions issues here</p>
               </div>
               <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                     <i class="fas fa-server"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-12 col-md-6 mb-4">

      
      </div>



      <!-- <div class="row"> -->


         <div class="col-xl-12 col-md-6">

         
            <div class="card card-stats">
               <!-- Card body -->
               <div class="card-body">
              
                  <h4 class=" mb-5 my-3">If you have been debited and not credited, You can use your session ID to resolve the transaction.</h4>


                  <form action="resolve-now" method="post">
                     @csrf 

                     <div class="row">

                     <div class="col-xl-6 col-md-6">
                        <div class="form-group mb-3">
                           <label>Enter Session ID</label>
                          <input type="number" required name="session_id" class="form-control">
                        </div>
                     </div>

                    
                     

                   

                     <div class="col-xl-6 col-md-6">

                       {{-- <div class="my-1">
                           <p >Total Price: <strong id="totalPrice">NGN 0</strong></p>
                        </div> --}}

                        <div>
                           <button type="submit" class="btn btn-outline-primary my-4 submit-button float-left">{{ __('Resolve') }}</button>
                        </div>
                     </div>

                  
                  </div>

                  </form>

                  <div>


</div>

                 


               </div>
            </div>
         </div>

       

      </div>



   </div>


  

</div>
</div>

<input type="hidden" id="base_url" value="{{ url('/') }}">
@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/device.js') }}"></script>
@endpush