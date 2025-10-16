<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShortUrlController extends Controller
{
    public function getShortUrls(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $query = ShortUrl::select(['id', 'short_code', 'original_url', 'clicks', 'company_id', 'user_id', 'created_at'])
            ->with('user:id,name', 'company:id,name');

        switch ($role) {
            case 'SuperAdmin':

                break;

            case 'Admin':
                $query->where('company_id', $user->company_id);
                break;

            case 'Member':
                $query->where('company_id', $user->company_id)
                    ->where('user_id', $user->id);
                break;

            default:
                abort(401, 'Unauthorized action.');
        }

        if ($request->has('date_filter')) {
            $dateFilter = $request->input('date_filter');
            $startDate = Carbon::now()->startOfDay();
            switch ($dateFilter) {
                case 'last_week':
                    $startDate = $startDate->subWeek();
                    break;
                case 'last_month':
                    $startDate = $startDate->subMonth();
                    break;
                case 'today':
                    $startDate = $startDate->subDay();
                    break;
                default:
                    $startDate = null;
            }
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
        }

        $shortUrls = $query->get();

        return DataTables::of($shortUrls)
            ->addIndexColumn()
            ->editColumn('short_code', function ($shortUrl) {
                $url = url('re/'.$shortUrl->short_code);

                return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
            })
            ->editColumn('original_url', function ($shortUrl) {
                return '<a href="'.$shortUrl->original_url.'" target="_blank">'.substr($shortUrl->original_url, 0, 30).'...</a>';
            })
            ->editColumn('company_id', function ($shortUrl) {
                return auth()->user()->role === 'SuperAdmin' ? ($shortUrl->company->name) : (auth()->user()->role == 'Admin' ? $shortUrl->user->name : '');
            })
            ->editColumn('created_at', function ($shortUrl) {
                return $shortUrl->created_at->format('d M, y');
            })
            ->rawColumns(['short_code', 'original_url', 'company_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['Admin', 'Member'])) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['original_url']) + [
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ];

        $shortUrl = ShortUrl::updateOrCreate(['id' => $request->id], $data);

        return response()->json([
            'status' => 'success',
            'message' => $shortUrl->wasRecentlyCreated ? 'Short Url Created' : 'Short Url Updated',
            'short_code' => $shortUrl->short_code,
        ]);
    }

    // download URLs based on date filter
    public function downloadUrls(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $query = ShortUrl::select(['id', 'short_code', 'original_url', 'clicks', 'company_id', 'user_id', 'created_at'])
            ->with(['user:id,name', 'company:id,name']);

        switch ($role) {
            case 'SuperAdmin':

                break;

            case 'Admin':
                $query->where('company_id', $user->company_id);
                break;

            case 'Member':
                $query->where('company_id', $user->company_id)
                    ->where('user_id', $user->id);
                break;

            default:
                abort(401, 'Unauthorized action.');
        }
        if ($request->filled('date_filter')) {
            $startDate = match ($request->input('date_filter')) {
                'today' => Carbon::now()->startOfDay(),
                'last_week' => Carbon::now()->subWeek()->startOfDay(),
                'last_month' => Carbon::now()->subMonth()->startOfDay(),
                default => null,
            };
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
        }

        $shortUrls = $query->get();

        $csvData = [['Short Code', 'Original URL', 'Clicks', 'Client', 'Created At']];
        foreach ($shortUrls as $shortUrl) {
            $clientName = match ($role) {
                'SuperAdmin', 'Admin' => optional($shortUrl->company)->name ?? 'N/A',
                'Member' => optional($shortUrl->user)->name ?? 'N/A',
                default => optional($shortUrl->company)->name ?? 'N/A',
            };

            $csvData[] = [
                url('/s/'.$shortUrl->short_code),
                $shortUrl->original_url,
                $shortUrl->clicks,
                $clientName,
                $shortUrl->created_at->format('d M, Y'),
            ];
        }
        $csvContent = '';
        foreach ($csvData as $row) {
            $escapedRow = array_map(function ($value) {
                $value = str_replace('"', '""', $value);

                return '"'.$value.'"';
            }, $row);
            $csvContent .= implode(',', $escapedRow)."\n";
        }

        $filename = 'short_urls_'.now('Asia/Kolkata')->format('Ymd_His').'.csv';
        $filePath = storage_path('app/temp/'.$filename);

        if (! is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0775, true);
        }

        file_put_contents($filePath, $csvContent);

        Log::info('CSV generated: '.$filePath);

        if (! file_exists($filePath)) {
            Log::error('CSV file creation failed: '.$filePath);
            abort(500, 'Failed to generate CSV file.');
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    // url redirection
    public function redirectToLongUrl($short_code = null)
    {
        if (! $short_code) {
            abort(404);
        }
        $shortUrl = ShortUrl::where('short_code', $short_code)->first();
        if (! $shortUrl) {
            abort(404);
        }
        $shortUrl->increment('clicks');

        return redirect($shortUrl->original_url);
    }
}
