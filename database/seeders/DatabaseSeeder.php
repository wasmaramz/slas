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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

		\App\Models\User::upsert([
			["user_name" => "sapot", "password" => bcrypt("abc"), "level_id" => "ADMN", "user_status" => "ACTIVE", "user_email" => "support.slas@gmail.com", "user_nophone" => "", "created_by" => 1, "updated_by" => 1, "created_at" => now(), "updated_at" => now()],
		], ["user_name", "password", "level_id", "user_status", "user_email", "user_nophone", "created_by", "updated_by", "created_at", "updated_at"]);

		\App\Models\Level::upsert([
			["level_id" => "ADMN", "level_name" => "PENTADBIR", "created_at" => now(), "updated_at" => now()],
			["level_id" => "STUD", "level_name" => "PELAJAR", "created_at" => now(), "updated_at" => now()],
			["level_id" => "PEAK", "level_name" => "PENOLONG AKADEMIK", "created_at" => now(), "updated_at" => now()],
			["level_id" => "KEPR", "level_name" => "KETUA PROGRAM", "created_at" => now(), "updated_at" => now()],
			["level_id" => "TIPE", "level_name" => "TIMBALAN PENGARAH", "created_at" => now(), "updated_at" => now()],
		],["level_id", "level_name", "created_at", "updated_at"]);

		\App\Models\Program::upsert([
			["prog_code" => "TERA", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Terapeutik)", "created_at" => now(), "updated_at" => now()],
			["prog_code" => "DIAGNOS", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Diagnostik)", "created_at" => now(), "updated_at" => now()],
			["prog_code" => "RADIO", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Radiologi dan Pengimejan)", "created_at" => now(), "updated_at" => now()],
			["prog_code" => "MAKMAL", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Makmal)", "created_at" => now(), "updated_at" => now()],
			["prog_code" => "ICT", "prog_name" => "Diploma Kejuruteraan Elektronik Bioperubatan (Teknologi Maklumat dan Komunikasi)", "created_at" => now(), "updated_at" => now()],
			["prog_code" => "DCN", "prog_name" => "Diploma Kompetensi Rangkaian Komputer dan Sistem Pentadbiran", "created_at" => now(), "updated_at" => now()],
		],["prog_code", "prog_name", "created_at", "updated_at"]);

		\App\Models\FormType::upsert([
			["ftype_name" => "LAMPIRAN A", "ftype_desc" => "KELULUSAN CUTI MELEBIHI 3 HARI", "created_at" => now(), "updated_at" => now()],
			["ftype_name" => "LAMPIRAN B", "ftype_desc" => "KELULUSAN CUTI KURANG 3 HARI", "created_at" => now(), "updated_at" => now()],
		], ["ftype_name", "ftype_desc", "created_at", "updated_at"]);
    }
}
