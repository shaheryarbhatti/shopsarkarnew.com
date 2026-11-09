<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'is_published', 'show_in_footer',
        'sort_order', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_footer' => 'boolean',
    ];

    public function scopeFooter($q)
    {
        return $q->where('is_published', true)->where('show_in_footer', true)->orderBy('sort_order');
    }
}
