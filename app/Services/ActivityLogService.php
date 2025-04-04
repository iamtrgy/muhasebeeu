<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;

class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $action The action being performed
     * @param string $description Description of the activity
     * @param mixed $model The model being acted upon (optional)
     * @param array $properties Additional properties to store (optional)
     * @return ActivityLog|null
     */
    public static function log(string $action, string $description, $model = null, array $properties = [])
    {
        try {
            // Check if the activity_logs table exists
            if (!Schema::hasTable('activity_logs')) {
                // Log a warning instead of failing
                Log::warning('Activity logging attempted but activity_logs table does not exist. Run migrations to create it.');
                return null;
            }
            
            $user = Auth::user();
            
            $log = new ActivityLog();
            $log->user_id = $user ? $user->id : null;
            $log->action = $action;
            $log->description = $description;
            
            if ($model) {
                $log->model_type = get_class($model);
                $log->model_id = $model->id;
            }
            
            $log->properties = !empty($properties) ? json_encode($properties) : null;
            $log->ip_address = Request::ip();
            $log->user_agent = Request::userAgent();
            
            $log->save();
            
            return $log;
        } catch (QueryException $e) {
            // Handle database-related exceptions silently
            Log::warning('Failed to log activity: ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            // Handle other exceptions silently
            Log::warning('Error in activity logging: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get recent activities for a user
     *
     * @param int $userId User ID
     * @param int $limit Maximum number of activities to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentActivities(int $userId, int $limit = 10)
    {
        if (!Schema::hasTable('activity_logs')) {
            return collect([]);
        }
        
        try {
            return ActivityLog::where('user_id', $userId)
                ->latest()
                ->limit($limit)
                ->get();
        } catch (Exception $e) {
            Log::warning('Failed to retrieve activity logs: ' . $e->getMessage());
            return collect([]);
        }
    }
} 