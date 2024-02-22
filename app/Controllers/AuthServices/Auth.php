<?php

namespace App\Controllers\AuthServices;

use App\Models\Auth\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;

class Auth extends ResourceController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        $rules = [
            'email' => [
                'rules' => 'required|valid_email|is_unique[auth_users.email]'
            ],
            'username' => [
                'rules' => 'required|is_unique[auth_users.email]'
            ],
            'password' => [
                'rules' => 'required|min_length[6]'
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]'
            ]
        ];

        $password = $this->request->getPost('password');
        if (is_array($password) || $password === null) {
            $password = (string) $password;
        }

        if ($this->validate($rules)) {

            $userData = [
                'email' => $this->request->getPost('email'),
                'name' => $this->request->getPost('nama'),
                'username' => $this->request->getPost('username'),
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => $this->request->getPost('role')
            ];

            $this->userModel->save($userData);

            return $this->respond(['status' => true, 'message' => 'Register berhasil'], 200);
        } else {
            $response = [
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ];

            return $this->respond($response, 422);
        }
    }

    public function get()
    {
        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('per_page') ?? 10;
        $offset = ($page - 1) * $perPage;
        $segment = $this->request->getGet('page') ?? 1;
        if (is_array($segment) || $segment === null) {
            $segment = (string) $segment;
        }

        $users = $this->userModel->paginate($perPage, '', $offset, $segment);
        $pager = $this->userModel->pager;
        $total = $this->userModel->countAllResults();

        $pagination = [
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'lastPage' => $pager->getPageCount(),
        ];

        $data = [
            'user' => $users,
            'pager' => $pagination
        ];

        return $this->respond(["status" => 200, "data" => $data], 200);
    }

    public function login()
    {
        $email = $this->request->getPost('email') ?? $this->request->getPost('username') ;
        $password = $this->request->getPost('password');
        if (is_array($password) || $password === null) {
            $password = (string) $password;
        }

        $user = $this->userModel->where('username', $email)->orWhere('email', $email)->first();

        if (!$user) {
            return $this->respond(['status' => false, 'message' => 'Username atau Password salah'], 401);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->respond(['status' => false, 'message' => 'Username atau Password salah'], 401);
        }

        if($user['is_active'] == 1){

            $key = getenv("JWT_SECRET");
            $iat = time();
            $exp = $iat + (3600*24);

            $payload = [
                'iss' => 'ci4-jwt',
                'sub' => 'logintoken',
                'iat' => $iat,
                'exp' => $exp,
                'username' => $user['username'],
                'id' => $user['id'],
            ];

            $token = JWT::encode($payload, $key, "HS256");

            $data = [
                "nama" => $user['name'],
                "email" => $user['email'],
                "username" => $user['username'],
                "role" => $user['role'],
                "is_active" => $user['is_active'],
                "access_token" => $token
            ];

            return $this->respond(['status' => true, 'data' => $data], 200);
        }else{
            return $this->respond(['status' => false, 'message' => 'Akun anda tidak aktif'], 401);
        }

    }

    public function recovery(){
        $email = $this->request->getPost('email') ?? $this->request->getPost('username');
        $password = $this->request->getPost('password');
        if (is_array($password) || $password === null) {
            $password = (string) $password;
        }

        $user = $this->userModel->where('username', $email)->orWhere('email', $email)->first();

        if($user){
            if($password){

                $rules = [
                    'password' => [
                        'rules' => 'required|min_length[6]'
                    ]
                ];

                if($this->validate($rules)){
                    $newPasswordHash = password_hash($password, PASSWORD_BCRYPT);
                    $this->userModel->update(['id' => (string)$user['id']], ['password' => $newPasswordHash]);


                    return $this->respond(['status' => true, 'message' => 'Password Berhasil Di Rubah'], 200);
                }else{
                    $response = [
                        'status' => false,
                        'errors' => $this->validator->getErrors(),
                    ];
        
                    return $this->respond($response, 422);
                }

            }else{
                $random = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);

                return $this->respond(["status" => true, "data" => $random], 200);
            }
        }else{
            return $this->respond(["status" => false], 422);
        }
    }
}
