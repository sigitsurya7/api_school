<?php

namespace App\Controllers\AuthServices;

use App\Models\Auth\RoleModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Role extends ResourceController
{
    use ResponseTrait;

    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function post()
    {
        $rules = [
            'role_name' => [
                'rules' => 'required'
            ]
        ];

        if ($this->validate($rules)) {

            $data = [
                'role_name' => $this->request->getPost('role_name')
            ];

            $this->roleModel->save($data);

            return $this->respond(['status' => true, 'message' => 'Menambahkan data berhasil'], 200);
        } else {
            $response = [
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ];

            return $this->respond($response, 422);
        }
    }

    public function get($page = null)
    {
        if($page == 'option'){

            $data = $this->roleModel->findAll();

            return $this->respond(['status' => true, 'data' => $data]);

        }else{
            $perPage = $this->request->getVar('per_page') ?? 10;
    
            $users = $this->roleModel->paginate($perPage);
            $pager = $this->roleModel->pager;
            $total = $this->roleModel->countAllResults();
    
            $pagination = [
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => $pager->getPageCount(),
                'links' => $pager
            ];
    
            $data = [
                'user' => $users,
                'pager' => $pagination
            ];
    
            return $this->respond(["status" => 200, "data" => $data], 200);
        }
    }

    public function delete($id = null){

        $response = $this->roleModel->where('id', $id)->first();
        
        if($response){
            $this->roleModel->delete(['id' => $id]);
            return $this->respond(['status' => true, 'message' => 'Data berhasil di hapus'], 200);
        }else{
            return $this->respond(['status' => false, 'message' => 'Data gagal di hapus'], 500);
        };
    }

    public function edit($id = null){
        $fieldsToUpdate = ['is_active', 'role_name'];
        $data = [];

        foreach ($fieldsToUpdate as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $data[$field] = $value;
            }
        }

        $this->roleModel->update(['id' => $id], $data);

        return $this->respond(['data' => $data], 200);

    }
}
