<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagamento extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["data_ocorrido","valor","parcela","descritivo","conta_id"];

    protected $searchableFields = ["*"];

    
   public function Conta()
    {
        return $this->belongsTo(Conta::class);
    }
    


}
