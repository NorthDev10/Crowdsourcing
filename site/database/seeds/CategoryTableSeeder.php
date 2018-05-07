<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Веб-сайты, IT и ПО',
            ],
            [
                'name' => 'Мобильная разработка',
            ],
            [
                'name' => 'Письмо и контент',
            ],
            [
                'name' => 'Дизайн, медиа и архитектура',
            ],
            [
                'name' => 'Ввод и администрирование данных',
            ],
            [
                'name' => 'Наука и техника',
            ],
            [
                'name' => 'Снабжение и производство',
            ],
            [
                'name' => 'Продажи и маркетинг',
            ],
            [
                'name' => 'Бизнес, бухгалтерия, трудовые ресурсы и право',
            ],
            [
                'name' => 'Перевод и языки',
            ],
            [
                'name' => 'Местные работы и услуги',
            ],
            [
                'name' => 'Другое',
            ],
        ]);
    }
}
