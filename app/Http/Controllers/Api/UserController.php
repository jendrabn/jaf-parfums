<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  public function getUser(): JsonResponse
  {
    return (new UserResource(auth()->user()))
      ->response()
      ->setStatusCode(Response::HTTP_OK);
  }
}