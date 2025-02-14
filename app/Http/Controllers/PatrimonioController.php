<?php

namespace App\Http\Controllers;

use App\Models\Classificacao;
use App\Models\MovimentoPatrimonio;
use App\Models\Origem;
use App\Models\Predio;
use App\Models\Sala;
use App\Models\Servidor;
use App\Models\Setor;
use App\Models\Situacao;
use Illuminate\Http\Request;
use App\Models\Patrimonio;
use Illuminate\Support\Facades\Auth;

class PatrimonioController extends Controller
{
    public function index()
    {
        $patrimonios = Patrimonio::all();
        return view('patrimonio.index', compact('patrimonios'));
    }

    public function create()
    {
        $setores = Setor::all();
        $origens = Origem::all();
        $predios = Predio::all();
        $situacoes = Situacao::all();
        $classificacoes = Classificacao::all();
        $servidores = Servidor::all();
        return view('patrimonio.create', compact('setores', 'origens', 'predios', 'situacoes', 'servidores', 'classificacoes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->user_id == 1) {
            Patrimonio::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'classificacao_id' => $request->classificacao_id,
                'origem_id' => $request->origem_id,
                'situacao_id' => $request->situacao_id,
                'sala_id' => $request->sala_id,
                'tipo' => 'Patrimônio',
                'servidor_id' => $request->servidor_id
            ]);
        } else {
            Patrimonio::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'classificacao_id' => $request->classificacao_id,
                'origem_id' => $request->origem_id,
                'situacao_id' => $request->situacao_id,
                'sala_id' => $request->sala_id,
                'tipo' => 'Patrimônio',
                'servidor_id' => $request->servidor_id,
                'nota_fiscal' => $request->nota_fiscal
            ]);
        }
        return redirect(route('patrimonio.index'))->with('success', 'Patrimônio Cadastrado com Sucesso!');
    }

    public function edit($patrimonio_id)
    {
        $patrimonio = Patrimonio::find($patrimonio_id);
        $setores = Setor::all();
        $origens = Origem::all();
        $predios = Predio::all();
        $situacoes = Situacao::all();
        $classificacoes = Classificacao::all();
        $servidores = Servidor::all();
        return view('patrimonio.edit', compact('patrimonio', 'setores', 'origens', 'predios', 'situacoes', 'servidores', 'classificacoes'));
    }

    public function update(Request $request)
    {
        Patrimonio::find($request->patrimonio_id)->update($request->all());
        return redirect(route('patrimonio.index'))->with('success', 'Patrimonio Editado com Sucesso!');
    }

    public function delete($patrimonio_id)
    {
        $patrimonio = Patrimonio::find($patrimonio_id);
        $movimento = MovimentoPatrimonio::where('patrimonio_id', $patrimonio->id)->first();
        if ($movimento == null) {
            $patrimonio->delete();
            return redirect(route('patrimonio.index'))->with('success', 'Patrimonio Removido com Sucesso!');
        } else {
            return redirect(route('patrimonio.index'))->with('fail', 'Não é possivel remover, o patrimônio já foi movimentado.');
        }
    }

    public function getSalas(Request $request)
    {
        $predio_id = json_decode($request->predio_id);
        $salas = Sala::where('predio_id', $predio_id)->get();
        return response()->json($salas);
    }
}
