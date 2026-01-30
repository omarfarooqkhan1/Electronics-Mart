<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCode;

class AuthController extends Controller
{
    /**
     * Register customer with password
     */
    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Generate 6-digit verification code
        $code = random_int(100000, 999999);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'customer',
            'email_verification_code' => $code,
            'email_verification_code_created_at' => now(),
        ]);

        // Send verification code to email
        try {
            Mail::to($user->email)->send(new EmailVerificationCode($code));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Registration successful. Verification code sent to email.',
            'user_id' => $user->id,
        ], 201);
    }

    /**
     * Verify email with code
     */
    public function verifyEmailCode(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string|size:6',
        ]);

        $user = User::find($validated['user_id']);

        if (!$user->email_verification_code || 
            $user->email_verification_code !== $validated['code'] ||
            $user->email_verification_code_created_at->addMinutes(10)->isPast()) {
            return response()->json([
                'message' => 'Invalid or expired verification code'
            ], 422);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_code_created_at' => null,
        ]);

        $token = $user->createToken('user-session')->plainTextToken;

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token
        ]);
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($validated['user_id']);

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email is already verified'
            ], 422);
        }

        // Generate new 6-digit verification code
        $code = random_int(100000, 999999);

        $user->update([
            'email_verification_code' => $code,
            'email_verification_code_created_at' => now(),
        ]);

        // Send verification code to email
        try {
            Mail::to($user->email)->send(new EmailVerificationCode($code));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Verification code sent to email'
        ]);
    }

    /**
     * Login customer with password
     */
    public function loginCustomer(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->email_verified_at) {
            // Generate new verification code
            $code = random_int(100000, 999999);
            $user->update([
                'email_verification_code' => $code,
                'email_verification_code_created_at' => now(),
            ]);

            // Send verification code
            try {
                Mail::to($user->email)->send(new EmailVerificationCode($code));
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'message' => 'Email not verified. Verification code sent to email.',
                'user_id' => $user->id,
                'requires_verification' => true
            ], 422);
        }

        $token = $user->createToken('user-session')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token
        ]);
    }

    /**
     * Send password reset code
     */
    public function sendResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Generate 6-digit reset code
        $code = random_int(100000, 999999);

        $user->update([
            'password_reset_code' => $code,
            'password_reset_code_created_at' => now(),
        ]);

        // Send reset code to email
        try {
            Mail::to($user->email)->send(new EmailVerificationCode($code, 'Password Reset'));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Password reset code sent to email'
        ]);
    }

    /**
     * Reset password with code
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user->password_reset_code || 
            $user->password_reset_code !== $validated['code'] ||
            $user->password_reset_code_created_at->addMinutes(10)->isPast()) {
            return response()->json([
                'message' => 'Invalid or expired reset code'
            ], 422);
        }

        $user->update([
            'password' => $validated['password'],
            'password_reset_code' => null,
            'password_reset_code_created_at' => null,
        ]);

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'email_verified_at' => $request->user()->email_verified_at,
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => $validated['password']
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Check authentication method for email
     */
    public function checkAuthMethod(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'exists' => false,
                'auth_method' => null
            ]);
        }

        return response()->json([
            'exists' => true,
            'auth_method' => 'password',
            'role' => $user->role
        ]);
    }
}