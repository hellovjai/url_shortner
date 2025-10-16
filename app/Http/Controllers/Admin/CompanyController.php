<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function getCompanies()
    {
        if (Auth::user()->role !== 'SuperAdmin') {
            abort(403);
        }

        $companies = Company::with(['users', 'shortUrls'])->select(['id', 'name', 'email']);

        return DataTables::of($companies)
            ->addIndexColumn()
            ->editColumn('name', function ($company) {
                return e($company->name).'<br><small class="text-muted">'.e($company->email).'</small>';
            })
            ->addColumn('users_count', function ($company) {
                return $company->users()->count();
            })
            ->addColumn('total_generated_urls', function ($company) {
                return $company->shortUrls()->count();
            })
            ->addColumn('total_url_hits', function ($company) {
                return $company->shortUrls()->sum('clicks');
            })
            ->rawColumns(['name', 'users_count', 'total_generated_urls', 'total_url_hits'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'SuperAdmin') {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:invitations,email|unique:companies,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $company = Company::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'email' => $request->email]);

        $token = Str::random(32);
        $expiresAt = now()->addDays(7);

        $invite = Invitation::create([
            'name' => $request->name,
            'email' => $request->email,
            'token' => $token,
            'role' => 'Admin',
            'company_id' => $company->id,
            'expires_at' => $expiresAt,
        ]);

        $invitationUrl = route('invitations.accept', ['token' => $invite->token]);

        try {
            Mail::raw("Click the link to join as Admin for {$company->name}: {$invitationUrl} (Valid until ".$expiresAt->toDateTimeString().')', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Invitation to Join as Admin');
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Client saved, but email failed to send: '.$e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Client saved and invitation sent to '.$request->email]);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'SuperAdmin') {
            abort(403);
        }
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['status' => 'success', 'message' => 'Company deleted successfully']);
    }
}
