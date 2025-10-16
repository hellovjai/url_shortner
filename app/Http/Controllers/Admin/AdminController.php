<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\MailSetting;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required|email',
            'password' => 'required|string|max:255',
        ]);

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password], $request->remember)) {
            return redirect()->route('admin.dashboard')->with('flash_success', 'Welcome Back');
        } else {
            return back()->with('flash_error', 'Invalid Username or Password');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin');
    }

    public function dashboard()
    {
        $hour = now()->format('G');
        $greeting = $hour < 12
            ? 'Good morning'
            : ($hour < 18 ? 'Good afternoon' : 'Good evening');

        $user = Auth::user();
        $role = $user->role;

        $clients = $users = $urls = 0;

        switch ($role) {
            case 'SuperAdmin':
                $clients = Company::count();
                $urls = ShortUrl::count();
                $users = User::count();
                break;

            case 'Admin':
                $clients = 0;
                $users = User::where('company_id', $user->company_id)->count();
                $urls = ShortUrl::where('company_id', $user->company_id)->count();
                break;

            case 'Member':
                $clients = 0;
                $users = 0;
                $urls = ShortUrl::where('company_id', $user->company_id)
                    ->where('user_id', $user->id)
                    ->count();
                break;

            default:
                abort(401);
        }

        return view('admin.dashboard', compact('greeting', 'clients', 'urls', 'users'));
    }

    public function profileSetting()
    {
        $profile = Auth::user();

        return view('admin.auth.profile-setting', compact('profile'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|string|min:5|confirmed',
                'password_confirmation' => 'required|string|min:5',
            ],
            ['password.confirmed' => 'New Password dose not matched']
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully!']);
    }

    public function updateImages(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('admin_asset', 'public');
            $user->profile_image = 'storage/'.$profileImagePath;
        }

        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('admin_asset', 'public');
            $user->cover_image = 'storage/'.$coverImagePath;
        }
        $user->save();

        return response()->json(['message' => 'Images updated successfully!']);
    }

    public function mailSetting()
    {
        $mail = MailSetting::first();

        return view('admin.auth.mail-setting', compact('mail'));
    }

    public function updateMail(Request $request)
    {

        $data = $request->all();
        $contact = MailSetting::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return response()->json([
            'status' => 'success',
            'message' => $contact->wasRecentlyCreated ? 'Smtp created successfully.' : 'Smtp updated successfully.',
        ]);
    }

    // invitation methods

    public function acceptInvitation(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired() || User::where('email', $invitation->email)->exists()) {
            abort(419);
        }

        $user = User::create([
            'name' => $invitation->name,
            'email' => $invitation->email,
            'password' => Hash::make('password'),
            'role' => $invitation->role,
            'company_id' => $invitation->company_id,
        ]);

        $invitation->delete();

        Auth::login($user);

        return redirect()->route('admin.profile.setting')->with('success', 'Please Set your password.');
    }

    // User methods
    public function getUsers()
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $users = User::with('shortUrls')->where('company_id', $user->company_id)->get();

        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('check_box', function ($row) {
                return '<input type="checkbox" class="user-checkbox" value="'.$row->id.'">';
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('role', function ($row) {
                return $row->role;
            })
            ->addColumn('total_generated_urls', function ($row) {
                return $row->shortUrls->count();
            })
            ->addColumn('total_hits', function ($row) {
                return $row->shortUrls->sum('clicks');
            })
            ->addColumn('tools', function ($row) {
                $editBtn = '<button class="btn btn-sm btn-primary edit-user" data-id="'.$row->id.'">Edit</button> ';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete-user" data-id="'.$row->id.'">Delete</button>';

                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['check_box', 'name', 'email', 'role', 'total_generated_urls', 'total_hits', 'tools'])
            ->make(true);
    }
}
