<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Event\EventRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Attendance\AttendanceRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
    }
}
