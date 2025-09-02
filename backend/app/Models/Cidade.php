<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cidade extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["codigo","nome","uf"];

    protected $searchableFields = ["*"];

    
    
    public function Pessoa()
    {
        return $this->hasMany(Pessoa::class);
    }


}
