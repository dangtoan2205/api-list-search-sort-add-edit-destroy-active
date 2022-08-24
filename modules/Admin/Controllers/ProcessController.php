<?php

namespace Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Resources\ProcessResource;
use Modules\Admin\Services\ProcessService;
use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use Modules\Admin\Requests\ProcessRequest;


class ProcessController extends Controller
{

    protected $processService;


    public function __construct(
        ProcessService $processService
    )
    {
        $this->processService = $processService;
    }

    public function index()
    {
        $processes = $this->processService->listProcesses();
        return response()->json($processes);

    }

    public function store(ProcessRequest $request)
    {
        try {
            $process = $this->processService->createProcess($request);
            if($process){
                $data =[
                    'success'=> 'Thêm thành công',
                ];
                return response()->json($data, 200);
            }else{
                return response()->json(['faild'=>'Thêm thất bại']);
            }

            $result = new ProcessResource($process);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(ProcessRequest $request, $id)
    {
        try {
            $process = $this->processService->editProcess($request, $id);

            if($process){
                $data =[
                    'success'=> 'Sửa thành công',
                ];
                return response()->json($data, 200);
            }else{
                return response()->json(['faild'=>'Sửa thất bại']);
            }

            $result = new ProcessResource($process);

            return response()->success($result);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            return response()->failure($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $process = $this->processService->destroyProcess($id);
        if($process){
            $data =[
                'success'=> 'Xóa thành công',
            ];
            return response()->json($data, 200);
        }else{
            return response()->json(['faild'=>'Xóa thất bại']);
        }

    }

    public function search(Request $request) {
        $process = $this->processService->searchProcess($request);

        return response()->json($process);
    }

    public function sort(Request $request)
    {
        $process = $this->processService->sortProcess($request);

        return response()->json($process);
    }

}
