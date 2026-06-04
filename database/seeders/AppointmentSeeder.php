<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentAddon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // 預約1：王小明帶球球（dog）預約狗狗小套餐，燕巢旗艦店，林美容師
        $appt1 = Appointment::create([
            'customer_id' => 4,
            'groomer_id' => 2,
            'pet_id' => 1,
            'service_id' => 2, // 狗狗小套餐 900
            'store_id' => 1,
            'appointment_at' => now()->addDays(3)->setTime(10, 0),
            'status' => 'pending',
            'total_price' => 1100,
            'note' => '請使用低敏洗毛精。',
        ]);
        AppointmentAddon::create([
            'appointment_id' => $appt1->id,
            'addon_service_id' => 1, // 香氛護毛 200
            'price' => 200,
        ]);

        // 預約2：王小明帶咪咪（cat）預約貓咪基礎洗澡，已完成
        $appt2 = Appointment::create([
            'customer_id' => 4,
            'groomer_id' => 3,
            'pet_id' => 2,
            'service_id' => 4, // 貓咪基礎洗澡 700
            'store_id' => 2,
            'appointment_at' => now()->subDays(7)->setTime(14, 0),
            'status' => 'completed',
            'total_price' => 1050,
            'note' => null,
        ]);
        AppointmentAddon::create([
            'appointment_id' => $appt2->id,
            'addon_service_id' => 3, // 深層去油（限貓咪）350
            'price' => 350,
        ]);

        // 預約3：張小華帶花花（dog）預約狗狗大套餐，進行中
        Appointment::create([
            'customer_id' => 5,
            'groomer_id' => 2,
            'pet_id' => 3,
            'service_id' => 3, // 狗狗大套餐 1500
            'store_id' => 1,
            'appointment_at' => now()->setTime(11, 0),
            'status' => 'in_progress',
            'total_price' => 1500,
            'note' => null,
        ]);
    }
}
