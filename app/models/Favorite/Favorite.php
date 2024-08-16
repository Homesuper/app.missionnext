<?php


namespace MissionNext\Models\Favorite;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {

    protected $table = "favorite";

    protected $fillable = ["app_id", "user_id", "target_type", "target_id"];

} 