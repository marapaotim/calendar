<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class EventController extends Controller
{
 	public function index() {
 		$firstRecord = Event::select(\DB::raw("DATE_FORMAT(event_date, '%Y-%m') AS date"))->first();
 		$lastRecord = Event::select(\DB::raw("DATE_FORMAT(event_date, '%Y-%m') AS date"))
 			->latest("id")->first();
 		$data = [];

		$period = $this->getDateRange($firstRecord->date . "-01", Carbon::parse($lastRecord->date)
			->endOfMonth()->toDateString());

		foreach ($period as $day) {
			$dateToFilter = Carbon::parse($day)->format("d D Y m");
			$dateToDisplay = Carbon::parse($day)->format("d D");
			$eventName = Event::select("event_name")
					->whereRaw("DATE_FORMAT(event_date, '%d %a %Y %m') = ?", [$dateToFilter])
					->first();

			$data[Carbon::parse($day)->format("Y F")][] = [
				"date" => $dateToDisplay,
				"event" => $eventName ? $eventName->event_name : "" 
			];
		}

		return $data;
 	}

	public function insert(Request $request) {
		$period = $this->getDateRange($request->from, $request->to);

	    foreach ($period as $day) {
	    	if (in_array(Carbon::parse($day)->format("D"), $request->days)) {
		        $data[] = [
		        	"event_date" => $day,
		        	"event_name" => $request->event
		        ];
	    	}
	    }

	    Event::truncate();
        return Event::insert($data);
    }

    private function getDateRange($from, $to) {
    	$from = Carbon::parse($from);
    	$to = Carbon::parse($to)->addDay();

    	$step = \Carbon\CarbonInterval::day();
    	$period = new \DatePeriod($from, $step, $to);

    	return $period;
    }
}
