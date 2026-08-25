<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'name', 'type', 'html_content', 'background_image',
        'border_style', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    /**
     * Render template by replacing placeholders with actual data
     */
    public function render(array $data): string
    {
        $html = $this->html_content;
        foreach ($data as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value ?? '', $html);
        }
        return $html;
    }
}
