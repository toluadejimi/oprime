<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Digitz | Toolz Bank</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{url('')}}/public/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{url('')}}/public/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Change Email</h3>


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


                <form action="email-change" method="post">
                @csrf
                
                  <div class="form-group">
                    <label>Enter Old Email</label>
                    <input type="email" placeholder="Enter your old email here" name="email" required class="form-control text-white p_input">
                  </div>


                  <div class="form-group">
                    <label>Enter New Email</label>
                    <input type="email" placeholder="Enter New email here" name="new_email" required class="form-control text-white p_input">
                  </div>
               
                  <div class="text-center">
                    <button type="submit" class="btn btn-warning  btn-block enter-btn">Change your Email</button>
                  </div>

                  <p class="sign-up text-center">Already had an account?<a href="login">Login</a></p>

                  <p class="sign-up text-center">Click here to<a href="register">Register</a></p>


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
    <script src="{{url('')}}/public/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{url('')}}/public/assets/js/off-canvas.js"></script>
    <script src="{{url('')}}/public/assets/js/hoverable-collapse.js"></script>
    <script src="{{url('')}}/public/assets/js/misc.js"></script>
    <script src="{{url('')}}/public/assets/js/settings.js"></script>
    <script src="{{url('')}}/public/assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>