<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CronogramaFisicoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CronogramaFisicoRequest;
use App\Http\Requests\Admin\UpdateCronogramaFisicoRequest;
use App\Jobs\PlanilhaProcessa;
use App\Models\CronogramaFisico;
use App\Models\Obra;
use App\Models\Planilha;
use App\Models\Servico;
use App\Models\TemplatePlanilha;
use App\Repositories\Admin\CronogramaFisicoRepository;
use App\Repositories\Admin\SpreadsheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class CronogramaFisicoController extends AppBaseController
{
    /** @var  CronogramaFisicoRepository */
    private $cronogramaFisicoRepository;

    public function __construct(CronogramaFisicoRepository $cronogramaFisicoRepo)
    {
        $this->cronogramaFisicoRepository = $cronogramaFisicoRepo;
    }

    /**
     * Display a listing of the Cronograma Fisico.
     *
     * @param CronogramaFisicoDataTable $cronogramaFisicoDataTable
     * @return Response
     */
    public function index(Request $request, CronogramaFisicoDataTable $cronogramaFisicoDataTable)
    {
        $id = null;
        if($request->id){
            $id = $request->id;
        }
				
		$obras = Obra::pluck('nome','id')->toArray();
		$meses = ["07/2017", "08/2017", "09/2017"];
		
		/*$tipos = ["", "", "", "", ""];
		$ano = ["", "", "", "", ""];*/
		
		
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Fisicos')->pluck('nome','id')->toArray();
		
        return $cronogramaFisicoDataTable->porObra($id)->render('admin.cronograma_fisicos.index', compact('obras', 'meses', 'templates','id'));
    }

    /**
     * Show the form for creating a new Cronograma Fisico.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('admin.cronograma_fisicos.create', compact('obras'));
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

        $cronogramaFisico = $this->cronogramaFisicoRepository->create($input);

        Flash::success('Cronograma Fisico '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Display the specified Cronograma Fisico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);        

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        return view('admin.cronograma_fisicos.show', compact('cronogramaFisico'));
    }

    /**
     * Show the form for editing the specified Cronograma Fisico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $obras = Obra::pluck('nome','id')->toArray();
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }


        return view('admin.cronograma_fisicos.edit', compact('cronogramaFisico','obras'));
    }

    /**
     * Update the specified Cronograma Fisico in storage.
     *
     * @param  int              $id
     * @param UpdatePlanejamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCronogramaFisicoRequest $request)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }
        $input = $request->all();

        $cronogramaFisico = $this->cronogramaFisicoRepository->update($input, $id);

        Flash::success('Cronograma Fisico '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }

    /**
     * Remove the specified Cronograma Fisico from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cronogramaFisico = $this->cronogramaFisicoRepository->findWithoutFail($id);

        if (empty($cronogramaFisico)) {
            Flash::error('Cronograma Fisico '.trans('common.not-found'));

            return redirect(route('admin.cronograma_fisicos.index'));
        }

        $this->cronogramaFisicoRepository->delete($id);

        Flash::success('Cronograma Fisico '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.cronograma_fisicos.index'));
    }


    ################################ IMPORTAÇÃO ###################################

    /**
     * $obras = Buscando chave e valor para fazer o combobox da view
     * $orcamento_tipos = Buscando chave e valor para fazer o combobox da view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexImport(Request $request){
        
		$id = null;
        if($request->id){
            $id = $request->id;
        }

        $obras = Obra::pluck('nome','id')->toArray();
        $templates = TemplatePlanilha::where('modulo', 'Cronograma Fisicos')->pluck('nome','id')->toArray();
        return view('admin.cronograma_fisicos.indexImport', compact('obras','templates','id'));
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
        $tipo = 'cronogramaFisico';
        $file = $request->except('obra_id','template_id');
        $input = $request->except('_token','file');
        $template = $request->template_id;
        $input['user_id'] = Auth::id();
		$input['template_id'] = $request->template_id;
        $parametros = json_encode($input);
        $colunasbd = [];

        # Enviando $file e $parametros para método de leitura da planilha.
        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros, $tipo);
        /* Percorrendo campos retornados e enviando para a view onde o
            usuário escolhe as colunas que vão ser importadas e tipos.
        */
        if(is_array($retorno)){
            foreach ($retorno['colunas'] as $coluna => $type ) {
                $colunasbd[$coluna] = $coluna . ' - ' . $type;
            }

            # Colocando variaveis na sessão para fazer validações de campos obrigatórios.
            \Session::put('retorno', $retorno);
            \Session::put('colunasbd', $colunasbd);

            return redirect('/admin/cronogramaFisico/importar/selecionaCampos?planilha_id='.$retorno['planilha_id'].($template?'&template_id='.$template:''));
        }else{
            return $retorno;
        }
    }

    /**
     * Método para tranformar a requisição de POST para GET onde vamos fazer a validações dos campos obrigatórios
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selecionaCampos(Request $request){

        $retorno = $request->session()->get('retorno');
        $colunasbd = $request->session()->get('colunasbd');
        if($request->template_id){
            $planilha = Planilha::find($request->planilha_id);
            $template = TemplatePlanilha::find($request->template_id);
            if($planilha && $template) {
                $planilha->colunas_json = $template->colunas;
                $planilha->save();

                # Coloca processo na fila
                dispatch(new PlanilhaProcessa($planilha));

                # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
                Flash::warning('Importação incluída na FILA. Ao concluir o processamento enviaremos um ALERTA!');

                return redirect('admin/cronogramaFisico');
            }
        }
        return view('admin.cronograma_fisicos.checkIn', compact('retorno','colunasbd'));
    }

    /*
     * $request = Pegando os campos selecionado de colunas a ser importadas e tipos das colunas.
     * Método responsável por enviar os dados para o método da fila.
     */
    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode(array_filter($input));

        # Pegando todas as planilhas por ordem decrescente e que trás somente a ultima planilha importada pelo usuário
        $planilha = Planilha::where('user_id', \Auth::id())->orderBy('id','desc')->first();
        # Após encontrar a planilha, será feito um update adicionando em array os campos escolhido pelo usuário.
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->save();
        }

        # Salvar os campos escolhido na primeira importação de planilha para criar um modelo de template
        $template_orcamento = TemplatePlanilha::firstOrNew([
            'nome' => 'CronogramaFisico',
            'modulo' => 'CronogramaFisico'
        ]);
        $template_orcamento->colunas = $json;
        $template_orcamento->save();

        # Comentário de processamento de fila iniciada
        \Log::info("Ciclo de solicitações com filas iniciada");
        dispatch(new PlanilhaProcessa($planilha));
        # Comentário de processamento de fila finalizada
        \Log::info("Ciclo de solicitações com filas finalizada");

        # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
        Flash::warning('Importação incluída na FILA. Ao concluir o processamento enviaremos um ALERTA!');
        return redirect('admin/cronogramaFisico');
    }
	
	################################ Relatórios ###################################
	
	//Acompanhamento Semanal
	public function relSemanal(Request $request, CronogramaFisicoDataTable $cronogramaFisicoDataTable)
    {
				
		//Carregar Dados Iniciais
		$obraId = 0;
		$mesId = 0;
		$semanaId = 0;
		
		$meses = ["2017-09"];
		$semanas = ["","1","2","3","4","5"];
		
		$tabColetaSemanal = [];
		$tabPercentualPrevReal = [];
		
		$tabTarefasCriticas = [];
		$tabTarefasCriticas['labels'] = [];
		$tabTarefasCriticas['data'] = [];
		
		$grafTarefasCriticas = [];
		$grafTarefasCriticas['labels'] = [];
		$grafTarefasCriticas['data']['previstoAcum'] = "";
		$grafTarefasCriticas['data']['realizadoAcum'] = "";
		
		$showDados = false;
		
		/*$inicioMes = "2017-07-01";
		$fimMes = "2017-07-31";*/
		
		//Carregar Combos
        $obras = Obra::join('cronograma_fisicos', 'cronograma_fisicos.obra_id', '=', 'obras.id')                        
                        ->orderBy('obras.nome', 'ASC')
                        ->pluck('obras.nome', 'obras.id')
                        ->toArray();
		
		//Filtros Obra, Mês e Semana de Referências		
		if($request->obra_id) {            
            
			$obraId = $request->obra_id;
			
			$obraDataInicio = Obra::select([
							'obras.id',
							'obras.data_inicio'							
						])											
						->where('obras.id', $obraId)						
                        ->orderBy('obras.id', 'ASC')                        
                        ->first()
						->data_inicio;	
								
			$obraDataInicio = Carbon::parse($obraDataInicio);
			
			if(Carbon::now()->subMonth() < Carbon::parse($obraDataInicio)->addMonths(36)){
				$obraDataFinal = Carbon::now()->subMonth();
			}else{
				$obraDataFinal = Carbon::parse($obraDataInicio)->addMonths(36);
			}
			
			$meses = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, $obraDataFinal);// ["julho-17","agosto-17","setembro-17"];
			$mesesObra = CronogramaFisicoRepository::getIntervalMonthsByDates($obraDataInicio, Carbon::parse($obraDataInicio)->addMonths(36)); // Todos os meses da Obra
			
		}		
		
		if($request->mes_id) {            
			
			$mesId = $request->mes_id;
			$mesRef = $meses[$mesId];
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$meses[$mesId])->startOfMonth();
			$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$meses[$mesId])->endOfMonth();	
			
		}
			
		if($request->semana_id) { 
			$semanaId = $request->semana_id;
		}

		if(($request->obra_id)&&($request->mes_id)&&($request->semana_id)){
			
			$showDados = true;
			
			/***** Tabela Coleta Semanal - Dados, pega da Tendencia Real ****/
			$tabColetaSemanal = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, "Tendência Real");
						
			/***** Tabela Percentual Previsto e Acumulado ****/	
			$tabPercentualPrevReal['labels'] = CronogramaFisicoRepository::getFridaysByDate($inicioMes);	; //Label Horizontal				

			/***** Tabela Percentual Previsto x Percentual Realizado - Dados: Vindo da Curva de Andamento (PD, PT, TR, TD e TT) ****/			
			$planoDiretorAcumulado = $this->planoAcumulado($this->percentualPorMes($obraId, $meses, "Plano Diretor"));
			$planoTrabalhoAcumulado = $this->planoAcumulado($this->percentualPorMes($obraId, $meses, "Plano Trabalho"));
			$planoPrevistoAcumulado = $this->planoAcumulado($this->percentualPorMes($obraId, $meses, "Tendência Real"));			
			
			$tabPercentualPrevReal['data']['planoDiretorAcumulado'] = $this->getPlanoAcumuladoMes($planoDiretorAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['planoTrabalhoAcumulado'] = $this->getPlanoAcumuladoMes($planoTrabalhoAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['planoPrevistoAcumulado'] = $this->getPlanoAcumuladoMes($planoPrevistoAcumulado, $mesRef);
			$tabPercentualPrevReal['data']['previstoSemanal'] = $this->previstoSemanal($tabColetaSemanal, $inicioMes, $semanaId);

			/*dump($planoDiretorAcumulado);
			dump($planoTrabalhoAcumulado);
			dump($planoPrevistoAcumulado);
			die;*/
			
			/***** Tabela Tarefas Críticas ****/
			$tabTarefasCriticas = [];
			$tabTarefasCriticas['labels'] = ["LOCAL","Tarefas Críticas","Previsto Ac.","Realizado Ac.","Desvio"]; //Label 		
			$tabTarefasCriticas['data'] = $this->tabTarefasCriticas($tabColetaSemanal, $inicioMes, $semanaId); //Dados
									
			/***** Gráfico Tarefas Críticas	 ****/	
			$grafTarefasCriticas = [];
			$grafTarefasCriticasLabels = [];
			$grafTarefasCriticasPrevAcum = [];
			$grafTarefasCriticasRealAcum = [];
			
			//Pegar Labels vinda das Tarefas
			foreach ($tabTarefasCriticas['data'] as $tmp1) {			
				array_push($grafTarefasCriticasLabels, ltrim($tmp1['tarefa']));							
			}
			$grafTarefasCriticas['labels'] = $grafTarefasCriticasLabels;		
			
			foreach ($tabTarefasCriticas['data'] as $tmp2) {			
				array_push($grafTarefasCriticasPrevAcum, $tmp2['previsto']);
				array_push($grafTarefasCriticasRealAcum, $tmp2['realizado']);			
			}		
			
			$grafTarefasCriticas['data']['previstoAcum'] = $grafTarefasCriticasPrevAcum;
			$grafTarefasCriticas['data']['realizadoAcum'] = $grafTarefasCriticasRealAcum;	
		}
		
		return 
			$cronogramaFisicoDataTable->render('admin.cronograma_fisicos.relSemanal', 
				compact(
					'showDados',
					'obraId', 'mesId', 'semanaId', 'obras', 'meses', 'semanas',
					'tabPercentualPrevReal', 
					'tabTarefasCriticas', 'grafTarefasCriticas'
				)
			);
    }	
	
	// Dados calculados em todos os meses
	public function percentualPorMes($obraId, $meses, $tipoPlanejamento){
		
		//Remover o primeiro item do select
		unset($meses[0]);
		
		foreach ($meses as $mes) {
												
			if (isset($mes)){			
				
				$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->startOfMonth();
				$fimMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->endOfMonth();
				
				$percentualPorMes[$mes] = $this->tabColetaSemanal($obraId, $inicioMes, $fimMes, $tipoPlanejamento);												
			}
			
		}
		
		return $percentualPorMes;
		
	}
	
	// Dados vindo calculados dos plano escolhido
	public function planoAcumulado($percentualPorMes){
				
		$planoAcumulado = [];
		$acumuladoTotal = 0;
		
		foreach ($percentualPorMes as $mes => $dados) {			
			
			$inicioMes = Carbon::createFromFormat("d/m/Y", "01/".$mes)->startOfMonth();
			
			// Todas as Sextas do Mês de Referencia
			$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);

			foreach ($sextasArray as $sexta) {
					
					$acumuladoSemanal = 0;
					
					foreach ($dados as $tmp) {
						$acumuladoSemanal = $acumuladoSemanal + ($tmp["percentual-".$sexta]/100) * $tmp["peso"];						
					}				
					
					$acumuladoTotal = $acumuladoTotal  + $acumuladoSemanal;
					$planoAcumulado[$mes][$sexta] = round($acumuladoTotal,4);
			}
			
			$planoAcumulado[$mes]['mes'] = round($acumuladoTotal,4);
			
									
		}	
		
		/*dump($percentualPorMes);
		dump($planoAcumulado);*/
				
		return $planoAcumulado;
		
		
	}
	
	// Filtrar dados do plano Acumulados para o Mes de Referencia
	public function getPlanoAcumuladoMes($planoAcumulado, $mesRef){
				
		$planoAcumuladoMes = $planoAcumulado[$mesRef];						
				
		return $planoAcumuladoMes;
		
	}	
		
	// Dados vindo do Mediçao Fisicas
	public function realizadoSemanal($planoTendenciaReal){
		
	}
	
	// Retornar Valor % por Tipo de Planejamento, Obra, Mês e Semana
	public function tabColetaSemanal($obraId, $inicioMes, $fimMes, $tipoPlanejamento){	
		
		//DB::enableQueryLog();
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$ultimaSexta= end($sextasArray);
		
		$tabColetaSemanal = CronogramaFisico::select([
			'cronograma_fisicos.id',
			'cronograma_fisicos.torre',
			'cronograma_fisicos.pavimento',				
			'cronograma_fisicos.tarefa',
			'cronograma_fisicos.data_inicio',
			'cronograma_fisicos.data_termino',
			'cronograma_fisicos.critica',
			DB::raw("(SELECT ROUND(CF.custo/
						(SELECT custo 
							FROM cronograma_fisicos  
							WHERE tarefa like '%Cronograma%'
							GROUP BY custo
						)*100,2) custo_total
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id 
					 ) as peso"
			),
			/*'cronograma_fisicos.concluida',				
			DB::raw("(SELECT (case when count(distinct CF.id) = 1 then 'Sim' else 'Não' end) as tarefa_mes
						FROM cronograma_fisicos CF
						WHERE CF.id = cronograma_fisicos.id						
						AND (CF.data_termino >= '$inicioMes')						
						AND (CF.data_inicio <= '$fimMes')
					 ) as tarefa_mes"
			),*/					
			/*DB::raw("(SELECT MF.valor_medido as valor_realizado
						FROM medicao_fisicas MF
						WHERE MF.tarefa = cronograma_fisicos.tarefa						
						AND MF.obra_id = cronograma_fisicos.obra_id						
					 ) as valor_realizado"
			),*/
			'cronograma_fisicos.created_at'					
		])
		->join('obras','obras.id','cronograma_fisicos.obra_id')
		->join('template_planilhas','template_planilhas.id','cronograma_fisicos.template_id')
		->where('cronograma_fisicos.obra_id', $obraId)
		->where('cronograma_fisicos.resumo','Não')
		->where('cronograma_fisicos.data_termino','>=',$inicioMes)
		->where('cronograma_fisicos.data_inicio','<=',$fimMes)		
		->where('template_planilhas.nome',$tipoPlanejamento)		
		->orderBy('cronograma_fisicos.data_inicio', 'desc')
		->groupBy('cronograma_fisicos.tarefa')		
		->get()
		->toArray();
		
		//Filtrar por Tarefa do Mês				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {				
			
			//if($tarefa['tarefa_mes']!='Sim'){
				
				//unset($tabColetaSemanal[$keyT]);
			
			//}else{
				
				foreach($sextasArray as $sexta){								
					
					//Calcular % da Semana
					$inicioTarefa = Carbon::parse($tarefa['data_inicio']);
					$fimTarefa = Carbon::parse($tarefa['data_termino']);					
					
					$inicioSemana = Carbon::createFromFormat("d/m/Y", $sexta)->subDays(6);									
					$fimSemana = Carbon::createFromFormat("d/m/Y", $sexta);						

					$valorPrevisto = CronogramaFisicoRepository::getPrevistoPorcentagem($inicioTarefa, $fimTarefa, $inicioSemana, $fimSemana);										
					$valorMedicaoFisica = CronogramaFisicoRepository::getRealizadoPorcentagem($inicioSemana, $fimSemana, $obraId, $tarefa['tarefa']);																				
									
					$tabColetaSemanal[$keyT]["percentual-".$sexta] =  round($valorPrevisto,4);
					$tabColetaSemanal[$keyT]["realizado-".$sexta] =  $valorMedicaoFisica; //Mediçao Fisicas
					//Mes
					//Farol
										
				}				
			//}	
		}
		
		//dump($tabColetaSemanal);die;
		
		return $tabColetaSemanal;
	}
			
	// Tabela Tarefas Criticas - Dados
	public function tabTarefasCriticas($tabColetaSemanal, $inicioMes, $semanaId){
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		$ultimaSexta= end($sextasArray);
		
		//Data da Sexta feira da Semana de Referência
		$semanaSexta = $sextasArray[$semanaId];
		
		$tabTarefasCriticas = [];
				
		//Filtrar por Critica				
		foreach ($tabColetaSemanal as $keyT => $tarefa) {
			
			$tmp1="percentual-".$semanaSexta;
			$tmp2="realizado-".$semanaSexta;
					
			if($tarefa['critica']=='Sim'){					
				
				$tabTarefasCriticas[$keyT]['local'] = $tarefa['torre'];
				$tabTarefasCriticas[$keyT]['tarefa'] = ltrim($tarefa['tarefa']);
				$tabTarefasCriticas[$keyT]['peso'] = $tarefa['peso'];
				$tabTarefasCriticas[$keyT]['previsto'] = $tarefa[$tmp1];
				$tabTarefasCriticas[$keyT]['realizado'] = $tarefa[$tmp2];
			
			}			
				
		}		
		
		return $tabTarefasCriticas;
	}
	
	// Retornar dados do valor Previsto por Semana	
	public function previstoSemanal($tabColetaSemanal, $inicioMes){
				
		$previstoSemanal = [];
		$acumuladoMes = 0;
		
		// Todas as Sextas do Mês de Referencia
		$sextasArray = CronogramaFisicoRepository::getFridaysByDate($inicioMes);		
		
		foreach ($sextasArray as $sexta) {
			
			$acumuladoSemanal = 0;
				
			//Filtrar por Critica				
			foreach ($tabColetaSemanal as $key => $tarefa) {
				
				$acumuladoSemanal = $acumuladoSemanal + ($tarefa["percentual-".$sexta]/100)*$tarefa["peso"];								
					
			}
			
			$previstoSemanal[$sexta] = round($acumuladoSemanal,4);
			$acumuladoMes = $acumuladoMes + round($acumuladoSemanal,4);
		}		
		
		$previstoSemanal["mes"] = $acumuladoMes; 
				
		
		return $previstoSemanal;
	}
		
	//Acompanhamento Mensal
	public function relMensal(Request $request)
    {	
		
		$obras = Obra::pluck('nome','id')->toArray(); 	
		
		$assertividadeMensal = [0.68, 0.68, 0.97, 0.71, -26.80];
               
        return view('admin.cronograma_fisicos.relMensal', compact('obras', 'assertividadeMensal'));
    }
	
	
	
	
}
