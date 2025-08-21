<?php

namespace App\Interfaces;

interface BookingRepositoryInterface{

    public function getAvailability(array $data);

    public function book(array $data);

}
