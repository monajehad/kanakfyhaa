@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.blankLayout')

@section('title', 'تسجيل الدخول')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
  <!-- إضافة Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/pages-auth.js'])
  <!-- تحميل jQuery أولاً (مطلوب لـ Toastr) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- ثم Toastr -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- ثم Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  
  <script>
    // إعدادات Toastr
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-center",
      "timeOut": "3000",
      "extendedTimeOut": "1000",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('formAuthentication');
      const loginButton = document.getElementById('loginButton');
      const originalButtonText = loginButton.innerHTML;
      const loginErrorMessage = document.getElementById('login-error-message');
      const emailError = document.getElementById('email-error');
      const passwordError = document.getElementById('password-error');
      
      function setLoading(isLoading) {
        const inputs = form.querySelectorAll('input, button');
        
        if (isLoading) {
          loginButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري تسجيل الدخول...';
          inputs.forEach(input => input.disabled = true);
        } else {
          loginButton.innerHTML = originalButtonText;
          inputs.forEach(input => input.disabled = false);
        }
      }
      
      function clearErrors() {
        // Hide general error message
        loginErrorMessage.classList.add('d-none');
        loginErrorMessage.textContent = '';
        
        // Clear field-specific errors
        emailError.textContent = '';
        document.getElementById('email').classList.remove('is-invalid');
        
        passwordError.textContent = '';
        document.getElementById('password').classList.remove('is-invalid');
      }
      
      function showErrors(errors) {
        // Show field-specific errors
        if (errors.email) {
          emailError.textContent = Array.isArray(errors.email) ? errors.email[0] : errors.email;
          document.getElementById('email').classList.add('is-invalid');
        }
        
        if (errors.password) {
          passwordError.textContent = Array.isArray(errors.password) ? errors.password[0] : errors.password;
          document.getElementById('password').classList.add('is-invalid');
        }
        
        // Show general error if available
        if (errors.message) {
          loginErrorMessage.textContent = errors.message;
          loginErrorMessage.classList.remove('d-none');
        }
        
        // Check if there are any other errors not specifically handled
        const otherErrors = Object.keys(errors).filter(key => key !== 'email' && key !== 'password' && key !== 'message');
        if (otherErrors.length > 0) {
          // Combine other errors into the general error message
          const errorMessages = otherErrors.map(key => {
            const error = errors[key];
            return Array.isArray(error) ? error[0] : error;
          });
          
          if (errorMessages.length > 0) {
            loginErrorMessage.textContent = errorMessages.join(' ');
            loginErrorMessage.classList.remove('d-none');
          }
        }
      }
      
      loginButton.addEventListener('click', function() {
        // Clear previous errors
        clearErrors();
        
        // Client-side validation
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const rememberMe = document.getElementById('remember-me').checked;
        
        let hasErrors = false;
        
        // Check for empty email
        if (!email.trim()) {
          emailError.textContent = 'البريد الإلكتروني أو اسم المستخدم مطلوب';
          document.getElementById('email').classList.add('is-invalid');
          hasErrors = true;
        }
        
        // Check for empty password
        if (!password.trim()) {
          passwordError.textContent = 'كلمة المرور مطلوبة';
          document.getElementById('password').classList.add('is-invalid');
          hasErrors = true;
        }
        
        // Stop if there are validation errors
        if (hasErrors) {
          return;
        }
        
        // Set loading state
        setLoading(true);
        
        axios.post('{{ route("admin.login") }}', {
          email: email,
          password: password,
          remember: rememberMe,
          _token: '{{ csrf_token() }}'
        })
        .then(function(response) {
          if (response.data.success) {
            // Display success message
            toastr.success('تم تسجيل الدخول بنجاح');
            
            // Redirect to dashboard after short delay
            setTimeout(function() {
              window.location.href = response.data.redirect;
            }, 1000);
          } else {
            setLoading(false);
            
            // Show validation errors if available
            if (response.data.errors) {
              showErrors(response.data.errors);
            } else {
              // Show general error message
              toastr.error(response.data.message || 'فشل تسجيل الدخول. يرجى التحقق من بيانات الاعتماد الخاصة بك.');
            }
          }
        })
        .catch(function(error) {
          setLoading(false);
          console.error(error);
          
          // Handle validation errors
          if (error.response && error.response.data) {
            if (error.response.data.errors) {
              showErrors(error.response.data.errors);
            }
            
            // Show message from backend if available
            if (error.response.data.message) {
              toastr.error(error.response.data.message);
            } else {
              // Fallback error message
              toastr.error('فشل تسجيل الدخول. يرجى التحقق من بيانات الاعتماد الخاصة بك.');
            }
          } else {
            // Show general error message for network errors
            toastr.error('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
          }
        });
      });
      
      // Allow Enter key to submit
      form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          loginButton.click();
        }
      });
    });
  </script>
@endsection

@section('content')
  <div class="position-relative" dir="rtl">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
      <div class="authentication-inner py-6">
        <!-- Login -->
        <div class="card p-md-7 p-1">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros')</span>
              <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <!-- /Logo -->

          <div class="card-body mt-1">
            <h4 class="mb-1">مرحباً بك في {{ config('variables.templateName') }}! 👋</h4>
            <p class="mb-5">يرجى تسجيل الدخول إلى حسابك وبدء المغامرة</p>

            <form id="formAuthentication" class="mb-5" >
              <!-- General error message area -->
              <div class="alert alert-danger d-none mb-3" id="login-error-message"></div>
              <div class="form-floating form-floating-outline mb-5 form-control-validation">
                <input type="text" class="form-control" id="email" name="email-username"
                  placeholder="أدخل البريد الإلكتروني أو اسم المستخدم" autofocus />
                <label for="email">البريد الإلكتروني أو اسم المستخدم</label>
                <div class="invalid-feedback" id="email-error"></div>
              </div>
              <div class="mb-5">
                <div class="form-password-toggle form-control-validation">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" id="password" class="form-control" name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                      <label for="password">كلمة المرور</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i
                        class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                  </div>
                  <div class="invalid-feedback" id="password-error"></div>
                </div>
              </div>
              <div class="mb-5 d-flex justify-content-between mt-5">
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" id="remember-me" />
                  <label class="form-check-label" for="remember-me"> تذكرني </label>
                </div>
              
              </div>
              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" type="button" id="loginButton">تسجيل الدخول</button>
              </div>
            </form>

         
           
          </div>
        </div>
        <!-- /Login -->
        <img alt="mask"
          src="{{ asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['theme'] . '.png') }}"
          class="authentication-image d-none d-lg-block"
          data-app-light-img="illustrations/auth-basic-login-mask-light.png"
          data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
      </div>
    </div>
  </div>
@endsection