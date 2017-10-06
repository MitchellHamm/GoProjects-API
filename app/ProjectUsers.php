<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUsers extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'project_id',
    'user_id',
    'is_owner',
  ];
}
