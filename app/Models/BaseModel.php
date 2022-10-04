<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 18/08/2022
 * Time: 4:28 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $perPage = 15;
}