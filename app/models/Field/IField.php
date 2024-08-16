<?php


namespace MissionNext\Models\Field;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface  IField  {

    /**
     * @return HasMany
     */
  public function choices();

  public function dataModels();

    /**
     * @return BelongsToMany
     */
  public function languages();

} 