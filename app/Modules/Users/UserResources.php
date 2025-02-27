<?php

namespace App\Modules\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'rule' => $this->rules,
            'team' => $this->section->title ?? null ,
            'workspace' => $this->workspace->name ?? null ,
            'avatar' => config('app.url').'/images/'.$this->avatar,
        ];
    }
}
