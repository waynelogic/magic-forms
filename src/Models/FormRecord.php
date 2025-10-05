<?php

namespace Waynelogic\MagicForms\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Waynelogic\FilamentCms\Models\BackendUser;

class FormRecord extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'manager_id',
        'form_type',
        'group',
        'form_data',
        'ip',
        'read_at',
        'country',
        'city',
    ];

    protected $casts = [
        'form_data' => 'array',
        'read_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (FormRecord $formRecord) {
            if (config('magic-forms.use_geocoding')) {
                $formRecord->checkGeo();
            }
        });
    }

    public function manager() : BelongsTo
    {
        return $this->belongsTo(BackendUser::class, 'manager_id');
    }

    public function getFilesUrlAttribute() : array
    {
        return $this->getMedia('files')->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }

    public function markAsRead() : bool
    {
        if (is_null($this->read_at)) {
            return $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
        return false;
    }

    /**
     * Mark the notification as unread.
     *
     * @return bool
     */
    public function markAsUnread() : bool
    {
        if (! is_null($this->read_at)) {
            return $this->forceFill(['read_at' => null])->save();
        }
        return false;
    }

    public function getUnreadAttribute() : bool
    {
        return is_null($this->read_at);
    }

    public function checkGeo() : void
    {
        $token = config('magic-forms.twoiptoken');

        if (!$token) return;
        $url = "https://api.2ip.io/{$this->ip}?token={$token}&lang=ru";

        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data && isset($data['country']) && isset($data['city'])) {
                $this->country = $data['country'];
                $this->city = $data['city'];
                $this->save();
            }
        } catch (Exception $e) {
            Log::error('Failed to get geo data for IP: ' . $this->ip, ['error' => $e->getMessage()]);
        }
    }
}
