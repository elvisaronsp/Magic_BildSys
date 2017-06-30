<?php

namespace App\Http\Controllers;

use App\DataTables\MemoriaCalculoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMemoriaCalculoRequest;
use App\Http\Requests\UpdateMemoriaCalculoRequest;
use App\Repositories\MemoriaCalculoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class MemoriaCalculoController extends AppBaseController
{
    /** @var  MemoriaCalculoRepository */
    private $memoriaCalculoRepository;

    public function __construct(MemoriaCalculoRepository $memoriaCalculoRepo)
    {
        $this->memoriaCalculoRepository = $memoriaCalculoRepo;
    }

    /**
     * Display a listing of the MemoriaCalculo.
     *
     * @param MemoriaCalculoDataTable $memoriaCalculoDataTable
     * @return Response
     */
    public function index(MemoriaCalculoDataTable $memoriaCalculoDataTable)
    {
        return $memoriaCalculoDataTable->render('memoria_calculos.index');
    }

    /**
     * Show the form for creating a new MemoriaCalculo.
     *
     * @return Response
     */
    public function create()
    {
        return view('memoria_calculos.create');
    }

    /**
     * Store a newly created MemoriaCalculo in storage.
     *
     * @param CreateMemoriaCalculoRequest $request
     *
     * @return Response
     */
    public function store(CreateMemoriaCalculoRequest $request)
    {
        $input = $request->all();

        $memoriaCalculo = $this->memoriaCalculoRepository->create($input);

        Flash::success('Memoria Calculo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }

    /**
     * Display the specified MemoriaCalculo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        return view('memoria_calculos.show')->with('memoriaCalculo', $memoriaCalculo);
    }

    /**
     * Show the form for editing the specified MemoriaCalculo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        return view('memoria_calculos.edit')->with('memoriaCalculo', $memoriaCalculo);
    }

    /**
     * Update the specified MemoriaCalculo in storage.
     *
     * @param  int              $id
     * @param UpdateMemoriaCalculoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemoriaCalculoRequest $request)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        $memoriaCalculo = $this->memoriaCalculoRepository->update($request->all(), $id);

        Flash::success('Memoria Calculo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }

    /**
     * Remove the specified MemoriaCalculo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        $this->memoriaCalculoRepository->delete($id);

        Flash::success('Memoria Calculo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }
}
