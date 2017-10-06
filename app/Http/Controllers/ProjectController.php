<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
  /**
   * Get root url.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    try {
      $this->validate($request, [
        'name' => 'required|max:255',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'create project route!'
    ]);
  }

  public function edit(Request $request)
  {
    try {
      $this->validate($request, [
        'project_id' => 'required',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'edit project route!'
    ]);
  }

  public function delete(Request $request)
  {
    try {
      $this->validate($request, [
        'project_id' => 'required',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'delete project route!'
    ]);
  }

  public function addMember(Request $request)
  {
    try {
      $this->validate($request, [
        'project_id' => 'required',
        'user_id' => 'required',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'add member project route!'
    ]);
  }

  public function requestMembership(Request $request)
  {
    try {
      $this->validate($request, [
        'project_id' => 'required',
      ]);
    } catch (ValidationException $e) {
      return $e->getResponse();
    }

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'request membership project route!'
    ]);
  }
}
