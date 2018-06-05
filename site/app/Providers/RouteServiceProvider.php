<?php

namespace App\Providers;

use Auth;
use App\User;
use App\Project;
use App\Subtask;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::bind('user_id', function($userId, $route) {
            $user = User::with([
                'skills',
                'reviewsFromUsers.project.typeProject',
                'reviewsFromUsers.reviewer'
            ])->find($userId);

            $user->reviewsFromUsers->load('answer');

            return $user;
        });

        Route::bind('my_task', function($taskId, $route) {
            return Subtask::with('taskExecutors')->find($taskId);
        });

        Route::bind('api_my_profile', function($userId, $route) {
            return User::with('skills')->find($userId);
        });

        Route::bind('my_project', function($value, $route) {
            if($route->getActionMethod() != 'edit') {
                return Project::where('slug', $value)->first();
            }
            return $value;
        });

        Route::bind('api_my_project', function($value, $route) {
            return Project::with('subtasks')->where('slug', $value)->first();
        });

        Route::bind('project', function($value) {
            $project = Project::with('customer', 'typeProject', 
                'executors.user', 'executors.subtask.category')
                ->where('slug', $value)->first();

            if($project->status == 'closed') {
                $project->load(['reviews' => function($query) {
                    $query->where('reviewer_id', '=', Auth::user()->id)->first();
                }]);
            }

            return $project;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
