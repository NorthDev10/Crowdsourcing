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
                'title' => 'Продвижение сайтов',
                'parent_id' => 0,
            ],
            [//4
                'title' => 'Веб-дизайн и Интерфейсы',
                'parent_id' => 1,
            ],
            [//5
                'title' => 'Back-end',
                'parent_id' => 1,
            ],
            [//6
                'title' => 'Мобильная разработка',
                'parent_id' => 0,
            ],
            [//7
                'title' => 'Разработка дизайна',
                'parent_id' => 6,
            ],
            [//8
                'title' => 'Программирование',
                'parent_id' => 6,
            ],
            [//9
                'title' => 'Контекстная реклама',
                'parent_id' => 3,
            ],
            [//10
                'title' => 'Поисковые системы (SEO)',
                'parent_id' => 3,
            ],
            [//11
                'title' => 'Социальные сети (SMM и SMO)',
                'parent_id' => 3,
            ],
        ];

        foreach($categoriesList as $key => $val) {
            $categoriesList[$key]['slug'] = str_slug($val['title'], '-');
        }

        DB::table('categories')->insert($categoriesList);
    }
}
