<?php

namespace App\Tests\Booking;

use App\Controller\BookingController;
use PHPUnit\Framework\TestCase;

class FalseTest extends TestCase
{
    public function testFailure()
    {
        $BookingController = new BookingController();
        $result = $BookingController->delete_to_basket(1);
        $this->assertFalse($result);
    }
}



/*
namespace App\Tests\Booking;

use App\Controller\BookingController;
use PHPUnit\Framework\TestCase;

class BookingControllerTestDeleteToBasket extends TestCase
{
    public function TestDeleteToBasket()
    {
        $BookingController = new BookingController();
        $result = $BookingController->delete_to_basket(1);

        $this->assertEquals(1, $result);
    }

}

*/