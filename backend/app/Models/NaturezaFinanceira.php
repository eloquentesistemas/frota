<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NaturezaFinanceira extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["nome","descritivo"];

    protected $searchableFields = ["*"];

    
    
    public function Conta()
    {
        return $this->hasMany(Conta::class);
    }


}
