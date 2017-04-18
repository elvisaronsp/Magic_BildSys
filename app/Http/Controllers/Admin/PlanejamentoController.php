<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PlanejamentoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreatePlanejamentoRequest;
use App\Http\Requests\Admin\UpdatePlanejamentoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\PlanejamentoCompra;
use App\Models\Planilha;
use App\Models\Servico;
use App\Repositories\Admin\PlanejamentoRepository;
use App\Repositories\Admin\SpreadsheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

class PlanejamentoController extends AppBaseController
{
    /** @var  PlanejamentoRepository */
    private $planejamentoRepository;

    public function __construct(PlanejamentoRepository $planejamentoRepo)
    {
        $this->planejamentoRepository = $planejamentoRepo;
    }

    /**
     * Display a listing of the Planejamento.
     *
     * @param PlanejamentoDataTable $planejamentoDataTable
     * @return Response
     */
    public function index(PlanejamentoDataTable $planejamentoDataTable)
    {
        return $planejamentoDataTable->render('admin.planejamentos.index');
    }

    /**
     * Show the form for creating a new Planejamento.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.planejamentos.create');
    }

    /**
     * Store a newly created Planejamento in storage.
     *
     * @param CreatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePlanejamentoRequest $request)
    {
        $input = $request->all();

        $planejamento = $this->planejamentoRepository->create($input);

        Flash::success('Planejamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.planejamentos.index'));
    }

    /**
     * Display the specified Planejamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $planejamento = $this->planejamentoRepository->findWithoutFail($id);

        if (empty($planejamento)) {
            Flash::error('Planejamento '.trans('common.not-found'));

            return redirect(route('admin.planejamentos.index'));
        }

        return view('admin.planejamentos.show')->with('planejamento', $planejamento);
    }

    /**
     * Show the form for editing the specified Planejamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $obras = Obra::pluck('nome','id')->toArray();
        $grupos = Grupo::whereNull('grupo_id')->pluck('nome','id')->toArray();
        $planejamento = $this->planejamentoRepository->findWithoutFail($id);

        if (empty($planejamento)) {
            Flash::error('Planejamento '.trans('common.not-found'));

            return redirect(route('admin.planejamentos.index'));
        }


        return view('admin.planejamentos.edit', compact('planejamento','obras','grupos'));
    }

    public function getGrupos($id){
        $grupo = Grupo::where('grupo_id', $id)
            ->pluck('nome','id')->toArray();
        return $grupo;
    }
    public function getServicos($id){
        $servico = Servico::where('grupo_id', $id)
            ->pluck('nome', 'id')->toArray();
        return $servico;
    }

    public function planejamentoCompras(Request $request){
        $insumosOrcados = null;
        if($request->servico_id){
            $insumosOrcados = Orcamento::where('servico_id', $request->servico_id)
                ->where('subgrupo3_id', $request->subgrupo3_id)
                ->where('subgrupo2_id', $request->subgrupo2_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('grupo_id', $request->grupo_id)->get();
        }
        if($request->subgrupo3_id){
            $insumosOrcados = Orcamento::where('subgrupo3_id', $request->subgrupo3_id)
                ->where('subgrupo2_id', $request->subgrupo2_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('grupo_id', $request->grupo_id)->get();
        }
        if($request->subgrupo2_id){
            $insumosOrcados = Orcamento::where('subgrupo2_id', $request->subgrupo2_id)
                ->where('subgrupo1_id', $request->subgrupo1_id)
                ->where('grupo_id', $request->grupo_id)->get();
        }
        if($request->subgrupo1_id){
            $insumosOrcados = Orcamento::where('subgrupo1_id', $request->subgrupo1_id)
                ->where('grupo_id', $request->grupo_id)->get();
        }
        if($request->grupo_id){
            $insumosOrcados = Orcamento::where('grupo_id', $request->grupo_id)->get();
        }

        if($insumosOrcados) {
            foreach ($insumosOrcados as $insumosOrcado) {
                $planejamentoCompra = new PlanejamentoCompra();
                $planejamentoCompra->planejamento_id = $request->planejamento_id;
                $planejamentoCompra->insumo_id = $insumosOrcado->insumo_id;
                $planejamentoCompra->codigo_estruturado = $insumosOrcado->codigo_insumo;
                $planejamentoCompra->grupo_id = $insumosOrcado->grupo_id;
                $planejamentoCompra->subgrupo1_id = $insumosOrcado->subgrupo1_id;
                $planejamentoCompra->subgrupo2_id = $insumosOrcado->subgrupo2_id;
                $planejamentoCompra->subgrupo3_id = $insumosOrcado->subgrupo3_id;
                $planejamentoCompra->servico_id = $insumosOrcado->servico_id;
                $planejamentoCompra->save();
            }
            return Response()->json(['success' => true]);
        }
        return Response()->json(['success' => false, 'mensagem' => 'Não foram encontrado insumo no orçamento com os filtros passados!']);
    }

    /**
     * Update the specified Planejamento in storage.
     *
     * @param  int              $id
     * @param UpdatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlanejamentoRequest $request)
    {
        $planejamento = $this->planejamentoRepository->findWithoutFail($id);

        if (empty($planejamento)) {
            Flash::error('Planejamento '.trans('common.not-found'));

            return redirect(route('admin.planejamentos.index'));
        }
        $input = $request->all();

        $planejamento = $this->planejamentoRepository->update($input, $id);

        Flash::success('Planejamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.planejamentos.index'));
    }

    /**
     * Remove the specified Planejamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $planejamento = $this->planejamentoRepository->findWithoutFail($id);

        if (empty($planejamento)) {
            Flash::error('Planejamento '.trans('common.not-found'));

            return redirect(route('admin.planejamentos.index'));
        }

        $this->planejamentoRepository->delete($id);

        Flash::success('Planejamento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.planejamentos.index'));
    }



    ################################ IMPORTAÇÃO ###################################

    /**
     * $obras = Buscando chave e valor para fazer o combobox da view
     * $orcamento_tipos = Buscando chave e valor para fazer o combobox da view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexImport(){
        $obras = Obra::pluck('nome','id')->toArray();
        return view('admin.planejamentos.indexImport', compact('obras'));
    }

    /**
     * $request = Recebendo campos na view
     * $file = Pegando campos request exceto os que está dentro da exceção
     * $input = Pegando campos request exceto os campos que está dentro da exceção
     * $input['user_id'] = pegando usuário logado
     * $parametros = pegando $input e tranformando em json
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function import(Request $request)
    {
        $tipo = 'planejamento';
        $file = $request->except('obra_id');
        $input = $request->except('_token','file');
        $input['user_id'] = Auth::id();
        $parametros = json_encode($input);
        $colunasbd = [];

        # Enviando $file e $parametros para método de leitura da planilha.
        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros, $tipo);
        /* Percorrendo campos retornados e enviando para a view onde o
            usuário escolhe as colunas que vão ser importadas e tipos.
        */
        foreach ($retorno['colunas'] as $coluna => $type ) {
            $colunasbd[$coluna] = $coluna . ' - ' . $type;
        }

