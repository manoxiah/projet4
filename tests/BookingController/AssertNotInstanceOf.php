<?php

namespace App\Tests\Booking;

use App\Controller\BookingController;
use App\Controller\TicketController;
use PHPUnit\Framework\TestCase;

class InstanceOfTest extends TestCase
{
    public function testFailureNot()
    {
        $this->assertNotInstanceOf(TicketController::class, new BookingController);
    }
}