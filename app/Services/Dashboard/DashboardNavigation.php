<?php

namespace App\Services\Dashboard;

/**
 * DashboardNavigation Service
 * 
 * Centralized navigation configuration for all dashboard roles
 * No more hardcoded navigation in views!
 */
class DashboardNavigation
{
    public static function getConfig(string $role): array
    {
        $configs = [
            'admin' => self::adminNavigation(),
            'staff' => self::staffNavigation(),
            'hotel_owner' => self::hotelOwnerNavigation(),
            'customer' => self::customerNavigation(),
        ];

        return $configs[$role] ?? self::defaultNavigation();
    }

    private static function adminNavigation(): array
    {
        return [
            'title' => 'TrekAdvisor Admin',
            'subtitle' => '',
            'panel_label' => 'Admin panel',
            'home_route' => 'admin.dashboard',
            'navigation' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-chart-line',
                    'route' => 'admin.dashboard',
                ],
                [
                    'label' => 'Trek Management',
                    'icon' => 'fa-mountain-sun',
                    'children' => [
                        ['label' => 'All Treks', 'route' => 'admin.treks.index'],
                        ['label' => 'Add New Trek', 'route' => 'admin.treks.create'],
                        ['label' => 'Departures', 'route' => 'admin.departures.index'],
                        ['label' => 'Trek Bookings', 'route' => 'admin.trek-bookings.index'],
                    ],
                ],
                [
                    'label' => 'Hotel Management',
                    'icon' => 'fa-hotel',
                    'children' => [
                        ['label' => 'All Hotels', 'route' => 'admin.hotels.index'],
                    ],
                ],
                [
                    'label' => 'User Management',
                    'icon' => 'fa-users',
                    'children' => [
                        ['label' => 'All Users', 'route' => 'admin.users.index'],
                        ['label' => 'Add Staff', 'route' => 'admin.users.create-staff'],
                    ],
                ],
                [
                    'label' => 'Payments',
                    'icon' => 'fa-credit-card',
                    'children' => [
                        ['label' => 'All Payments', 'route' => 'admin.payments.index'],
                    ],
                ],
                [
                    'label' => 'Reviews',
                    'icon' => 'fa-star',
                    'children' => [
                        ['label' => 'All Reviews', 'route' => 'admin.reviews.index'],
                        ['label' => 'Flagged Reviews', 'route' => 'admin.reviews.flagged'],
                    ],
                ],
            ],
        ];
    }

    private static function staffNavigation(): array
    {
        return [
            'title' => 'TrekAdvisor Staff',
            'subtitle' => 'Support desk',
            'panel_label' => 'Staff dashboard',
            'home_route' => 'staff.dashboard',
            'navigation' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-headset',
                    'route' => 'staff.dashboard',
                ],
                [
                    'label' => 'Trek Bookings',
                    'icon' => 'fa-mountain-sun',
                    'route' => 'staff.trek-bookings.index',
                ],
            ],
        ];
    }

    private static function hotelOwnerNavigation(): array
    {
        return [
            'title' => 'TrekAdvisor Hotel Hub',
            'subtitle' => 'Partner console',
            'panel_label' => 'Hotel owner dashboard',
            'home_route' => 'hotel_owner.dashboard',
            'navigation' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-hotel',
                    'children' => [
                        ['label' => 'Overview', 'route' => 'hotel_owner.dashboard'],
                        ['label' => 'My Hotels', 'route' => 'hotel_owner.hotels.index'],
                        ['label' => 'Add Hotel', 'route' => 'hotel_owner.hotels.create'],
                    ],
                ],
            ],
        ];
    }

    private static function customerNavigation(): array
    {
        return [
            'title' => 'My Dashboard',
            'subtitle' => 'My bookings',
            'panel_label' => 'Customer dashboard',
            'home_route' => 'customer.dashboard',
            'navigation' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-home',
                    'route' => 'customer.dashboard',
                ],
            ],
        ];
    }

    private static function defaultNavigation(): array
    {
        return [
            'title' => 'TrekAdvisor Dashboard',
            'subtitle' => 'Workspace',
            'panel_label' => 'Dashboard',
            'home_route' => 'dashboard',
            'navigation' => [],
        ];
    }

    /**
     * Check if a route matches a pattern
     * Handles wildcard route names like 'admin.treks.*'
     */
    public static function isRouteActive(string $routePattern): bool
    {
        if (str_contains($routePattern, '*')) {
            return request()->routeIs($routePattern);
        }
        return request()->routeIs($routePattern);
    }

    /**
     * Get normalized navigation with active states
     */
    public static function getNormalizedNavigation(array $navigation): array
    {
        return array_map(function ($item) {
            if (isset($item['children'])) {
                $item['children'] = array_map(function ($child) {
                    $child['active'] = self::isRouteActive($child['route']);
                    return $child;
                }, $item['children']);

                // Parent is active if any child is active
                $item['active'] = collect($item['children'])->contains(fn ($c) => $c['active']);
            } else {
                $item['active'] = self::isRouteActive($item['route']);
            }

            return $item;
        }, $navigation);
    }
}

