<?php

namespace Http\Controllers;

use App\Http\Controllers\GroupController;
use Tests\TestCase;


class GroupControllerTest extends TestCase
{

    public function testIndex()
    {
        $groupController = new GroupController();
        $response = $groupController->index();
       dd($response);

    }
}
