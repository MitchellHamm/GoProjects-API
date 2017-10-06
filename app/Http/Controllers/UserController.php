<?php

namespace App\Http\Controllers;

use App\User;
use App\ProjectUsers;
use App\Project;
use Laravel\Lumen\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
   * Get root url.
   *
   * @return \Illuminate\Http\Response
   */
  public function search(Request $request)
  {
    try {
      $this->validate($request, [
        'query_string' => 'required|min:3',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $queryString = $request->input('query_string');
    $users = User::where('users.email', 'LIKE', "%$queryString%")
      ->orWhere('users.first_name', 'LIKE', "%$queryString%")
      ->orWhere('users.last_name', 'LIKE', "%$queryString%")
      ->get();

    return new JsonResponse($users);
  }

  public function getProjects()
  {

    $user = JWTAuth::parseToken()->authenticate();

    $projects = ProjectUsers::where('user_id', '=', "$user->id")
      ->join('projects', 'projects.id', '=', 'project_users.project_id')
      ->get();

    return new JsonResponse($projects);
  }
}
