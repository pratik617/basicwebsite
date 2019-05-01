<?php

namespace App\Model\OAuth;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    public $table = "oauth_access_tokens";
    public $primaryKey = "id";
}
