<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'm_pengumuman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul_pengumuman', 'isi_pengumuman', 'to_pengumuman', 'created_by', 'updated_by', 'is_active', 'is_delete'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPagination(?int $perPage = null): array
    {
        $this->builder()
            ->select('m_pengumuman.*, auth_users.name as pembuat')
            ->join('auth_users', 'm_pengumuman.created_by = auth_users.id')
            ->where('m_pengumuman.is_delete', '0')
            ->orderBy('m_pengumuman.id', 'DESC');

        return [
            'data'  => $this->paginate($perPage),
            'pager' => $this->pager,
            'total' => $this->pager->getPageCount()
        ];
    }
}
