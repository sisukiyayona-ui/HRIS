<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'karyawan';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Routes untuk Absen Finger
$route['absensi'] = 'rekap/absensi';
$route['rekap'] = 'rekap/kehadiran';

// Routes untuk API Rekap
$route['rekap/tarik_ui'] = 'rekap/tarik_ui';
$route['rekap/tarik'] = 'rekap/tarik';
$route['rekap/tarik_semua'] = 'rekap/tarik_semua';
$route['rekap/mapping_ui'] = 'rekap/mapping_ui';
$route['rekap/rebuild_mapping'] = 'rekap/rebuild_mapping';
$route['rekap/status_mapping'] = 'rekap/status_mapping';
$route['rekap/reset'] = 'rekap/reset';
$route['rekap/hapus_lama'] = 'rekap/hapus_lama';
$route['rekap/mapping'] = 'rekap/mapping';
$route['rekap/cek'] = 'rekap/cek';
$route['rekap/sync'] = 'rekap/sync';
$route['rekap/absensi'] = 'rekap/absensi';
$route['rekap/rekap_bulanan'] = 'rekap/rekap_bulanan';
$route['rekap/bulanan'] = 'rekap/bulanan';
$route['rekap/export_bulanan'] = 'rekap/export_bulanan';
$route['rekap/history_absensi'] = 'rekap/history_absensi';
$route['rekap/get_history_by_month'] = 'rekap/get_history_by_month';
$route['rekap/export_history_bulanan'] = 'rekap/export_history_bulanan';
$route['rekap/export_kehadiran_bulanan'] = 'rekap/export_kehadiran_bulanan';

// Routes untuk Pindah Bagian (Bulk Update)
$route['karyawan/pindah_bagian_bulk'] = 'karyawan/pindah_bagian_bulk';
$route['karyawan/get_karyawan_by_filter'] = 'karyawan/get_karyawan_by_filter';
$route['karyawan/process_pindah_bagian'] = 'karyawan/process_pindah_bagian';
$route['karyawan/tracking_perubahan_bagian'] = 'karyawan/tracking_perubahan_bagian';
$route['karyawan/detail_perubahan'] = 'karyawan/detail_perubahan';

// Routes untuk Setup Bagian Karyawan (Initial Setup - No Log)
$route['karyawan/setup_bagian_karyawan'] = 'karyawan/setup_bagian_karyawan';
$route['karyawan/get_karyawan_setup'] = 'karyawan/get_karyawan_setup';
$route['karyawan/process_setup_bagian'] = 'karyawan/process_setup_bagian';

// Routes untuk Setup Bagian (Bulk)
$route['karyawan/setup_bagian_bulk'] = 'karyawan/setup_bagian_bulk';
$route['karyawan/get_karyawan_aktif'] = 'karyawan/get_karyawan_aktif';
$route['karyawan/process_setup_bagian_bulk'] = 'karyawan/process_setup_bagian_bulk';

// Routes untuk Terlambat & Izin (Absen Finger)
$route['absen/terlambat_list'] = 'absen/terlambat_list';
$route['absen/validasi_terlambat'] = 'absen/validasi_terlambat';
$route['absen/get_terlambat_data'] = 'absen/get_terlambat_data';
$route['absen/izin_list'] = 'absen/izin_list';

// Routes untuk Rekap Absensi Validasi
$route['rekap/validasi_terlambat_izin'] = 'rekap/validasi_terlambat_izin';
$route['rekap/simpan_izin_belum_absen'] = 'rekap/simpan_izin_belum_absen';

// Routes for Log Izin (Finger)
$route['rekap/izin_log'] = 'rekap/izin_log';
$route['rekap/get_izin_log'] = 'rekap/get_izin_log';

// Routes for Statistik Bagian (Absen Finger)
$route['rekap/statistik_bagian'] = 'rekap/statistik_bagian';
$route['rekap/get_statistik_bagian'] = 'rekap/get_statistik_bagian';
$route['rekap/debug_statistik_bagian'] = 'rekap/debug_statistik_bagian';
$route['rekap/export_statistik_bagian'] = 'rekap/export_statistik_bagian';

// Routes for Jadwal Shift (Absen Finger)
$route['rekap/jadwal_shift'] = 'rekap/jadwal_shift';
$route['rekap/get_karyawan_aktif'] = 'rekap/get_karyawan_aktif';
$route['rekap/get_jadwal_shift'] = 'rekap/get_jadwal_shift';
$route['rekap/simpan_jadwal_shift'] = 'rekap/simpan_jadwal_shift';
$route['rekap/hapus_jadwal_shift'] = 'rekap/hapus_jadwal_shift';
$route['rekap/get_shift_list'] = 'rekap/get_shift_list';
$route['rekap/create_custom_shift'] = 'rekap/create_custom_shift';




