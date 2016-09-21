<?php

class AddressStorageService
{
    /**
     * Run address storage functionality
     */
    public function run()
    {
        $request = new Request();
        new AddressController($request);
    }

    final public function __construct(){}

    final public function __destruct(){}

    final public function __clone(){}
}