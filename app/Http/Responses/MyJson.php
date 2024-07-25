<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class MyJson implements Responsable
{
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }


    public function toResponse($request)
    {
        return response(json_encode($this->content))
            ->withHeaders(['Content-Type' => 'application/json']);
    }
}
