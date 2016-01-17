<?php
namespace Salesfly\Salesfly\Entities;
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 08/08/15
 * Time: 03:00 PM
 */

class DetPres extends \Eloquent {

    protected $table = 'detPres';

    protected $fillable = ['variant_id','presentation_id','suppPri','markup','price','cambioDolar','suppPriDol','markupCant','dscto',
        'dsctoCant', 'pvp', 'fecIniDscto', 'fecFinDscto', 'dsctoRange', 'dsctoCantRange', 'pvpRange', 'activateDsctoRange'];

    public function variant()
      {
        return $this->belongsTo('\Salesfly\Salesfly\Entities\Variant');
      }
      public function presentation()
      {
        return $this->belongsTo('\Salesfly\Salesfly\Entities\Presentation');
      }
}
