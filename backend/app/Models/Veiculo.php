<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Veiculo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["nome","placa","cor","vencimento_documento","ativo","descritivo"];

    protected $searchableFields = ["*"];

    
    
    public function AbastecimentoVeiculo()
    {
        return $this->hasMany(AbastecimentoVeiculo::class);
    }
    public function Faturamento()
    {
        return $this->hasMany(Faturamento::class);
    }
    public function PessoaVeiculo()
    {
        return $this->hasMany(PessoaVeiculo::class);
    }
    public function PneusVeiculo()
    {
        return $this->hasMany(PneusVeiculo::class);
    }


}
