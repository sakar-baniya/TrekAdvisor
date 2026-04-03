<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class HotelOwnerAccessService
{
    public function authorize(User $user, Hotel $hotel): Hotel
    {
        if ((int) $hotel->owner_id !== (int) $user->id) {
            throw new AccessDeniedHttpException();
        }

        return $hotel;
    }
}
