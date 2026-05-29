<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $reviewer_id
 * @property string $review_period
 * @property int $quality_of_work
 * @property int $productivity
 * @property int $communication
 * @property int $teamwork
 * @property int $leadership
 * @property float $overall_rating
 * @property string|null $strengths
 * @property string|null $areas_for_improvement
 * @property string|null $goals
 * @property string|null $comments
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class PerformanceReview extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_period',
        'quality_of_work',
        'productivity',
        'communication',
        'teamwork',
        'leadership',
        'overall_rating',
        'strengths',
        'areas_for_improvement',
        'goals',
        'comments',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'overall_rating' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the employee being reviewed.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the reviewer (employee who conducted the review).
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }

    /**
     * Calculate overall rating from all rating fields.
     */
    public function calculateOverallRating(): float
    {
        $ratings = [
            $this->quality_of_work,
            $this->productivity,
            $this->communication,
            $this->teamwork,
            $this->leadership,
        ];

        $average = array_sum($ratings) / count($ratings);

        return round($average, 2);
    }

    /**
     * Boot method to auto-calculate overall rating.
     */
    protected static function booted(): void
    {
        static::saving(function (self $review) {
            $review->overall_rating = $review->calculateOverallRating();
        });
    }

    /**
     * Scope to filter by employee.
     */
    public function scopeByEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to filter by reviewer.
     */
    public function scopeByReviewer($query, int $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    /**
     * Scope to filter by review period.
     */
    public function scopeByPeriod($query, string $period)
    {
        return $query->where('review_period', $period);
    }

    /**
     * Scope to order by overall rating.
     */
    public function scopeOrderByRating($query, string $direction = 'desc')
    {
        return $query->orderBy('overall_rating', $direction);
    }
}
