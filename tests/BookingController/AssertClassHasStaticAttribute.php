<?php

namespace App\Tests\Booking;

use App\Controller\BookingController;
use PHPUnit\Framework\TestCase;

class ClassHasStaticAttributeTest extends TestCase
{
    public function testFailure()
    {
        $BookingController = new BookingController();
        $this->assertClassHasStaticAttribute('attribute', BookingController::class);
    }
}