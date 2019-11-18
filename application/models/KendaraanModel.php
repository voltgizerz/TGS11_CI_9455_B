<?php
defined('BASEPATH') or exit('No direct script access allowed');
class KendaraanModel extends CI_Model
{
    private $table = 'vehicles';
    public $id;
    public $merk;
    public $type;
    public $licensePlate;
    public $created_at;
    public $rule = [
        [
            'field' => 'merk',
            'label' => 'merk',
            'rules' => 'required',
        ],
        [
            'field' => 'type',
            'label' => 'type',
            'rules' => 'required',
        ],
    ];

    public function Rules()
    {
        return $this->rule;
    }

    public function getAll()
    {
        return
            $this->db->get('data_mahasiswa')->result();
    }

    public function store($request)
    {
        $this->merk = $request->merk;
        $this->type = $request->type;
        $this->licensePlate = $request->licensePlate;
        $this->created_at =  date('l, d-m-Y');
        if ($this->db->insert($this->table, $this)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id)
    {
        $updateData = ['merk' => $request->merk, 'type' => $request->type, 'licensePlate'=> $request->licensePlate];
        if ($this->db->where('id', $id)->update($this->table, $updateData)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id)
    {
        if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if ($this->db->delete($this->table, array('id' => $id))) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
