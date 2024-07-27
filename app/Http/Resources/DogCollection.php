<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Dog as DogResource;

// php artisan make:resource DogCollection
class DogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 단순 API 리소스 컬렉션
        return [
            'data' => $this->collection,
            'links' => [
                'self' => route('dogs.index')
            ],
        ];

        // 연관관계를 포함하는 간단한 api
        return [
            'name' => $this->name,
            'breed' => $this->breed,
            'friends' => DogResource::collection($this->friend),
        ];

        // 조건에 따라 api 연관관계 불러들이기
        return [
            'name' => $this->name,
            'breed' => $this->breed,
            // 이미 관계를 가지고 있는 경우에만 연관관계를 추가
            'bones' => BoneResource::collection($this->whenLoaded('bones')),
            // URL 요청하는 경우에만 연관관계를 추가
            'bones' => $this->when(
                $request->get('include') == 'bones',
                BoneResource::collection($this->bones)
            ),
        ];
    }
}
