<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('FILE_UPLOAD_TEMP', 'file_upload/temp/');
define('FILE_APPLICATION', 'file_upload/application/');
define('FILE_ACCOUNT', 'file_upload/Account/');

define('STATUS_ACTIVE',1);
define('STATUS_INACTIVE',2);

define('SOFT_DELETE_TRUE',1);
define('SOFT_DELETE_FALSE',0);

define('DEPARTMENT_LEVEL_1',1);
define('DEPARTMENT_LEVEL_2',2);

define('TEXT_DELETE_RECORD','Rekod telah dipadam');
define('TEXT_REREGISTER_RECORD','Ticket has been reopened');
define('TEXT_SAVE_RECORD','Data berjaya disimpan');
define('TEXT_UPDATE_RECORD','Data berjaya dikemaskini');
define('TEXT_SAVE_UNSUCCESSFUL','Data tidak berjaya disimpan');
define('TEXT_UPDATE_UNSUCCESSFUL','Tiada perubahan dilakukan');
define('TEXT_DELETE_UNSUCCESSFUL','Data tidak berjaya dipadam');

define('ACCESS_LEVEL_ALL',1);
define('ACCESS_LEVEL_OWN',2);

define('DEFAULT_PASSWORD','password123');

define('PROCESS_STATUS_SUCCEED',1);
define('PROCESS_STATUS_FAILED',2);

define('APPLICANT_TYPE_INDIVIDUAL',1);
define('APPLICANT_TYPE_COMPANY',2);

define('STATUS_APPLICATION_NEW',1);
define('STATUS_APPLICATION_APPROVED',2);
define('STATUS_APPLICATION_REJECTED',3);
define('STATUS_APPLICATION_KIV',4);

define('STATUS_AGREE_DEFAULT',1);
define('STATUS_AGREE_ACCEPTED',2);
define('STATUS_AGREE_REJECTED',3);

define('RACE_MELAYU',1);
define('RACE_CINA',2);
define('RACE_INDIA',3);
define('RACE_OTHERS',4);

define('MARITAL_STATUS_SINGLE',1);
define('MARITAL_STATUS_MARRIED',2);
define('MARITAL_STATUS_SINGLE_PARENT',3);
define('MARITAL_STATUS_OTHERS',4);

define('OCCUPATION_STATUS_WORKING',1);
define('OCCUPATION_STATUS_UNEMPLOYED',2);

define('STATUS_CREATE_ACCOUNT_YES',1);
define('STATUS_CREATE_ACCOUNT_NO',0);

define('FILE_MODULE_TYPE_CATEGORY',1);
define('FILE_MODULE_TYPE_ASSET',2);
define('FILE_MODULE_TYPE_APPLICATION',3);
define('FILE_MODULE_TYPE_ACCOUNT',4);

define('REF_NUMBER_TYPE_APPLICATION',1);
define('REF_NUMBER_TYPE_ACCOUNT',2);

define('RENTAL_USE_OTHERS',40);

define('DOC_INTERVIEW_RATING',1);
define('DOC_PANEL_REVIEW',2);
define('DOC_OFFER_LETTER',3);
define('DOC_ACCEPTANCE_LETTER',4);
define('DOC_AGREEMENT',5);
define('DOC_NOTICE',6);
define('DOC_QUARTERS',7);
define('DOC_SIGNATURE',8);
define('DOC_JOURNAL',9);
define('SURAT_PENGAMBILAN_PERJANJIAN',10);

define('HARTA_GAMBAR_LOKASI',1);
define('HARTA_PANDANGAN_SATELIT',2);
define('HARTA_PETA',3);
define('HARTA_PELAN_LOKASI_HARTA',4);
define('UNIT_PELAN_LOKASI_UNIT',5);
define('PERMOHONAN_SALINAN_KAD_PENGENALAN',6);
define('PERMOHONAN_GAMBAR_PASPORT',7);
define('PERMOHONAN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM',8);
define('PERMOHONAN_PELAN_STRUKTUR',9);
define('PERMOHONAN_PELAN_LOKASI',10);
define('PERMOHONAN_MAP_INFO',11);
define('PERMOHONAN_SURAT_PERMOHONAN',12);
define('PERMOHONAN_FOTO_LOKASI',13);
define('PERMOHONAN_SALINAN_CADANGAN_PELAN_STRUKTUR',14);
define('PERMOHONAN_CARIAN_SSM',15);
define('PERMOHONAN_LAMPIRAN_PERMOHONAN',16);
define('PERMOHONAN_LAMPIRAN_SETUJU_TERIMA',17);
define('PERMOHONAN_LAMPIRAN_PENGESAHAN_KOS_BINAAN',18);

