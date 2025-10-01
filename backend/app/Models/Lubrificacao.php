<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lubrificacao extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["lubrificacaos.data","lubrificacaos.pessoa_id","lubrificacaos.veiculo_id","lubrificacaos.servico","lubrificacaos.km"];

    protected $searchableFields = ["lubrificacaos.data","lubrificacaos.pessoa_id","lubrificacaos.veiculo_id","lubrificacaos.servico","lubrificacaos.km"];


   public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
