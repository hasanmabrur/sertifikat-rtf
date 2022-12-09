<?php
include "koneksi.php";
error_reporting(E_ERROR | E_PARSE);

if ($_GET['id'] == '' ) {
    echo "ID Pasien Tidak Di Temukan";
} else {

$id = $_GET['id'];
function tgl_indo($tanggal)
{
	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);

	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun

	return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}




$pilih = mysqli_query ($koneksi, "SELECT a.*, b.* FROM schedule a JOIN pasien b USING (idpasien) WHERE idpasien='$id'");
$data = mysqli_fetch_assoc($pilih);

        // mengambil data dari yang dikirim dari form index.php 
        $nomorkhitan    = "$id . apa";
        $nama           = ucwords(trim($data['namapasien']));
        $namaayah       = ucwords(trim($data['wali']));
        $tanggalkhitan  = $data['tanggal'];
    
    
        //mengambil dokumen surat
        $document = file_get_contents("sertifikat.rtf");
     
    
        //mereplace semua kata yang ada di file dengan variabel
        $document = str_replace("#nomorkhitan", $nomorkhitan, $document);
        $document = str_replace("#nama", $nama, $document);
        $document = str_replace("#namaayah", $namaayah, $document);
        $document = str_replace("#tanggalkhitan", tgl_indo(date($tanggalkhitan)), $document);
     
    
        // header untuk membuka file yang dihasilkan dengna aplikasi Ms. Word
        // nama file yang dihasilkan adalah surat izin.docx
        header("Content-type: application/msword");
        header("Content-disposition: inline; filename=Sertifikat $data[namapasien].doc");
        header("Content-length: " . strlen($document));
        echo $document;
    }
    ?>
