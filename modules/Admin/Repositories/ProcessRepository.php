<?php

namespace Modules\Admin\Repositories;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use App\Repositories\BaseRepository;
use DB;
use File;
use http\Env\Request;
use Session;
use Modules\Admin\Models\Process;

class ProcessRepository extends BaseRepository
{
    public function getModel()
    {
        return Process::class;
    }

    public  function listProcesses()
    {
        return $this->model->paginate(5);
    }

    public function createProcess($request){
        try{
            $process = [
//                'slug' => Str::slug($request->name),
                'title' => $request->title,
//                'is_active' => $request->is_active ?? 0,
            ];

            $getImage= $request->file('image');

            if($getImage){
                $nameImage= $getImage->getClientOriginalName();
                $newNameImage= time() . $nameImage;
                $getImage->move('upload/process', $newNameImage);
            } else {
                $newNameImage='';
            }
            $process['image'] = $newNameImage;
            return $process = $this->model->create($process);
        } catch (DDException $th) {
            LogHelper::logTrace($th);

            throw $th;
        }
    }
    public function editProcess($request, $id){
        try{
            $data = $request->only([
                'title',
//                'title_ja',
//                'title_en',
//                'status',
            ]);

            $process = Process::find($id);

            $process->title = $data['title'];
//            $slider->title_ja = $data['title_ja'];
//            $slider->title_en = $data['title_en'];
//            $slider->status   = $data['status'];

            if($request->file('image')){
                $file= $request->file('image');
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('/uploads/process'), $filename);
                $process->image= $filename;
            }

            $process->save();

            return $process;

        } catch (DDException $th) {
            LogHelper::logTrace($th);

            throw $th;
        }
    }
    public function destroyProcess($id)
    {
        $process = $this->model->find($id);
        $process->delete();

        $imagePath = "uploads/image/" .  $process->image;
        File::delete($imagePath);
        return true;

    }
    public function searchProcess($request)
    {
        $search= (int)$request->search;
        if($search) {
            $process =$this->model->where('title','like', '%'.$request->search.'%')->orWhere('id', $request->search)->paginate(9);
        } else {
            $process =$this->model->where('title','like', '%'.$request->search.'%')->paginate(9);
        }
        
        return $process;
    }

    public function sortProcess()
    {
        $process = Process::orderBy('id', 'DESC')->get();
        return $process;

    }


}
