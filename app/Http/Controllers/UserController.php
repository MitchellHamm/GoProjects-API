<?php

namespace App\Http\Controllers;

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

    $user = JWTAuth::parseToken()->authenticate();
    return new JsonResponse([
      'message' => 'search user route!'
    ]);
  }
}
