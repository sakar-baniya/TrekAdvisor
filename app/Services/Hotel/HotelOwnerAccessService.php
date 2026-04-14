<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * Yo HotelOwnerAccessService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class HotelOwnerAccessService
{
    /**
     * Yo method le authorize ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function authorize(User $user, Hotel $hotel): Hotel
    {
        if ((int) $hotel->owner_id !== (int) $user->id) {
            throw new AccessDeniedHttpException();
        }

        return $hotel;
    }
}





