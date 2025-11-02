<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // إذا مسجل دخول وهو admin، حوله للوحة التحكم
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // إذا ليس admin، اعمل logout
            Auth::logout();
        }

        return view('admin.auth.login');
    }

    /**
     * Handle login request
     */
    public function adminLogin(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'البريد الإلكتروني أو اسم المستخدم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // تحقق من نوع المستخدم
            if ($user->type !== 'admin') {
                Auth::logout();
                
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالدخول إلى لوحة التحكم'
                ], 403);
            }
            
            // إعادة توليد session للأمان
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'redirect' => route('admin.dashboard')
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'بيانات الاعتماد غير صحيحة. يرجى التحقق من البريد الإلكتروني وكلمة المرور.'
        ], 401);
    }

    /**
     * Handle logout
     */
    public function adminLogout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // تأكد من استخدام الاسم الصحيح للـ route
        return redirect()->route('admin.login-view')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}