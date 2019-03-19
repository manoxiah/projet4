<?php

namespace App\Tests\Booking;

use App\Controller\BookingController;
use PHPUnit\Framework\TestCase;

class InstanceOfTest extends TestCase
{
    public function testFailure()
    {
        $this->assertInstanceOf(BookingController::class, new BookingController);
    }
}