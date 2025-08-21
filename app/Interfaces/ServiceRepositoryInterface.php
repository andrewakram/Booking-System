<?php

namespace App\Interfaces;

interface ServiceRepositoryInterface{

    public function showPublishedServices();

    public function addMultiService(array $data);

}
