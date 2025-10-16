<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            abort(401, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role' => 'required|in:Member,Admin',
            'client_id' => 'required|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $token = Str::random(32);
        $expiresAt = now()->addDays(1);

        $invitation = Invitation::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => $request->client_id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $invitationUrl = route('invitations.accept', ['token' => $invitation->token]);

        try {
            Mail::raw("Click the link to join as {$request->role} for your company: {$invitationUrl} (Valid until " . $expiresAt->toDateTimeString() . ")", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Invitation to Join Your Company');
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Invitation created, but email failed to send: ' . $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Invitation sent to ' . $request->email]);
    }

}
