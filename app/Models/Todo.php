<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'is_completed',
        'category'
    ];

    // カテゴリーID定数 
    const CATEGORY_ID_WORK       = 1;
    const CATEGORY_ID_INDIVIDUAL = 2;
    const CATEGORY_ID_FAMILY     = 3; 

    // カテゴリー名称定数
    const CATEGORY_NAME_WORK       = '仕事';
    const CATEGORY_NAME_INDIVIDUAL = '個人';
    const CATEGORY_NAME_FAMILY     = '家族';

    const CATEGORY_LIST = [
        self::CATEGORY_ID_WORK => self::CATEGORY_NAME_WORK,
        self::CATEGORY_ID_INDIVIDUAL => self::CATEGORY_NAME_INDIVIDUAL,
        self::CATEGORY_ID_FAMILY => self::CATEGORY_NAME_FAMILY,
    ];

}
