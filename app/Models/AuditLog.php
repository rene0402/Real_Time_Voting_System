<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type',
        'category',
        'description',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
        'target_type',
        'target_id',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    // Action types constants
    const TYPE_ADMIN_ACTION = 'admin_action';
    const TYPE_VOTER_ACTIVITY = 'voter_activity';
    const TYPE_VOTE_SUBMISSION = 'vote_submission';
    const TYPE_SYSTEM_CHANGE = 'system_change';

    // Admin action categories
    const CATEGORY_USER_CREATED = 'user_created';
    const CATEGORY_USER_UPDATED = 'user_updated';
    const CATEGORY_USER_DELETED = 'user_deleted';
    const CATEGORY_USER_BLOCKED = 'user_blocked';
    const CATEGORY_USER_UNBLOCKED = 'user_unblocked';
    const CATEGORY_ELECTION_CREATED = 'election_created';
    const CATEGORY_ELECTION_UPDATED = 'election_updated';
    const CATEGORY_ELECTION_DELETED = 'election_deleted';
    const CATEGORY_ELECTION_STARTED = 'election_started';
    const CATEGORY_ELECTION_ENDED = 'election_ended';
    const CATEGORY_ELECTION_PAUSED = 'election_paused';
    const CATEGORY_ELECTION_RESUMED = 'election_resumed';
    const CATEGORY_CANDIDATE_CREATED = 'candidate_created';
    const CATEGORY_CANDIDATE_UPDATED = 'candidate_updated';
    const CATEGORY_CANDIDATE_DELETED = 'candidate_deleted';
    const CATEGORY_SETTINGS_CHANGED = 'settings_changed';

    // Voter activity categories
    const CATEGORY_LOGIN = 'login';
    const CATEGORY_LOGOUT = 'logout';
    const CATEGORY_LOGIN_FAILED = 'login_failed';
    const CATEGORY_PROFILE_UPDATED = 'profile_updated';
    const CATEGORY_PASSWORD_CHANGED = 'password_changed';

    // Vote submission categories
    const CATEGORY_VOTE_CAST = 'vote_cast';
    const CATEGORY_VOTE_CHANGED = 'vote_changed';
    const CATEGORY_VOTE_REMOVED = 'vote_removed';

    // System change categories
    const CATEGORY_SYSTEM_STARTUP = 'system_startup';
    const CATEGORY_SYSTEM_SHUTDOWN = 'system_shutdown';
    const CATEGORY_BACKUP_CREATED = 'backup_created';
    const CATEGORY_SECURITY_ALERT = 'security_alert';

    /**
     * Get the user that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by action type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('action_type', $type);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by target.
     */
    public function scopeForTarget($query, $type, $id)
    {
        return $query->where('target_type', $type)->where('target_id', $id);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the action type label.
     */
    public function getActionTypeLabelAttribute()
    {
        return match($this->action_type) {
            self::TYPE_ADMIN_ACTION => 'Admin Action',
            self::TYPE_VOTER_ACTIVITY => 'Voter Activity',
            self::TYPE_VOTE_SUBMISSION => 'Vote Submission',
            self::TYPE_SYSTEM_CHANGE => 'System Change',
            default => ucfirst(str_replace('_', ' ', $this->action_type)),
        };
    }

    /**
     * Get the category label.
     */
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            self::CATEGORY_USER_CREATED => 'User Created',
            self::CATEGORY_USER_UPDATED => 'User Updated',
            self::CATEGORY_USER_DELETED => 'User Deleted',
            self::CATEGORY_USER_BLOCKED => 'User Blocked',
            self::CATEGORY_USER_UNBLOCKED => 'User Unblocked',
            self::CATEGORY_ELECTION_CREATED => 'Election Created',
            self::CATEGORY_ELECTION_UPDATED => 'Election Updated',
            self::CATEGORY_ELECTION_DELETED => 'Election Deleted',
            self::CATEGORY_ELECTION_STARTED => 'Election Started',
            self::CATEGORY_ELECTION_ENDED => 'Election Ended',
            self::CATEGORY_ELECTION_PAUSED => 'Election Paused',
            self::CATEGORY_ELECTION_RESUMED => 'Election Resumed',
            self::CATEGORY_CANDIDATE_CREATED => 'Candidate Created',
            self::CATEGORY_CANDIDATE_UPDATED => 'Candidate Updated',
            self::CATEGORY_CANDIDATE_DELETED => 'Candidate Deleted',
            self::CATEGORY_SETTINGS_CHANGED => 'Settings Changed',
            self::CATEGORY_LOGIN => 'Login',
            self::CATEGORY_LOGOUT => 'Logout',
            self::CATEGORY_LOGIN_FAILED => 'Login Failed',
            self::CATEGORY_PROFILE_UPDATED => 'Profile Updated',
            self::CATEGORY_PASSWORD_CHANGED => 'Password Changed',
            self::CATEGORY_VOTE_CAST => 'Vote Cast',
            self::CATEGORY_VOTE_CHANGED => 'Vote Changed',
            self::CATEGORY_VOTE_REMOVED => 'Vote Removed',
            self::CATEGORY_SYSTEM_STARTUP => 'System Startup',
            self::CATEGORY_SYSTEM_SHUTDOWN => 'System Shutdown',
            self::CATEGORY_BACKUP_CREATED => 'Backup Created',
            self::CATEGORY_SECURITY_ALERT => 'Security Alert',
            default => ucfirst(str_replace('_', ' ', $this->category ?? '')),
        };
    }

    /**
     * Static method to log an action.
     */
    public static function log($userId, $actionType, $category, $description, $oldValue = null, $newValue = null, $targetType = null, $targetId = null)
    {
        return static::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'category' => $category,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'target_type' => $targetType,
            'target_id' => $targetId,
        ]);
    }

    /**
     * Static method to log admin action.
     */
    public static function logAdminAction($userId, $category, $description, $oldValue = null, $newValue = null, $targetType = null, $targetId = null)
    {
        return static::log($userId, self::TYPE_ADMIN_ACTION, $category, $description, $oldValue, $newValue, $targetType, $targetId);
    }

    /**
     * Static method to log voter activity.
     */
    public static function logVoterActivity($userId, $category, $description, $targetType = null, $targetId = null)
    {
        return static::log($userId, self::TYPE_VOTER_ACTIVITY, $category, $description, null, null, $targetType, $targetId);
    }

    /**
     * Static method to log vote submission.
     */
    public static function logVoteSubmission($userId, $category, $description, $oldValue = null, $newValue = null)
    {
        return static::log($userId, self::TYPE_VOTE_SUBMISSION, $category, $description, $oldValue, $newValue, 'Vote', $targetId ?? null);
    }

    /**
     * Static method to log system change.
     */
    public static function logSystemChange($category, $description, $oldValue = null, $newValue = null)
    {
        return static::log(null, self::TYPE_SYSTEM_CHANGE, $category, $description, $oldValue, $newValue);
    }
}
