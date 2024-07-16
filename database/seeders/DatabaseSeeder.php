<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(ContactsTableSeeder::class);
        // Contact::factory()->count(20)->create();

        // 모델 팩토리 사용하기
/*        $post = Post::factory()->create([
            'title' => 'My greatest post ever'
        ]);

        // 조금 더 복잡한 형태의 팩토리 사용
        User::factory()->count(20)->create()->each(function ($u) use ($post) {
            $post->comments()->save(Comment::factory()->make(
                [
                    'user_id' => $u->id,
                ]
            ));
        });*/

        //state 메서드를 호출하는 커스텀 메서드 정의하기
        $vip = Contact::factory()->vip()->create();
        $vips = Contact::factory()->count(3)->vip()->create();


    }
}
