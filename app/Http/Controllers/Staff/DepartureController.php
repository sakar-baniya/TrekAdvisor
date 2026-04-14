<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Admin\DepartureController as AdminDepartureController;

/**
 * Yo DepartureController controller le departure controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class DepartureController extends AdminDepartureController
{
    // Pass A compatibility shell: staff uses same departure workflows as admin.
}

