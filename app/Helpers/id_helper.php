<?php
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;

  function getid($header){

    $key = getenv("JWT_SECRET");

    $token = explode(' ', $header)[1];
    $decoded = JWT::decode($token, new Key($key, "HS256"));

    return $decoded->id;
  }