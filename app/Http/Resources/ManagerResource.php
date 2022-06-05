<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'fullName' => ucwords($this->name),
            'username' => $this->name,
            'avatar' => $this->avatar ?? '',
            'email' => $this->email,
            'status' => match ($this->status) {
                INACTIVE => 'inactive',
                ACTIVE => 'active',
                PENDING => 'pending',
            },
        ];

        // Fake to login
        $result['role'] = 'admin';
        $result['ability'][] = [
            'action' => 'manage',
            'subject' => 'all'
        ];

        return $result;
    }
}
