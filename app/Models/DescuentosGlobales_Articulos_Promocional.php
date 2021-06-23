<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescuentosGlobales_Articulos_Promocional extends Model
{
    use HasFactory;
    protected $table = 'L@Red_Software_DescuentosGlobales_Articulos_Promocional';
    Protected $primaryKey = ["itemlookupcode", "StoreID"];

}
