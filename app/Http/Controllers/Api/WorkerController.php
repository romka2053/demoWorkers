<?php

namespace App\Http\Controllers\Api;
use App\Models\Worker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class WorkerController extends Controller
{
    public function index(Request $request)
    { /**
        $search=$request->input('s');
        $sort=$request->input('h');
        if($search!=null || $sort!=null)
        {
            if($search!=null) {
                $workers = worker::where('name', 'LIKE', "%{$search}%")
                    ->orwhere('id', 'LIKE', "%{$search}%")
                    ->orwhere('post', 'LIKE', "%{$search}%")
                    ->orwhere('device_date', 'LIKE', "%{$search}%")
                    ->orwhere('salary', 'LIKE', "%{$search}%");
            }else{
                $workers=new Worker;
            }
            if($sort==null)
            {
                $workers=$workers->paginate(15);

            }else {

                if($sort[0]=='-') {

                    $par="DESC";
                    $name=substr($sort,1);
                    $workers = $workers->orderby($name, $par)->paginate(15);

                }else{
                    $par="asc";
                    $workers = $workers->orderby($sort, $par)->paginate(15);

                }
            }
            return $workers;



        }
        else {
            return worker::paginate(15);
        }

     */
        $workers=Worker::all();
        return $workers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function tree(Request $request)
    {
        $parent_id=$request->id;
        $chief = Worker::where('parent_id',$parent_id)
            ->with('children')
            ->get();
        return $chief;
    }

    public function store(Request $request)
    {
        $addworker=  new worker([
            'name'=>$request->
            get('name'),
            'post'=>$request->
            get('post'),
            'device_date'=>$request->
            get('device_date'),
            'salary'=>$request->
            get('salary'),
            'parent_id'=>$request->
            get('parent_id'),

        ]);
        $addworker->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {$id=$request->id;
        $editWorker=worker::find($id);
        return $editWorker;
    }


    public function update(Request $request, $id)
    {
        $editWorker=worker::find($id);
        $editWorker->name=$request->get('name');
        $editWorker->post=$request->get('post');
        $editWorker->device_date=$request->get('device_date');
        $editWorker->salary=$request->get('salary');
        $editWorker->parent_id=$request->get('parent_id');
        $editWorker->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        worker::find($id)->delete();
    }

    public function image(Request $request)
    {
        $result=['success'=>true];


        if($request->fupload)
        {
            $file=$request->fupload;
            $id=$request->id;

            $ext=$file->getClientOriginalExtension();
            $filename=$file->getClientOriginalName();
            //$mime=$file->getClientMimeType();
           // $size=$file->getSize();

            try{
                $file->move('images',$filename);

                $editWorker=worker::find($id);
                $editWorker->urlImage=$filename;
                $editWorker->save();
            }catch(Exception $e){
                $result['success']=false;
                $result['error']=$e->getMessage();
            }

        }

    }
}
