<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: CarModel
 * 
 * Automatically generated via CLI.
 */
class CarModel extends Model {
    protected $table = 'cars';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}