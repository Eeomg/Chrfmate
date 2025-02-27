<?php

namespace App\Modules\Categories;

use App\Models\Trait\BelongsToWorkspaceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,BelongsToWorkspaceTrait;


    protected $fillable = ['title', 'workspace_id'];

    protected $hidden = ['workspace_id','created_at','updated_at'];


}