        # Colocando variaveis na sessão para fazer validações de campos obrigatórios.
        \Session::put('retorno', $retorno);
        \Session::put('colunasbd', $colunasbd);

        return redirect('/admin/planejamento/importar/selecionaCampos');
    }

    /**
     * Método para tranformar a requisição de POST para GET onde vamos fazer a validações dos campos obrigatórios
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selecionaCampos(Request $request){

        $retorno = $request->session()->get('retorno');
        $colunasbd = $request->session()->get('colunasbd');
        return view('admin.planejamentos.checkIn', compact('retorno','colunasbd'));
    }

    /*
     * $request = Pegando os campos selecionado de colunas a ser importadas e tipos das colunas.
     * Método responsável por enviar os dados para o método da fila.
     */
    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode(array_filter($input));

        # Validando campos obrigatórios como chave estrangeiras
//        $codigo_insumo = in_array('codigo_insumo', $input);
//        $unidade_sigla = in_array('unidade_sigla', $input);
//        if(!$codigo_insumo && !$unidade_sigla){
//            Flash::error('Os campos: codigo_insumo e unidade_sigla são obrigátorios.');
//            return back();
//        }

        # Pegando todas as planilhas por ordem decrescente
        $planilha = Planilha::orderBy('id','desc')->get();
        # consulta que trás somente a ultima planilha importada pelo usuário
        $planilha = $planilha->where('user_id', Auth::id())->first();
        # Após encontrar a planilha, será feito um update adicionando em array os campos escolhido pelo usuário.
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->update();
        }

        # Comentário de processamento de fila iniciada
        \Log::info("Ciclo de solicitações com filas iniciada");
        dispatch(new PlanilhaProcessa($planilha));
        # Comentário de processamento de fila finalizada
        \Log::info("Ciclo de solicitações com filas finalizada");

        # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
        Flash::warning('Importação incluida na FILA. Ao concluir o processamento enviaremos um ALERTA!');
        return redirect('admin/planejamento');
    }
}
