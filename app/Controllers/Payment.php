<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use DateTime;
use DateTimeZone;

class Payment extends BaseController
{
    public function __construct()
    {
        helper('form');
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->session->start();
        $this->email = \Config\Services::email();

        // $this->transaksimidtrans = new \App\Models\TransaksiMidtransModel();
        $this->transaksi = new \App\Models\TransaksiModel();
        $this->transaksipromo = new \App\Models\TransaksiPromoModel();
        $this->banner = new \App\Models\BannerModel();
        $this->user = new \App\Models\UserModel();
    }

    public function index()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-XPVlhdgn-tQoGUS0jp849vuB';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $id_user = $_POST['id_user'];
        $id_banner = $_POST['id_banner'];
        $nama_pembeli = $_POST['nama_pembeli'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $total_harga = $_POST['total_harga'];
        $jumlah = $_POST['jumlah'];
        $total_dp = $_POST['total_dp'];
        $handphone = $_POST['handphone'];
        $alamat = $_POST['alamat'];

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $total_dp,
                // 'gross_amount' => 100,
            ),
            'customer_details' => array(
                'first_name' => $nama_pembeli,
                'email' => $email,
                'phone' => $handphone,
                'address' => $alamat,
            ),
            'item_details' => array(
                array(
                    'id'       => 'Paket Travel',
                    'price'    => $total_dp / $jumlah,
                    'quantity' => $jumlah,
                    'name'     => $nama
                ),
            )
        );

        $data = [
            'snapToken' => \Midtrans\Snap::getSnapToken($params),
            'status' => 'Success',
            'total' => $total_dp,
            'nama' => $nama,
            'nama_pembeli' => $nama_pembeli,
            'jumlah' => $jumlah,
            'handphone' => $handphone,
            'email' => $email,
            'total_harga' => $total_harga,
            'id_banner' => $id_banner,
            'id_user' => $id_user,
            'alamat' => $alamat,
            // 'cekdata' => $cekdata
        ];
        return json_encode($data);
    }
}
