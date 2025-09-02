<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faturamento extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["pessoa_motorista_id","veiculo_id","data_embarque","origem_cidade_id","origem_local","destino_cidade_id","destino_local","pessoa_cliente_id","danfe","peso","valor_acerto_motorista","valor_total","DMT","carga","descritivo"];

    protected $searchableFields = [
        "faturamentos.pessoa_motorista_id",
        "faturamentos.veiculo_id",
        "faturamentos.data_embarque",
        "faturamentos.origem_cidade_id",
        "faturamentos.origem_local",
        "faturamentos.destino_cidade_id",
        "faturamentos.destino_local",
        "faturamentos.pessoa_cliente_id",
        "faturamentos.danfe",
        "faturamentos.peso",
        "faturamentos.valor_acerto_motorista",
        "faturamentos.valor_total",
        "faturamentos.DMT",
        "faturamentos.carga",
        "faturamentos.descritivo"];


   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