define('AKAUN_SALINAN_KAD_PENGENALAN',19);
define('AKAUN_GAMBAR_PASPORT',20);
define('AKAUN_SALINAN_PENDAFTARAN_PERNIAGAAN_SSM',21);
define('AKAUN_PELAN_STRUKTUR',22);
define('AKAUN_PELAN_LOKASI',23);
define('AKAUN_MAP_INFO',24);
define('AKAUN_SURAT_PERMOHONAN',25);
define('AKAUN_FOTO_LOKASI',26);
define('AKAUN_SALINAN_CADANGAN_PELAN_STRUKTUR',27);
define('AKAUN_CARIAN_SSM',28);
define('AKAUN_LAMPIRAN_PERMOHONAN',29);
define('AKAUN_LAMPIRAN_SETUJU_TERIMA',30);
define('AKAUN_LAMPIRAN_PENGESAHAN_KOS_BINAAN',31);
define('AKAUN_DOKUMEN_TAMBAHAN',32);

define('NOTICE_LEVEL_1',1);
define('NOTICE_LEVEL_2',2);
define('NOTICE_LEVEL_3',3);
define('NOTICE_LEVEL_4',4);
define('NOTICE_LEVEL_5',5);
define('NOTICE_LEVEL_6',6);

define('SELANGOR_IC_CODE','10|41|42|43|44');

define('DEPENDENT_LIST_ARR',serialize(array('Anak','Isteri','Ibu','Ayah','Adik bawah 18 tahun')));
define('STATE_LIST',serialize(array('SELANGOR','JOHOR','KEDAH','KELANTAN','KUALA LUMPUR','LABUAN','MELAKA','NEGERI SEMBILAN','PAHANG','PUTRAJAYA','PERLIS','PULAU PINANG','PERAK','TERENGGANU','SABAH','SARAWAK')));

// For Production Env Only
/*

define('DEPENDENT_LIST_ARR',array('Anak','Isteri','Ibu','Ayah','Adik bawah 18 tahun'));
define('STATE_LIST',array('SELANGOR','JOHOR','KEDAH','KELANTAN','KUALA LUMPUR','LABUAN','MELAKA','NEGERI SEMBILAN','PAHANG','PUTRAJAYA','PERLIS','PULAU PINANG','PERAK','TERENGGANU','SABAH','SARAWAK'));

*/

define('RENTAL_STATUS_YES',1);
define('RENTAL_STATUS_NO',0);

define('STATUS_ACCOUNT_ACTIVE',1);
define('STATUS_ACCOUNT_NONACTIVE',2);

define('STATUS_BILL_ACTIVE',1);
define('STATUS_BILL_NONACTIVE',2);

define('TR_ID_LMS','11');
define('TR_ID_LPS','120');
define('TR_ID_TIPPING','14');

define('BILL_TYPE_MONTHLY',1);
define('BILL_TYPE_ANNUALLY',2);
define('BILL_TYPE_NO_PROCESS',3);

define('GST_TYPE_RENTAL',1);
define('GST_TYPE_WATER',2);
define('GST_TYPE_TIPPING',3);

define('TR_CODE_TIPPING','11110014');

define('TR_CODE_GST_TIPPING',11110029);
define('TR_CODE_GST_WATER',11110029);
define('TR_CODE_LMS',11110011);
define('TR_CODE_FAEDAH_8_PERC',11110023);
define('TR_CODE_FAEDAH_8_PERC_OLD',11036);
define('TR_CODE_OLD_FAEDAH_8_PERC',11036);
define('TR_CODE_LEBIHAN',11119999);
define('TR_CODE_OLD_LEBIHAN',11090);

define('TR_CODE_DESC_FAEDAH_8_PERC','FAEDAH 8% TUNTUTAN SEWAAN');

define('CHARGE_FAEDAH_SEWAAN_PERC',0.08);
define('CHARGE_FAEDAH_PRIORITY',7);

define('BILLBOARD_TYPE_INTERIM',1);
define('BILLBOARD_TYPE_SUBLESEN',2);

define('LMS_CHARGE',3000.00);
define('CHECK_ADMIN',false);
define('ADMIN_ID',1);

//define('TR_CODE_BIL_AIR',11110016);
//define('TR_CODE_BIL_AIR',11110016);