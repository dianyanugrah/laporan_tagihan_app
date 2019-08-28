<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {
	public function select_all() {
		$this->db->select('*');
		$this->db->from('laporan');

		$data = $this->db->get();

		return $data->result();
	}

	public function select_by_id($id) {
		$sql = "SELECT * FROM laporan WHERE id = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}

	// public function select_by_pegawai($id) {
	// 	$sql = " SELECT pegawai.id AS id, pegawai.nama AS pegawai, pegawai.telp AS telp, laporan.nama AS laporan, kelamin.nama AS kelamin, posisi.nama AS posisi FROM pegawai, laporan, kelamin, posisi WHERE pegawai.id_kelamin = kelamin.id AND pegawai.id_posisi = posisi.id AND pegawai.id_laporan = laporan.id AND pegawai.id_laporan={$id}";

	// 	$data = $this->db->query($sql);

	// 	return $data->result();
	// }

	public function insert($data) {
		$tgl_input = date('Y-m-d H:i:s');
		$sql = "INSERT INTO laporan VALUES('','" .$data['no_ppk'] ."','" .$data['no_agenda'] ."','" .$data['no_spm'] ."','" .$data['jenis_surat'] ."','" .$data['keterangan'] ."','" .$tgl_input."')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function insert_batch($data) {
		$this->db->insert_batch('laporan', $data);
		
		return $this->db->affected_rows();
	}

	public function update($data) {
		$sql = "UPDATE laporan SET no_ppk='" .$data['no_ppk'] ."', no_agenda='" .$data['no_agenda'] ."', no_spm='" .$data['no_spm'] ."', jenis_surat='" .$data['jenis_surat'] ."', keterangan='" .$data['keterangan'] ."' WHERE id='" .$data['id'] ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function delete($id) {
		$sql = "DELETE FROM laporan WHERE id='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function check_nama($nama) {
		$this->db->where('no_ppk', $no_ppk);
		$data = $this->db->get('laporan');

		return $data->num_rows();
	}

	public function total_rows_ls() {
		$this->db->where('jenis_surat', 'ls');
		$data = $this->db->get('laporan');

		return $data->num_rows();
	}

	public function total_rows_up() {
		$this->db->where('jenis_surat', 'ls');
		$data = $this->db->get('laporan');

		return $data->num_rows();
	}

	public function total_rows() {
		$data = $this->db->get('laporan');

		return $data->num_rows();
	}
}

/* End of file M_laporan.php */
/* Location: ./application/models/M_laporan.php */