<?php
namespace App\Http\Services;

use App\Project;
use App\ProjectUsers;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

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

  public static function editProject($request) {
    $result = [
      'status' => 0,
      'data' => [],
    ];
    $user = JWTAuth::parseToken()->authenticate();
    $projectData = [];
    $projectData['id'] = $request->input('project_id');
    $project = Project::find($projectData['id']);

    if($request->has('name')) {
      $projectData['name'] = $request->input('name');
    }

    if($request->has('status')) {
      $projectData['status'] = $request->input('status');
    }

    if($request->has('description')) {
      $projectData['description'] = $request->input('description');
    }

    if($request->has('client')) {
      $projectData['client'] = $request->input('client');
    }

    if($request->has('training_date')) {
      $projectData['training_date'] = $request->input('training_date');
    }

    if($request->has('live_date')) {
      $projectData['live_date'] = $request->input('live_date');
    }

    if($request->has('project_color')) {
      $projectData['project_color'] = $request->input('project_color');
    }

    //Handle uploaded image
    if($request->hasFile('project_image') && $request->file('project_image')->isValid()) {
      if(isset($projectData['name'])) {
        $projectData['primary_image'] = $projectData['name'] . '-' . date("Y-m-d H:i:s");
      } else {
        $projectData['primary_image'] = $project->name . '-' . date("Y-m-d H:i:s");
      }
      $request->file('project_image')->move('/img', $projectData['primary_image']);
    }

    $projectUser = ProjectUsers::where([
      ['project_id', '=', $projectData['id']],
      ['user_id', '=', $user->id],
    ])->get();

    if(!is_null($project) && !$projectUser->isEmpty()) {
      $project->fill($projectData);
      $project->save();

      $result['status'] = 1;
      $result['data'] = $projectData;
    } else {
      $result['data'] = [
        'error' => 'You do not have permission to edit this project. Please request membership first',
      ];
    }

    return $result;
  }

  public static function deleteProject($request) {
    $result = [
      'status' => 0,
      'data' => [],
    ];
    $user = JWTAuth::parseToken()->authenticate();
    $projectId = $request->input('project_id');
    $project = Project::where('id', '=', $projectId)->get();

    if(!$project->isEmpty()) {
      $projectUser = ProjectUsers::where([
        ['project_id', '=', $projectId],
        ['user_id', '=', $user->id],
      ])->get();

      if(!$projectUser->isEmpty() && $projectUser->first()->is_owner === 1) {
        $projectUserDelete = ProjectUsers::where([
          ['project_id', '=', $projectId],
        ])->delete();

        if($projectUserDelete) {
          Project::where('id', '=', $projectId)->delete();
          $result['status'] = 1;
        }
      } else {
        $result['data'] = [
          'error' => 'You are not the creator of this project. Only a project creator can delete a project',
        ];
      }
    } else {
      $result['data'] = [
        'error' => 'The project does not exist',
      ];
    }

    return $result;
  }

  public static function addMember($request) {
    $result = [
      'status' => 0,
      'data' => [],
    ];
    $user = JWTAuth::parseToken()->authenticate();
    $projectId = $request->input('project_id');
    $memberId = $request->input('user_id');
    $project = Project::where('id', '=', $projectId)->get();
    $member = User::where('id', '=', $memberId)->get();

    if(!$project->isEmpty() && !$member->isEmpty()) {
      $projectUser = ProjectUsers::where([
        ['project_id', '=', $projectId],
        ['user_id', '=', $user->id],
      ])->get();

      if(!$projectUser->isEmpty() && $projectUser->first()->is_owner === 1) {
        $projectUserData = [
          'project_id' => $projectId,
          'user_id' => $memberId,
          'is_owner' => '0',
        ];

        $projectUser = ProjectUsers::create($projectUserData);

        if(!is_null($projectUser)) {
          $result['status'] = 1;
        } else {
          //Remove the project and return an error
          $result['data'] = [
            'error' => 'Could not link user to the project',
          ];
        }
      } else {
        $result['data'] = [
          'error' => 'You are not the creator of this project. Only a project creator can add a member',
        ];
      }
    } else {
      $result['data'] = [
        'error' => 'The project or user does not exist',
      ];
    }

    return $result;
  }
}