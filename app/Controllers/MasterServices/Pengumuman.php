<?php

namespace App\Controllers\MasterServices;

use App\Models\Master\PengumumanModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Pengumuman extends ResourceController
{
    use ResponseTrait;
    protected $pengumumanModel;

    public function __construct()
    {
        $this->pengumumanModel = new PengumumanModel();
        helper(['id_helper']);
    }

    private function userId(){
        
        $header = $this->request->getServer("HTTP_AUTHORIZATION");
        $userId = getid($header);

        return $userId;
    }

    public function post()
    {
        $rules = [
            'judul_pengumuman' => [
                'rules' => 'required'
            ],
            'isi_pengumuman' => [
                'rules' => 'required'
            ],
            'to_pengumuman' => [
                'rules' => 'required'
            ],
        ];

        if ($this->validate($rules)) {

            $data = [
                'judul_pengumuman' => $this->request->getPost('judul'),
                'isi_pengumuman' => $this->request->getPost('isi'),
                'to_pengumuman' => $this->request->getPost('to'),
                'created_by' => userId(),
            ];

            $this->pengumumanModel->save($data);

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

            $data = $this->pengumumanModel->findAll();

            return $this->respond(['status' => true, 'data' => $data]);

        }else{
            $perPage = $this->request->getVar('per_page') ?? 10;
    
            $data = $this->pengumumanModel->paginate($perPage);
            $pager = $this->pengumumanModel->pager;
            $total = $this->pengumumanModel->countAllResults();
    
            $pagination = [
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => $pager->getPageCount(),
                'links' => $pager
            ];
    
            $data = [
                $data,
                'pager' => $pagination
            ];
    
            return $this->respond(["status" => 200, "data" => $data], 200);
        }
    }
}
