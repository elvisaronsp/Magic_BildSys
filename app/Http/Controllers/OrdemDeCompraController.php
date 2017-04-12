<?php

namespace App\Http\Controllers;

use App\DataTables\OrdemDeCompraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateOrdemDeCompraRequest;
use App\Http\Requests\UpdateOrdemDeCompraRequest;
use App\Models\Insumo;
use App\Models\Grupo;
use App\Models\Lembrete;
use App\Models\Planejamento;
use App\Models\PlanejamentoCompra;
use App\Repositories\CodeRepository;
use function foo\func;
use Illuminate\Pagination\Paginator;
use App\Models\Obra;
use App\Models\OrdemDeCompra;
use App\Repositories\OrdemDeCompraRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class OrdemDeCompraController extends AppBaseController
{
    /** @var  OrdemDeCompraRepository */
    private $ordemDeCompraRepository;

    public function __construct(OrdemDeCompraRepository $ordemDeCompraRepo)
    {
        $this->ordemDeCompraRepository = $ordemDeCompraRepo;
    }

    /**
     * Display a listing of the OrdemDeCompra.
     *
     * @param OrdemDeCompraDataTable $ordemDeCompraDataTable
     * @return Response
     */
    public function index(OrdemDeCompraDataTable $ordemDeCompraDataTable)
    {
        return $ordemDeCompraDataTable->render('ordem_de_compras.index');
    }

    /**
     * Show the form for creating a new OrdemDeCompra.
     *
     * @return Response
     */
    public function create()
    {
        return view('ordem_de_compras.create');
    }

    /**
     * Store a newly created OrdemDeCompra in storage.
     *
     * @param CreateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function store(CreateOrdemDeCompraRequest $request)
    {
        $input = $request->all();

        $ordemDeCompra = $this->ordemDeCompraRepository->create($input);

        Flash::success('Ordem De Compra '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('ordemDeCompras.index'));
    }

    /**
     * Display the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        return view('ordem_de_compras.show')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Show the form for editing the specified OrdemDeCompra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        return view('ordem_de_compras.edit')->with('ordemDeCompra', $ordemDeCompra);
    }

    /**
     * Update the specified OrdemDeCompra in storage.
     *
     * @param  int              $id
     * @param UpdateOrdemDeCompraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrdemDeCompraRequest $request)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        $ordemDeCompra = $this->ordemDeCompraRepository->update($request->all(), $id);

        Flash::success('Ordem De Compra '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('ordemDeCompras.index'));
    }

    /**
     * Remove the specified OrdemDeCompra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ordemDeCompra = $this->ordemDeCompraRepository->findWithoutFail($id);

        if (empty($ordemDeCompra)) {
            Flash::error('Ordem De Compra '.trans('common.not-found'));

            return redirect(route('ordemDeCompras.index'));
        }

        $this->ordemDeCompraRepository->delete($id);

        Flash::success('Ordem De Compra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('ordemDeCompras.index'));
    }

    public function compras()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('ordem_de_compras.compras', compact('obras'));
    }


    public function insumos(Lembrete $lembrete){
        return view('ordem_de_compras.insumos', compact('lembrete'));
    }

    public function insumosFilters(){
        $filters = OrdemDeCompra::$filters_insumos;
        return response()->json($filters);
    }

    public function insumosJson(Request $request, Lembrete $lembrete){
        //Query para utilização dos filtros
        //DB::raw(Auth::user()->admin ? '1 as admin' : '0 as admin'),
        $insumo_query = Insumo::query();
        $insumos = $insumo_query->join('insumo_servico', 'insumo_servico.insumos_id','=','insumos.id')
            ->join('servicos','servicos.id','=','insumo_servico.servicos_id')
            ->select([
                'insumos.id',
                'insumos.codigo as insumo_cod',
                'insumos.unidade_sigla',
                'insumos.nome as descricao',
                'servicos.id as servico_id',
                'servicos.nome as servico',
                'servicos.codigo as cod_servico',
                'servicos.grupo_id',
//                DB::raw('(SELECT TOP 1 planejamento_compras.id FROM planejamento_compras
//                 JOIN servicos ON servicos.id = planejamento_compras.servico_id
//                 JOIN insumos ON insumos.codigo = planejamento_compras.codigo_insumo
//                 WHERE planejamento_id ='.$planejamento->id. ')
//                 as teste')
            ]);

        if(isset($request->orderkey)){
            $insumos->orderBy($request->orderkey, $request->order);
        }

        //Aplica filtro do Jhonatan

        $insumos = CodeRepository::filter($insumos, $request->all());

        return response()->json($insumos->paginate(10), 200);
    }

    public function insumosAdd(Request $request, Lembrete $lembrete)
    {
        try{
            $planejamento_compras = new PlanejamentoCompra();
//            $planejamento_compras->planejamento_id = $planejamento->id;
            $planejamento_compras->grupo_id = $request->grupo_id;
            $planejamento_compras->servico_id = $request->servico_id;
            $planejamento_compras->codigo_insumo = $request->insumo_cod;
            $planejamento_compras->save();
            Flash::success('Insumo adicionado com sucesso');
            return response()->json('{response: "sucesso"}');
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Tela que traz os insumos de uma tarefa especifica de uma obra.
     *
     * @param  Request $request ->planejamento_id
     *
     * @return Render View
     */
    public function obrasInsumos(Request $request, Lembrete $lembrete)
    {
        return view('ordem_de_compras.obras_insumos', compact('lembrete'));
    }

    /**
     * Método que retorna a lista de filtros aplicaveis a obras insumos.
     *
     *
     * @return Json
     */
    public function obrasInsumosFilters(){
        $filters = OrdemDeCompra::$filters_obras_insumos;
        return response()->json($filters);
    }

    /**
     * Método que retorna a lista de insumos de uma tarefa como json.
     *
     * @param  Request $request ->planejamento_id
     *
     * @filters obrasInsumosFilters()
     *
     * @return Json
     */
    public function obrasInsumosJson(Request $request, Planejamento $planejamento)
    {
        //Pega a tarefa(planejamento)
        $planejamento_compras = DB::table('planejamento_compras')
            ->select('insumo_id')
            ->where('planejamento_compras.planejamento_id',$planejamento->id)
            ->get();

        //Criar arrays dos insumos do planejamento de compras por servicos grupos ou codigo do insumo
        $servicos = array();
        $grupos = array();
        $codigo = array();
        //Popula arrays
        foreach ($planejamento_compras as $planejamento)
        {
            $flag_cod = false;
            if(isset($planejamento->codigo_insumo) && !empty($planejamento->codigo_insumo)){
                $flag_cod = true;
                $codigo[] = $planejamento->codigo_insumo;
            }

            $flag_servico = false;
            if(isset($planejamento->servico_id) && !empty($planejamento->servico_id) && !$flag_cod){
                $flag_servico = true;
                $servicos[] = $planejamento->servico_id;
            }

            if(isset($planejamento->grupo_id)&& !empty($planejamento->grupo_id) && !$flag_cod && !$flag_servico){
                $grupos[] = $planejamento->grupo_id;
            }
        }

        //Query para utilização dos filtros
        $insumo_query = Insumo::query();

        $insumos = $insumo_query->join('orcamentos', 'insumos.id', '=', 'orcamentos.insumo_id')
            ->join('planejamento_compras','planejamento_compras.insumo_id','=','insumos.id')
            ->select([
                'insumos.id',
                'insumos.nome',
                'insumos.unidade_sigla',
                'insumos.codigo',
                'orcamentos.grupo_id',
                'orcamentos.servico_id',
                'orcamentos.qtd_total',
                'orcamentos.preco_total'
            ])
            ->where('insumos.insumo_grupo_id',$insumo_grupo->id)
            ->where('planejamento_compras.planejamento_id', $planejamento->id);

        //Testa a ordenação
        if(isset($request->orderkey)) {
            //Traz os resultados filtrados pelos arrays criados
            $insumos = $insumo_query->join('orcamentos', 'insumos.id', '=', 'orcamentos.insumo_id')
                ->where(function ($query) use($codigo, $grupos, $servicos) {
                    $query->whereIn('orcamentos.codigo_insumo', $codigo, 'or')
                        ->whereIn('orcamentos.servico_id', $servicos, 'or')
                        ->whereIn('orcamentos.grupo_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo1_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo2_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo3_id', $grupos, 'or');
                })
                ->select([
                    'insumos.id',
                    'insumos.nome',
                    'insumos.unidade_sigla',
                    'insumos.codigo',
                    'orcamentos.grupo_id',
                    'orcamentos.servico_id',
                    'orcamentos.qtd_total',
                    'orcamentos.preco_total'
                ])->orderBy($request->orderkey, $request->order);
        }else{
            //Traz os resultados filtrados pelos arrays criados
            $insumos = $insumo_query->join('orcamentos', 'insumos.id', '=', 'orcamentos.insumo_id')
                ->where(function ($query) use($codigo, $grupos, $servicos) {
                    $query->whereIn('orcamentos.codigo_insumo', $codigo, 'or')
                        ->whereIn('orcamentos.servico_id', $servicos, 'or')
                        ->whereIn('orcamentos.grupo_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo1_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo2_id', $grupos, 'or')
                        ->whereIn('orcamentos.subgrupo3_id', $grupos, 'or');
                })
                ->select([
                    'insumos.id',
                    'insumos.nome',
                    'insumos.unidade_sigla',
                    'insumos.codigo',
                    'orcamentos.grupo_id',
                    'orcamentos.servico_id',
                    'orcamentos.qtd_total',
                    'orcamentos.preco_total'
                ]);
        }
        //Aplica filtro do Jhonatan
        $insumos = CodeRepository::filter($insumos, $request->all());

        return response()->json($insumos->paginate(10), 200);
    }

    //Metodo de paginacao manual caso necessario
    protected function paginate($items, $perPage = 12){
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage, true);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($items),
            $perPage
        );
    }

    public function filterJsonOrdemCompra(){
        $filters = OrdemDeCompra::$filters;

        return response()->json($filters);
    }
}

