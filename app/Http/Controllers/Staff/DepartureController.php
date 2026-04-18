<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Admin\DepartureController as AdminDepartureController;

/**
 * Staff Departure Controller: Staff le trek ka dates manage garne thau.
 *
 * Note: Staff le Admin ko DepartureController ko logic nai reuse garcha.
 */
class DepartureController extends AdminDepartureController
{
    // Pass A compatibility shell: staff uses same departure workflows as admin.
}

