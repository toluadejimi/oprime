<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Digitz | Toolz Bank</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{url('')}}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{url('')}}/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{url('')}}/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{url('')}}/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Email Verification</h3>
                <p class="text-left mb-3">OTP verification code has been sent to {{$email ?? "toolz@mail.com"}}</p>


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


                <form action="verify-code" method="post">
                @csrf
                
                  <div class="form-group">
                    <label>Enter Code</label>
                    <input type="number" placeholder="Enter OTP code here" name="code" required class="form-control text-white p_input">
                    <input type="email" name="email" hidden value="{{ $email }}" class="form-control p_input">
                  </div>
               
                  <div class="text-center">
                    <button type="submit" class="btn btn-warning  btn-block enter-btn">Verify</button>
                  </div>
                  <p class="text-left mb-3">Wait for about 5mins, If code is not in INBOX, Kindly check your spam folder for OTP code. <br><a href="resend-code?email={{ $email }}">Request another code</a></p>

                  <p class="sign-up text-center">Already had an account?<a href="login">Login</a></p>
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{url('')}}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{url('')}}/assets/js/off-canvas.js"></script>
    <script src="{{url('')}}/assets/js/hoverable-collapse.js"></script>
    <script src="{{url('')}}/assets/js/misc.js"></script>
    <script src="{{url('')}}/assets/js/settings.js"></script>
    <script src="{{url('')}}/assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>