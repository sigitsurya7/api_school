<?php

namespace App\Controllers\MasterServices;

use App\Models\Master\LevelModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Level extends ResourceController
{
    use ResponseTrait;

    protected $levelModel;

    public function __construct()
    {
        $this->levelModel = new LevelModel();
        helper(['id_helper']);
    }
    
    private function userId(){
        
        $header = $this->request->getServer("HTTP_AUTHORIZATION");
        $userId = getid($header);

        return $userId;
    }
    
    public function get($id = null)
    {
        if($id == 'option'){
            $data = $this->levelModel->findAll();
    
            return $this->respond(['status' => true, 'data' => $data]);
        }else{
            return $this->respond(['status' => true, 'data' => $id]);
        }
    }

    
}
