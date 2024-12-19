<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Example;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => '123',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet obcaecati at illum dolor perspiciatis magni voluptates, rerum consectetur a mollitia consequuntur nulla quod quam neque impedit similique perferendis excepturi quidem.',
                'name' => 'example name',
                'tag' => 'technology,career,education',
                'user_id' => null,
            ],
            [
                'code' => '124',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet obcaecati at illum dolor perspiciatis magni voluptates, rerum consectetur a mollitia consequuntur nulla quod quam neque impedit similique perferendis excepturi quidem.',
                'name' => 'example name 1',
                'tag' => 'technology,career,education',
                'user_id' => null,
            ],
        ];

        foreach ($data as $key => $item) {
            Example::create($item);
        }
    }
}
