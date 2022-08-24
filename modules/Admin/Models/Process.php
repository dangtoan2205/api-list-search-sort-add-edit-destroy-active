<?php

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'title_vi', 'image',
        'title_en', 'title_ja'
    ];
}
