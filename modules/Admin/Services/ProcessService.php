<?php

namespace Modules\Admin\Services;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use App\Services\BaseService;
use http\Env\Request;
use Modules\Admin\Repositories\ProcessRepository;
use Modules\Admin\Models\Process;

class ProcessService extends BaseService
{
    public function __construct( ProcessRepository $repository )
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getModel()
    {
        return Process::class;
    }
    public  function listProcesses()
    {
        return $this->repository->listProcesses();
    }
    public function createProcess($request){
        try {
            return $this->repository->createProcess($request);
        } catch (DDException $th) {
            LogHelper::logTrace($th);
            throw $th;
        }
    }
    public function editProcess($request, $id){
        try {
            return $this->repository->editProcess($request, $id);
        } catch (DDException $th) {
            LogHelper::logTrace($th);
            throw $th;
        }
    }
    public function destroyProcess($id)
    {
        return $this->repository->destroyProcess($id);
    }

    public function searchProcess($request)
    {
        return $this->repository->searchProcess($request);
    }

    public function sortProcess($request)
    {
        return $this->repository->sortProcess($request);
    }

}
