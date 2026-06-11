<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'location',
        'phone',
        'whatsapp',
        'category_id',
        'user_id',
        'is_active',
        'status',
        'views',
        'is_pinned',
        'pinned_at',
        'featured_position',
        'featured_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
        'price' => 'decimal:2',
        'views' => 'integer',
        'pinned_at' => 'datetime',
        'featured_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate slug and set default status
    protected static function booted()
    {
        static::creating(function ($ad) {
            if (empty($ad->slug)) {
                $ad->slug = Str::slug($ad->title) . '-' . uniqid();
            }
            if (empty($ad->status)) {
                $ad->status = 'approved';
            }
        });
    }

    // ========== RELATIONSHIPS ==========
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(AdImage::class);
    }

    // ========== SCOPES ==========
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'approved');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true)->where('is_active', true);
    }

    public function scopePinnedByAdmin($query)
    {
        return $query->where('is_pinned', true)
                     ->where('is_active', true)
                     ->orderBy('pinned_at', 'desc');
    }

    public function scopeTrending($query, $limit = 8)
    {
        return $query->active()->orderBy('views', 'desc')->limit($limit);
    }

    public function scopeLatest($query, $limit = 12)
    {
        return $query->active()->orderBy('created_at', 'desc')->limit($limit);
    }

    // ========== HELPER METHODS ==========
    public function isPinnedActive()
    {
        return $this->is_pinned && $this->is_active;
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return 'قابل للتفاوض';
        }
        return number_format($this->price) . ' ر.س';
    }

    // ========== STATIC METHODS ==========
    public static function saudiCities()
    {
        return [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام',
            'الخبر', 'الظهران', 'بريدة', 'تبوك', 'أبها', 'الطائف', 'حائل',
            'نجران', 'جازان', 'الخرج', 'المجمعة', 'حفر الباطن', 'القطيف'
        ];
    }
}