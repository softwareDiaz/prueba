<?php

namespace Salesfly\Salesfly\Managers;
class UserManager extends BaseManager {

    public function getRules()
    {
        $rules = ['name'=> 'required',
    'email'=> 'required','password'=> '','store_id'=> 'required','role_id'=> 'required','estado'=> 'required','image'=> 'required'];
        return $rules;
    }
} 
