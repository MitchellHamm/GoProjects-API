<?php
namespace App\Http\Services;

use App\Project;
use App\ProjectUsers;
use Laravel\Lumen\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class ProjectService {
  public static function createProject($request) {
    $result = [
      'status' => 0,
      'data' => [],
    ];
    $user = JWTAuth::parseToken()->authenticate();
    $projectData = [];

    $projectData['name'] = $request->input('name');
    $projectData['status'] = $request->input('status', 0);
    $projectData['description'] = $request->input('description', 'No Description');
    $projectData['client'] = $request->input('client', 'No Client');
    $projectData['training_date'] = $request->input('training_date', date("Y-m-d H:i:s"));
    $projectData['live_date'] = $request->input('live_date', date("Y-m-d H:i:s"));
    $projectData['project_color'] = $request->input('project_color', 'FFFFFF');
    $projectData['primary_image'] = '';

    //Handle uploaded image
    if($request->hasFile('project_image') && $request->file('project_image')->isValid()) {
      $projectData['primary_image'] = $projectData['name'] . '-' . date("Y-m-d H:i:s");
      $request->file('project_image')->move('/img', $projectData['primary_image']);
    }

    $project = Project::create($projectData);

    if(!is_null($project)) {
      $projectUserData = [
        'project_id' => $project->id,
        'user_id' => $user['id'],
        'is_owner' => '1',
      ];

      $projectUser = ProjectUsers::create($projectUserData);

      if(!is_null($projectUser)) {
        $result['status'] = 1;
        $result['data'] = $projectData;
      } else {
        //Remove the project and return an error
        $project->delete();
        $result['data'] = [
          'error' => 'Could not link user to the project',
        ];
      }
    }

    return $result;
  }
}