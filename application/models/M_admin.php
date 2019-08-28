<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {
	public function updateprofile($data, $id) {
		$this->db->where("id", $id);
		$this->db->update("admin", $data);

		return $this->db->affected_rows();
	}

	public function select_all() {
		$data = $this->db->get('admin');

		return $data->result();
	}

	public function select($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
		}

		$data = $this->db->get('admin');

		return $data->row();
	}

	public function select_by_id($id) {
		$sql = "SELECT * FROM admin WHERE id = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}

	public function delete($id) {
		$sql = "DELETE FROM admin WHERE id='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function insert_admin($data) {
		$data = array(
							 'username' => $data['username'],
							 'password' => $data['password'],
							 'nama' => $data['nama'],
							 'level' => $data['level'],
							 'status' => $data['status']
					 );
		$this->db->insert("admin", $data);

		return $this->db->affected_rows();
	}

	public function update($data) {
		$pass = md5($data['password']);
		$sql = "UPDATE admin SET nama='" .$data['nama'] ."', username='" .$data['username'] ."', password='{$pass}', foto='" .$data['foto'] ."' WHERE id='" .$data['id'] ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}


	public function insert_batch($data) {
		$this->db->insert_batch('admin', $data);

		return $this->db->affected_rows();
	}

	public function check_nama($nama) {
		$this->db->where('nama', $nama);
		$data = $this->db->get('admin');

		return $data->num_rows();
	}

	public function total_rows() {
		$data = $this->db->get('admin');

		return $data->num_rows();
	}

	function simpan_upload($simpan,$image){

				$now = date('Y-m-d H:i:s');
        $data = array(
                'username' => $simpan['username'],
								'password' => $simpan['password'],
								'nama' => $simpan['nama'],
								'email' => $simpan['email'],
								'status' => '1',
								'level' => '2',
								'created' => $now,
								'updated' => $now
            );
				if($image != '')
				{
					$data['foto'] = $image;
				}
        $result= $this->db->insert('admin',$data);
        return $result;
  }

	function update_upload($simpan,$image){

				$now = date('Y-m-d H:i:s');
        $data = array(
                'username' => $simpan['username'],
								'nama' => $simpan['nama'],
								'email' => $simpan['email'],
								'status' => '1',
								'level' => $simpan['level'],
								'updated' => $now
            );

				if($image != '')
				{
					$data['foto'] = $image;
				}

				$this->db->where('id',$simpan['id']);
				$this->db->update('admin',$data);

				return $this->db->affected_rows();
  }

	//--region new user + activate--//

	public function newuser($data,$image) {
		$data = array(
							 'username' => $data['username'],
							 'password' => $data['password'],
							 'nama' => $data['nama'],
							 'foto' => $image,
							 'email' => $data['email'],
							 'level' => $data['level'],
							 'status' => $data['status']
					 );
		$this->db->insert("admin", $data);

		return $this->db->affected_rows();
	}

	public function activate($token)
	{
		$data = array('status' => 1);
		// $this->db->set('status','1');
		$this->db->where('md5(email)',$token);
		$this->db->update('admin',$data);
		return $this->db->affected_rows();
	}


//-----region forgot password-----------//

	public function getByEmail($email)
	{
	  $this->db->where('email',$email);
	  $result = $this->db->get('admin');
	  return $result;
	}

	public function simpanToken($data)
	{
	  $this->db->insert('tbl_forgot_password', $data);
	  return $this->db->affected_rows();
	}

	public function cekToken($token)
	{
	  $this->db->where('token',$token);
		$this->db->where('reset != "Y"');
	  $result = $this->db->get('tbl_forgot_password');
	  return $result;
	}

	public function updatePass($data,$id) {
		$pass = md5($data['password']);
		$sql = "UPDATE admin SET password='{$pass}' WHERE id='" .$id."'";
		$sql2 = "UPDATE tbl_forgot_password SET reset='Y' WHERE id_forgot='" .$data['id_forgot']."'";

		$this->db->query($sql);
		$this->db->query($sql2);

		return $this->db->affected_rows();
	}
}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */
