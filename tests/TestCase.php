<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property string imageName1
 * @property string imageName2
 * @property string imageName3
 * @property string image1
 * @property string image2
 * @property string image3
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;
}
