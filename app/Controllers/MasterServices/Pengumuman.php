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
            $id = $this->userId();

            $data = [
                'judul_pengumuman' => $this->request->getPost('judul_pengumuman'),
                'isi_pengumuman' => $this->request->getPost('isi_pengumuman'),
                'to_pengumuman' => $this->request->getPost('to_pengumuman'),
                'created_by' => $id,
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

    public function get()
    {
        $perPage = $this->request->getVar('per_page') ?? 10;

        $data = $this->pengumumanModel->getPagination($perPage);

        return $this->respond(["status" => 200, "data" => $data], 200);
    }
}
