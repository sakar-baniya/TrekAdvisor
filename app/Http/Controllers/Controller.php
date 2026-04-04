<?php

namespace App\Http\Controllers;

use App\Services\Authorization\AuthorizationHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller with authorization helpers built-in
 * 
 * All controllers should extend this for consistent authorization
 * 
 * Usage:
 *   class TrekController extends Controller {
 *       public function store(CreateTrekRequest $request) {
 *           $this->authorizeCreate(Trek::class);
 *           // Create trek...
 *       }
 *   }
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Check if user can create a resource
     * Usage: $this->authorizeCreate(Trek::class)
     */
    protected function authorizeCreate(string $modelClass): void
    {
        $this->authorize('create', $modelClass);
    }

    /**
     * Check if user can view a resource
     * Usage: $this->authorizeView($trek)
     */
    protected function authorizeView($resource): void
    {
        $this->authorize('view', $resource);
    }

    /**
     * Check if user can update a resource
     * Usage: $this->authorizeUpdate($trek)
     */
    protected function authorizeUpdate($resource): void
    {
        $this->authorize('update', $resource);
    }

    /**
     * Check if user can delete a resource
     * Usage: $this->authorizeDelete($trek)
     */
    protected function authorizeDelete($resource): void
    {
        $this->authorize('delete', $resource);
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        return AuthorizationHelper::isAdmin(auth()->user());
    }

    /**
     * Check if current user is staff
     */
    protected function isStaff(): bool
    {
        return AuthorizationHelper::isStaff(auth()->user());
    }

    /**
     * Check if current user can manage operations (admin/staff)
     */
    protected function isOperational(): bool
    {
        return AuthorizationHelper::isOperational(auth()->user());
    }

    /**
     * Check if current user is customer
     */
    protected function isCustomer(): bool
    {
        return AuthorizationHelper::isCustomer(auth()->user());
    }

    /**
     * Check if current user is hotel owner
     */
    protected function isHotelOwner(): bool
    {
        return AuthorizationHelper::isHotelOwner(auth()->user());
    }

    /**
     * Get current authenticated user
     */
    protected function getUser()
    {
        return auth()->user();
    }
}

