<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Superintendency;
use Carbon\Carbon;

class SuperintendencyController extends Controller
{
    /**
     * Get rentability
     *
     * @param  int  $year
     * @param int $month
     * @return string json
     */
    public function rentability($year, $month)
    {
        $superintendency = new Superintendency;
        $date = Carbon::createFromDate($year, $month);
        $data = $superintendency->getRentability($date);
        return response()->json($data);
    }
}
