<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Activity;
use App\Helpers\RequestType;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as ModelsRequest;

class Dashboard extends Controller
{
  public function index()
  {
    $authUser = Auth::user();
    $requestTypes = RequestType::get();

    $issues = ModelsRequest::where('status', 'Issue');
    $documents = ModelsRequest::orderBy('id', 'desc');
    $newRequests = ModelsRequest::where('status', 'Sent');
    $completedRequests = ModelsRequest::where('status', 'Complete');
    $activities = Activity::orderBy('id', 'desc');

    if (!$authUser->isAdmin()) {
      $issues->where('user_id', $authUser->id);
      $documents->where('user_id', $authUser->id);
      $newRequests->where('user_id', $authUser->id);
      $completedRequests->where('user_id', $authUser->id);
      $activities->where('user_id', $authUser->id);
    }

    $issues = $issues->count();
    $documents = $documents->limit(10)->get();
    $newRequests = $newRequests->count();
    $completedRequests = $completedRequests->count();
    $activities = $activities->get();

    $last12Months = collect(range(0, 11))->map(function ($i) {
      return Carbon::now()->subMonths($i)->format('Y-m');
    })->reverse()->values();
    $monthlyCounts = ModelsRequest::select(
      DB::raw('DATE_FORMAT(`created_at`, "%Y-%m") as `year_month`'),
      DB::raw('DATE_FORMAT(`created_at`, "%M %Y") as `month_name`'),
      DB::raw('COUNT(*) as `count`')
    );
    if (!$authUser->isAdmin()) {
      $monthlyCounts->where('user_id', $authUser->id);
    }
    $monthlyCounts = $monthlyCounts->where('created_at', '>=', Carbon::now()->subYear()->startOfMonth())
      ->groupBy('year_month', 'month_name')
      ->orderBy('year_month')
      ->get()
      ->keyBy('year_month');
    $graphDatasets = $last12Months->map(function ($month) use ($monthlyCounts) {
      return [
        'month' => Carbon::parse($month . '-01')->format('M Y'),
        'count' => $monthlyCounts->get($month)->count ?? 0
      ];
    });

    $expenseData = [];
    $invoices = Invoice::selectRaw('user_id, SUM(amount) as total_amount')
      ->groupBy('user_id')
      ->get();
    foreach ($invoices as $invoice) {
      $expenseData[] = [
        'name' => $invoice->user->name,
        'value' => $invoice->total_amount,
      ];
    }

    return view('dashboard', [
      'issues' => $issues,
      'documents' => $documents,
      'newRequests' => $newRequests,
      'requestTypes' => $requestTypes,
      'graphDatasets' => $graphDatasets,
      'completedRequests' => $completedRequests,
      'activities' => $activities,
      'expenseData' => json_encode($expenseData),
    ]);
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
  }
}
