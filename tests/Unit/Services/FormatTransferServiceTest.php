<?php

use App\Services\FormatTransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->formatTransferService = $this->app->make(FormatTransferService::class);
});

it('will return empty array, if not pass the tag json string', function () {
    expect($this->formatTransferService->tagsJsonToTagIdsArray())
        ->toBeEmpty();
});
