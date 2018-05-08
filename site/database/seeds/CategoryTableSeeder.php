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
        $categoriesList = [
            [//1
                'title' => 'Веб-сайты',
                'parent_id' => 0,
            ],
            [//2
                'title' => 'Front-end',
                'parent_id' => 1,
            ],
            [//3
                'title' => 'HTML-верстка',
                'parent_id' => 2,
            ],
            [//4
                'title' => 'Программирование',
                'parent_id' => 2,
            ],
            [//5
                'title' => 'Продвижение сайтов',
                'parent_id' => 0,
            ],
            [//6
                'title' => 'Контекстная реклама',
                'parent_id' => 5,
            ],
            [//7
                'title' => 'Поисковые системы (SEO)',
                'parent_id' => 5,
            ],
            [//8
                'title' => 'Социальные сети (SMM и SMO)',
                'parent_id' => 5,
            ],
            [//9
                'title' => 'Тестирование ПО',
                'parent_id' => 2,
            ],
            [//10
                'title' => 'Одностраничный сайт SPA',
                'parent_id' => 4,
            ],
            [//11
                'title' => 'JavaScript скрипты',
                'parent_id' => 4,
            ],
            [//12
                'title' => 'jQuery скрипты',
                'parent_id' => 4,
            ],
            [//13
                'title' => 'Веб-дизайн и Интерфейсы',
                'parent_id' => 1,
            ],
            [//14
                'title' => 'Дизайн сайтов',
                'parent_id' => 13,
            ],
            [//15
                'title' => 'Баннеры',
                'parent_id' => 13,
            ],
            [//16
                'title' => 'Логотипы',
                'parent_id' => 13,
            ],
            [//17
                'title' => 'UX дизайн',
                'parent_id' => 14,
            ],
            [//18
                'title' => 'Иконки и Пиксель-арт',
                'parent_id' => 13,
            ],
            [//19
                'title' => 'Back-end',
                'parent_id' => 1,
            ],
            [//20
                'title' => 'Проектирование БД',
                'parent_id' => 19,
            ],
            [//21
                'title' => 'Программирование',
                'parent_id' => 19,
            ],
            [//22
                'title' => 'Тестирование ПО',
                'parent_id' => 19,
            ],
            [//23
                'title' => 'Написание документации',
                'parent_id' => 19,
            ],
            [//24
                'title' => 'Разработка API',
                'parent_id' => 19,
            ],
            [//25
                'title' => 'Мобильная разработка',
                'parent_id' => 0,
            ],
            [//26
                'title' => 'Разработка дизайна',
                'parent_id' => 25,
            ],
            [//27
                'title' => 'UX дизайн',
                'parent_id' => 26,
            ],
            [//28
                'title' => 'UI дизайн',
                'parent_id' => 26,
            ],
            [//29
                'title' => 'UI дизайн',
                'parent_id' => 14,
            ],
            [//30
                'title' => 'Верстка электронного письма',
                'parent_id' => 3,
            ],
            [//31
                'title' => 'Настройка серверов',
                'parent_id' => 19,
            ],
            [//32
                'title' => 'Программирование',
                'parent_id' => 25,
            ],
        ];

        foreach($categoriesList as $key => $val) {
            $categoriesList[$key]['slug'] = str_slug($val['title'], '-');
        }

        DB::table('categories')->insert($categoriesList);
    }
}
