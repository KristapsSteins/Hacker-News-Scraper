<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'link',
        'points',
        'date_created',
    ];

    public function scopeFiltered(Builder $builder)
    {
        $articles = $builder->select(
            'articles.id AS id',
            'articles.title AS title', 
            'articles.link AS link', 
            'articles.points AS points', 
            'articles.date_created AS date_created'
        );

        return $articles;
    }
}
