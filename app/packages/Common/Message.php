<?php
/*
    Author by Abdul Rohim
    Library Code Message
*/

namespace Package\Common;
use Stichoza\GoogleTranslate\GoogleTranslate as Translate;


class Message
{
    public $list = [
        0 => 'Sukses! pengecekan akun berhasil.',
        1 => 'Username yang anda masukkan salah!.',
        2 => 'Password yang anda masukkan salah!.',
        3 => 'Anda tidak memiliki akses!.',
        4 => 'Hapus akun sudah berhasil!.',
        5 => 'Maaf terjadi kegagalan untuk menghapus akun!.',
        6 => 'Akun yang ingin anda hapus tidak ada didatabase!.',
        7 => 'Username sudah ada yang punya, silahkan gunakan username yang lain!.',
        8 => 'Akun berhasil di buat!.',
        9 => 'Akun gagal di buat!.',
        10 => 'Untuk akun ESS kolom EMPLOYEE_ID,COMPANY_CODE tidak boleh kosong!.',
        11 => 'Kode program tidak ditemukan atau anda belum belum mendapatkan otorisasi!.',
        12 => 'Gagal memperbarui!.',
        13 => 'Berhasil memperbarui!.',
        14 => 'Kode Role sudah ada!.',
        15 => 'Kode role tersebut tersedia!.',
        16 => 'Berhasil disimpan!.',
        17 => 'Gagal disimpan!.',
        18 => 'Berhasil dihapus!.',
        19 => 'Gagal dihapus!.',
        20 => 'Berhasil membuat akun, namun akun tersebut gagal untuk menambahkan role!.',
        21 => 'Data tidak ditemukan!.',
        22 => 'Anda sudah pernah memasukan data tersebut!.',
        23 => 'Data ditemukan!.',
        24 => 'Upload komplete!.',
        25 => 'Upload gagal!.',
        26 => 'Tanggal tidak boleh mundur!.',
        27 => 'Permintaan untuk izin keluar pekerjaan berhasil!.',
        28 => 'Permintaan untuk izin keluar pekerjaan tidak berhasil!.',
        29 => 'Persetujuan berhasil!.',
        30 => 'Persetujuan tidak berhasil!.',
        31 => 'Pembatalan izin keluar berhasil!.',
        32 => 'Pembatalan izin keluar tidak berhasil!.',
    ];

    public function get($code,$useLang = [])
    {
        if($useLang['use'] === true)
        {
            $translate = new Translate();
            $result    = $translate->setSource('id')
                        ->setTarget($useLang['lang'])
                        ->translate($this->list[$code]);
            return $result;
        }
        return $this->list[$code];
    }

    public function error($message,$useLang = [])
    {
        if($useLang['use'] === true)
        {
            $translate = new Translate();
            $result    = $translate->setSource('id')
                        ->setTarget($useLang['lang'])
                        ->translate($message);
            return $result;
        }
        return $message;
    }
}
