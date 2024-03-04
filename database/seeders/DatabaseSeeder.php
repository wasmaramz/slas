<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		\App\Models\Level::create(["level_id" => "SYST", "level_name" => "PENTADBIR SISTEM", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Level::create(["level_id" => "ADMN", "level_name" => "PENTADBIR", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Level::create(["level_id" => "KEPR", "level_name" => "KETUA PROGRAM", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Level::create(["level_id" => "TIPE", "level_name" => "TIMBALAN PENGARAH", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Level::create(["level_id" => "PEAK", "level_name" => "PENOLONG AKADEMIK", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Level::create(["level_id" => "STUD", "level_name" => "PELAJAR", "created_at" => now(), "updated_at" => now()]);

		\App\Models\UserStatus::create(["ustat_id" => "ACTIVE", "ustat_name" => "Active"]);
		\App\Models\UserStatus::create(["ustat_id" => "PENDING", "ustat_name" => "Pending"]);
		\App\Models\UserStatus::create(["ustat_id" => "INACTIVE", "ustat_name" => "Inactive"]);

		\App\Models\User::create(["user_fullname" => "ADMIN SISTEM", "user_name" => "sapot", "password" => bcrypt("abc"), "level_id" => "SYST", "user_status" => "ACTIVE", "user_email" => "support.slas@gmail.com", "user_nophone" => "", "created_by" => 1, "updated_by" => 1, "created_at" => now(), "updated_at" => now()]);
		\App\Models\User::create(["user_fullname" => "PENTADBIR 1", "user_name" => "admin", "password" => bcrypt("abc"), "level_id" => "ADMN", "user_status" => "ACTIVE", "user_email" => "admin.slas@gmail.com", "user_nophone" => "", "created_by" => 1, "updated_by" => 1, "created_at" => now(), "updated_at" => now()]);

		\App\Models\Program::create(["prog_code" => "TERA", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Terapeutik)", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Program::create(["prog_code" => "DIAGNOS", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Diagnostik)", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Program::create(["prog_code" => "RADIO", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Radiologi dan Pengimejan)", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Program::create(["prog_code" => "MAKMAL", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Makmal)", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Program::create(["prog_code" => "ICT", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Teknologi Maklumat dan Komunikasi)", "created_at" => now(), "updated_at" => now()]);
		\App\Models\Program::create(["prog_code" => "DCN", "prog_name" => "Diploma Kompetensi Rangkaian Komputer dan Sistem Pentadbiran", "created_at" => now(), "updated_at" => now()]);

		$ftype_desc1 = "Cuti melebihi 3 hari adalah dengan sokongan Ketua Program/ Ketua Jabatan dan kelulusan daripada Timbalan Pengarah(HEA)/ Timbalan Pengarah(KPHP).";
		\App\Models\FormType::create(["ftype_name" => "LAMPIRAN A", "ftype_memo" => "CUTI MELEBIHI 3 HARI", "ftype_desc" => $ftype_desc1, "created_at" => now(), "updated_at" => now()]);
		$ftype_desc2 = "Cuti kurang 3 hari adalah dengan sokongan Pensyarah yang mengajar kursus pada hari yang diambil cuti dan kelulusan daripada Ketua Program/Ketua Jabatan.";
		\App\Models\FormType::create(["ftype_name" => "LAMPIRAN B", "ftype_memo" =>"CUTI KURANG 3 HARI", "ftype_desc" => $ftype_desc2, "created_at" => now(), "updated_at" => now()]);
    }
}
