<?php

namespace App\Http\Resources\Api\V1\Auth;

use App\Dtos\Api\V1\Auth\LoggedInUserDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoggedInResource extends JsonResource
{
    /** @var LoggedInUserDto */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource->getUser();

        return [
            'data' => [
                'access_token' => $this->resource->getToken(),
                'token_type' => 'Bearer',
            ],
            'meta' => [
                'user' => [
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
            ],
        ];
    }
}
