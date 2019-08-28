<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends AUTH_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_admin');

	}

	public function index() {
		$data['userdata'] = $this->userdata;
		$data['dataAdmin'] = $this->M_admin->select_all();
		// $data['dataKota'] = $this->M_kota->select_all();

		$data['page'] = "users";
		$data['judul'] = "Data Users";
		$data['deskripsi'] = "Manage Data Users";

		$data['modal_tambah_admin'] = show_my_modal('modals/modal_tambah_admin', 'tambah-admin', $data);

		$this->template->views('users/home', $data);
	}

	public function tampil() {
		$data['dataAdmin'] = $this->M_admin->select_all();
		$this->load->view('users/list_data', $data);
	}

	public function prosesTambah() {
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('level', 'Level', 'required');
		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {

			$data = [
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'nama' => $this->input->post('nama'),				
				'level' => $this->input->post('level'),
				'status' => 1
			];

			$result = $this->M_admin->insert_admin($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Admin Berhasil ditambahkan', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Admin Gagal ditambahkan', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function update() {
		$data['userdata'] 	= $this->userdata;

		$id = $_POST['id'];
		$data['dataUser'] = $this->M_admin->select_by_id($id);

		echo show_my_modal('modals/modal_update_admin', 'update-admin', $data);
	}

	public function prosesUpdate() {
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');

		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->M_admin->update($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Admin Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Admin Gagal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function delete() {
		$id = $_POST['id'];
		$result = $this->M_admin->delete($id);

		if ($result > 0) {
			echo show_succ_msg('Data Admin Berhasil dihapus', '20px');
		} else {
			echo show_err_msg('Data Admin Gagal dihapus', '20px');
		}
	}

	public function export() {
		error_reporting(E_ALL);

		include_once './assets/phpexcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();

		$data = $this->M_admin->select_all();

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$rowCount = 1;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "ID");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "USERNAME");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "NAMA");
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "FOTO");
		$rowCount++;

		foreach($data as $value){
		    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $value->id);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->username);
		    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $value->nama);
		    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $value->foto);
		    $rowCount++;
		}

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('./assets/excel/Data Admin.xlsx');

		$this->load->helper('download');
		force_download('./assets/excel/Data Admin.xlsx', NULL);
	}

	public function import() {
		$this->form_validation->set_rules('excel', 'File', 'trim|required');

		if ($_FILES['excel']['name'] == '') {
			$this->session->set_flashdata('msg', 'File harus diisi');
		} else {
			$config['upload_path'] = './assets/excel/';
			$config['allowed_types'] = 'xls|xlsx';

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('excel')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data = $this->upload->data();

				error_reporting(E_ALL);
				date_default_timezone_set('Asia/Jakarta');

				include './assets/phpexcel/Classes/PHPExcel/IOFactory.php';

				$inputFileName = './assets/excel/' .$data['file_name'];
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

				$index = 0;
				foreach ($sheetData as $key => $value) {
					if ($key != 1) {
						$id = md5(DATE('ymdhms').rand());
						$check = $this->M_pegawai->check_nama($value['B']);

						if ($check != 1) {
							$resultData[$index]['id'] = $id;
							$resultData[$index]['nama'] = ucwords($value['B']);
							$resultData[$index]['telp'] = $value['C'];
							$resultData[$index]['id_kota'] = $value['D'];
							$resultData[$index]['id_kelamin'] = $value['E'];
							$resultData[$index]['id_posisi'] = $value['F'];
							$resultData[$index]['status'] = $value['G'];
						}
					}
					$index++;
				}

				unlink('./assets/excel/' .$data['file_name']);

				if (count($resultData) != 0) {
					$result = $this->M_pegawai->insert_batch($resultData);
					if ($result > 0) {
						$this->session->set_flashdata('msg', show_succ_msg('Data Pegawai Berhasil diimport ke database'));
						redirect('Pegawai');
					}
				} else {
					$this->session->set_flashdata('msg', show_msg('Data Pegawai Gagal diimport ke database (Data Sudah terupdate)', 'warning', 'fa-warning'));
					redirect('Pegawai');
				}

			}
		}
	}

	public function do_upload()
	{
		$image = "";
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('level', 'Level', 'required');
		// $this->form_validation->set_rules('foto', 'Foto', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
        $config['upload_path']="./assets/img/"; //path folder file upload
        $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
        // $config['encrypt_name'] = TRUE; //enkripsi file name upload

        $this->load->library('upload',$config); //call library upload
        if($this->upload->do_upload("file"))
				{ //upload file
            $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
						$image= $data['upload_data']['file_name']; //set file name ke variable image
        }

				$simpan['username']= $this->input->post('username'); //get username
				$simpan['password']= md5($this->input->post('password')); //get pass
				$simpan['nama']= $this->input->post('nama'); //get nama
				$simpan['email']= $this->input->post('email'); //get email
				$simpan['status']= '1'; //get status
				$simpan['level']= $this->input->post('level'); //get status

				$result= $this->M_admin->simpan_upload($simpan,$image); //kirim value ke model m_upload

				if ($result > 0) {
					$out['status'] = '';
					$out['msg'] = show_succ_msg('Data Admin Berhasil Ditambah', '20px');
				} else {
					$out['status'] = '';
					$out['msg'] = show_err_msg('Data Admin Gagal Ditambah', '20px');
				}

			}else {
				$out['status'] = 'form';
				$out['msg'] = show_err_msg(validation_errors());
			}
	  echo json_encode($out);
	}

	public function do_update()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$image = "";
        $config['upload_path']="./assets/img/"; //path folder file upload
        $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
        // $config['encrypt_name'] = TRUE; //enkripsi file name upload

        $this->load->library('upload',$config); //call library upload
        if($this->upload->do_upload("foto")){ //upload file
            $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
						$image= $data['upload_data']['file_name']; //set file name ke variable image
				}

				$simpan['id']= $this->input->post('id'); //get username
				$simpan['username']= $this->input->post('username'); //get username
				$simpan['nama']= $this->input->post('nama'); //get nama
				$simpan['email']= $this->input->post('email'); //get email
				$simpan['status']= '1'; //get status
				$simpan['level']= $this->input->post('level'); //get level

				$result= $this->M_admin->update_upload($simpan,$image); //kirim value ke model m_upload

				if ($result > 0) {
					$out['status'] = '';
					$out['msg'] = show_succ_msg('Data Admin Berhasil di-Update', '20px');
				} else {
					$out['status'] = '';
					$out['msg'] = show_err_msg('Data Admin Gagal di-Update', '20px');
				}
			echo json_encode($out);
	}
}

/* End of file Pegawai.php */
/* Location: ./application/controllers/Pegawai.php */
