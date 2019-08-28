<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends AUTH_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_laporan');
	}

	public function index() {
		$data['userdata'] 	= $this->userdata;
		$data['dataLaporan'] 	= $this->M_laporan->select_all();

		$data['page'] 		= "laporan";
		$data['judul'] 		= "Data Tagihan Keuangan";
		$data['deskripsi'] 	= "Manage Data Tagihan Keuangan";

		$data['modal_tambah_laporan'] = show_my_modal('modals/modal_tambah_laporan', 'tambah-laporan', $data);

		$this->template->views('laporan/home', $data);
	}

	public function tampil() {
		$data['dataLaporan'] = $this->M_laporan->select_all();
		$this->load->view('laporan/list_data', $data);
	}

	public function prosesTambah() {
		$this->form_validation->set_rules('no_ppk', 'No PPK', 'trim|required');
		$this->form_validation->set_rules('no_agenda', 'No Agenda', 'trim|required');
		$this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->M_laporan->insert($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Tagihan Berhasil ditambahkan', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Tagihan Gagal ditambahkan', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function update() {
		$data['userdata'] 	= $this->userdata;

		$id 				= trim($_POST['id']);
		$data['dataLaporan'] 	= $this->M_laporan->select_by_id($id);

		echo show_my_modal('modals/modal_update_laporan', 'update-laporan', $data);
	}

	public function prosesUpdate() {
		$this->form_validation->set_rules('no_ppk', 'No PPK', 'trim|required');
		// $this->form_validation->set_rules('no_agen', 'No Agenda', 'trim|required');
		$this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		$jenis_surat 	= $this->input->post("jenis_surat");
		if ($jenis_surat == "up") {
			$data = array(
				"id" => $this->input->post("id"),
				"no_ppk" => $this->input->post("no_ppk"),
				"no_agenda" => $this->input->post("no_agenda"),
				"jenis_surat" => $this->input->post("jenis_surat"),
				"no_spm" => '',
				"keterangan" => $this->input->post("keterangan")
			);
		}else{
			$data = array(
				"id" => $this->input->post("id"),
				"no_ppk" => $this->input->post("no_ppk"),
				"no_agenda" => $this->input->post("no_agenda"),
				"jenis_surat" => $this->input->post("jenis_surat"),
				"no_spm" => $this->input->post("no_spm"),
				"keterangan" => $this->input->post("keterangan")
			);
		}
		
		if ($this->form_validation->run() == TRUE) {
			$result = $this->M_laporan->update($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Tagihan Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data Tagihan Gagal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function delete() {
		$id = $_POST['id'];
		$result = $this->M_laporan->delete($id);
		
		if ($result > 0) {
			echo show_succ_msg('Data Tagihan Berhasil dihapus', '20px');
		} else {
			echo show_err_msg('Data Tagihan Gagal dihapus', '20px');
		}
	}

	public function detail() {
		$data['userdata'] 	= $this->userdata;

		$id 				= trim($_POST['id']);
		$data['laporan'] = $this->M_laporan->select_by_id($id);
		$data['jumlahLaporan'] = $this->M_laporan->total_rows();
		$data['dataLaporan'] = $this->M_laporan->select_by_pegawai($id);

		echo show_my_modal('modals/modal_detail_laporan', 'detail-laporan', $data, 'lg');
	}

	public function export() {
		error_reporting(E_ALL);
    
		include_once './assets/phpexcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();

		$data = $this->M_laporan->select_all();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0); 

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', "ID"); 
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', "NO PPK");
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', "NO SPM");
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', "JENIS SURAT");
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', "Keterangan");
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', "Tanggal Input");

		$rowCount = 2;
		foreach($data as $value){
		    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $value->id); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->no_ppk);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->no_spm);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->jenis_surat);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->keterangan);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->tgl_input); 
		    $rowCount++; 
		} 

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		$objWriter->save('./assets/excel/Data Tagihan.xlsx'); 

		$this->load->helper('download');
		force_download('./assets/excel/Data Tagihan.xlsx', NULL);
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
						$check = $this->M_laporan->check_nama($value['B']);

						if ($check != 1) {
							$resultData[$index]['id'] = $value['A'];
							$resultData[$index]['no_ppk'] = ucwords($value['B']);
							$resultData[$index]['no_spm'] = $value['C'];
							$resultData[$index]['jenis_surat'] = $value['D'];
							$resultData[$index]['keterangan'] = $value['E'];
							$resultData[$index]['tgl_input'] = $value['F'];
						}
					}
					$index++;
				}

				unlink('./assets/excel/' .$data['file_name']);

				if (count($resultData) != 0) {
					$result = $this->M_laporan->insert_batch($resultData);
					if ($result > 0) {
						$this->session->set_flashdata('msg', show_succ_msg('Data Tagihan Berhasil diimport ke database'));
						redirect('Laporan');
					}
				} else {
					$this->session->set_flashdata('msg', show_msg('Data Tagihan Gagal diimport ke database (Data Sudah terupdate)', 'warning', 'fa-warning'));
					redirect('Laporan');
				}

			}
		}
	}
}

/* End of file Kota.php */
/* Location: ./application/controllers/Kota.php */