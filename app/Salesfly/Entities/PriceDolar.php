<?php
namespace Salesfly\Salesfly\Entities;

class PriceDolar extends \Eloquent {

	protected $table = 'priceDolars';
    
    protected $fillable = ['fecha','fecha2','preDolar','mes'];

}