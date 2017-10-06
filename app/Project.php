<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'projects';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'status',
    'description',
    'client',
    'training_date',
    'live_date',
    'primary_image',
    'project_color',
    'project_color',
  ];
}
