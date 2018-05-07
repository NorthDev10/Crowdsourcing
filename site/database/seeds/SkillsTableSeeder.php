<?php

use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->insert([
            [
                'name' => 'php',
            ],
            [
                'name' => 'Laravel',
            ],
            [
                'name' => 'VueJS',
            ],
            [
                'name' => 'Angular',
            ],
            [
                'name' => 'ReactJS',
            ],
            [
                'name' => 'MySQL',
            ],
            [
                'name' => 'Linux',
            ],
            [
                'name' => 'HTML5',
            ],
            [
                'name' => 'CSS',
            ],
            [
                'name' => 'JS',
            ],
            [
                'name' => 'Zend Framework',
            ],
            [
                'name' => 'Yii',
            ],
            [
                'name' => 'Redis',
            ],
            [
                'name' => 'MongoDB',
            ],
            [
                'name' => 'Node.js',
            ],
            [
                'name' => 'NPM',
            ],
            [
                'name' => 'Yarn',
            ],
            [
                'name' => 'Git',
            ],
            [
                'name' => 'JQuery',
            ],
            [
                'name' => 'Bootstrap',
            ],
            [
                'name' => 'Docker',
            ],
            [
                'name' => 'Webpack',
            ],
        ]);
    }
}
