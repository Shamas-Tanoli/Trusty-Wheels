<?php

namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    View::composer('*', function ($view) {
      $user = Auth::user();

      $horizontalMenuJson = file_get_contents(base_path('resources/menu/admin/horizontalMenu.json'));
      $horizontalMenuData = json_decode($horizontalMenuJson);

      $verticalMenuJson = file_get_contents(base_path('resources/menu/admin/verticalMenu.json'));
      $verticalMenuData = json_decode($verticalMenuJson);
  
  
      $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
    });
  }

  private function filterMenu(array $menu, string $userRole): array
  {
    return array_values(array_filter($menu, function ($item) use ($userRole) {
      return isset($item->allowed_roles) && in_array($userRole, $item->allowed_roles);
    }));
  }
}
