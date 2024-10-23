<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Request;
use App\Models\RequestNote;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    if (App::environment('production')) {
      URL::forceScheme('https');
    }

    View::composer('*', function ($view) {
      $notes = [];
      $invoices = [];
      $completedRequests = [];
      $authUser = Auth::user();
      if ($authUser) {
        $notes = RequestNote::where('status', 'unread')
          ->where('receiver_id', $authUser->id)
          ->orderBy('created_at', 'desc')
          ->get();

        if (!$authUser->isAdmin()) {
          $invoices = Invoice::where('user_id', $authUser->id)
            ->where('status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();
          $completedRequests = Request::where('user_id', $authUser->id)
            ->where('status', 'Complete')
            ->where('read', false)
            ->orderBy('updated_at', 'desc')
            ->get();
        }
      }
      $view->with('user', $authUser)
        ->with('notice_notes', $notes)
        ->with('notice_invoices', $invoices)
        ->with('notice_completed_requests', $completedRequests);
    });
  }
}
