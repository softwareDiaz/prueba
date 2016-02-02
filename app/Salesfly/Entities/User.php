<?php
namespace Salesfly\Salesfly\Entities;

class User extends \Eloquent {

	protected $table = 'users';
    
    protected $fillable = ['name',
    'email','password','store_id','role_id','estado','image'];
}