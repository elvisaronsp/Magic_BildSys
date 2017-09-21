<?php

namespace App\Http\Controllers;

use App\DataTables\PagamentoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePagamentoRequest;
use App\Http\Requests\UpdatePagamentoRequest;
use App\Repositories\PagamentoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PagamentoController extends AppBaseController
{
    /** @var  PagamentoRepository */
    private $pagamentoRepository;

    public function __construct(PagamentoRepository $pagamentoRepo)
    {
        $this->pagamentoRepository = $pagamentoRepo;
    }

    /**
     * Display a listing of the Pagamento.
     *
     * @param PagamentoDataTable $pagamentoDataTable
     * @return Response
     */
    public function index(PagamentoDataTable $pagamentoDataTable)
    {
        return $pagamentoDataTable->render('pagamentos.index');
    }

    /**
     * Show the form for creating a new Pagamento.
     *
     * @return Response
     */
    public function create()
    {
        return view('pagamentos.create');
    }

    /**
     * Store a newly created Pagamento in storage.
     *
     * @param CreatePagamentoRequest $request
     *
     * @return Response
     */
    public function store(CreatePagamentoRequest $request)
    {
        $input = $request->all();

        $pagamento = $this->pagamentoRepository->create($input);

        Flash::success('Pagamento '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('pagamentos.index'));
    }

    /**
     * Display the specified Pagamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento '.trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        return view('pagamentos.show')->with('pagamento', $pagamento);
    }

    /**
     * Show the form for editing the specified Pagamento.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento '.trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        return view('pagamentos.edit')->with('pagamento', $pagamento);
    }

    /**
     * Update the specified Pagamento in storage.
     *
     * @param  int              $id
     * @param UpdatePagamentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePagamentoRequest $request)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento '.trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $pagamento = $this->pagamentoRepository->update($request->all(), $id);

        Flash::success('Pagamento '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('pagamentos.index'));
    }

    /**
     * Remove the specified Pagamento from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pagamento = $this->pagamentoRepository->findWithoutFail($id);

        if (empty($pagamento)) {
            Flash::error('Pagamento '.trans('common.not-found'));

            return redirect(route('pagamentos.index'));
        }

        $this->pagamentoRepository->delete($id);

        Flash::success('Pagamento '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('pagamentos.index'));
    }
}
