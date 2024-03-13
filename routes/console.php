<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->daily();
