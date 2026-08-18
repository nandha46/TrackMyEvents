<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PrivateLockController extends Controller
{
    /**
     * Verify the 4-digit PIN entered by user.
     */
    public function verifyPin(Request $request): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'string', 'size:4'],
        ]);

        $user = $request->user();
        $pin = $request->input('pin');

        if (!$user->hasPrivatePin()) {
            // First time setup - store this PIN as user's PIN
            $user->private_pin = Hash::make($pin);
            $user->save();

            return response()->json([
                'success' => true,
                'is_first_setup' => true,
                'message' => 'PIN created and private events unlocked for 5 minutes!',
            ]);
        }

        if ($user->verifyPrivatePin($pin)) {
            return response()->json([
                'success' => true,
                'message' => 'Private events unlocked for 5 minutes!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect 4-digit PIN. Please try again.',
        ], 422);
    }

    /**
     * Set or update user's 4-digit PIN.
     */
    public function setPin(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'current_pin' => ['nullable', 'string', 'size:4'],
            'pin' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
            'pin_confirmation' => ['required', 'same:pin'],
        ]);

        $user = $request->user();

        // If user already has a PIN, verify current PIN
        if ($user->hasPrivatePin() && !$user->verifyPrivatePin($request->input('current_pin', ''))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current PIN does not match.',
                ], 422);
            }
            return redirect()->back()->withErrors(['current_pin' => 'Current PIN does not match.'], 'updatePin');
        }

        $user->private_pin = Hash::make($request->input('pin'));
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Private event PIN updated successfully.',
            ]);
        }

        return redirect()->back()->with('status', 'pin-updated');
    }

    /**
     * Check if current user has a PIN configured.
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'has_pin' => $user->hasPrivatePin(),
        ]);
    }
}
