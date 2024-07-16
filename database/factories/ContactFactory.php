<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{

    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'company_id' => function () {
                return Company::factory()->create()->id;
            },
            'company_name' => function ($contact) {
                // 바로 위에서 생성한 'company_id' 속성값을 사용한다.
                return Company::find($contact['company_id'])->name;
            },
        ];
    }

    //state 메서드를 호출하는 커스텀 메서드 정의하기
    public function vip()
    {
        return $this->state(function (array $attributes) {
            return [
                'vip' => true,
            ];
        });
    }

}
