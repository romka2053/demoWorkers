<?php

namespace App\Http\Controllers\Api;
use App\Models\Worker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $search=$request->input('s');
        $sort=$request->input('h');
        $pagecount=$request->p;
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
                $workers=$workers->paginate($pagecount);

            }else {

                if($sort[0]=='-') {

                    $par="DESC";
                    $name=substr($sort,1);
                    $workers = $workers->orderby($name, $par)->paginate($pagecount);

                }else{
                    $par="asc";
                    $workers = $workers->orderby($sort, $par)->paginate($pagecount);

                }
            }
            return $workers;



        }
        else {
            return worker::paginate($pagecount);
        }



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function treeDel(Request $request)
    {
        $parent_id=$request->id;
        $delete_id=$request->delete_id;
        $chief = Worker::where([['parent_id',$parent_id],['id','<>',$delete_id]])
            ->with('children')
            ->get();
        return $chief;
    }

    public function tree(Request $request)
    {
        $parent_id=$request->id;
        $delete_id=$request->delete_id;
        $chief = Worker::where('parent_id',$parent_id)
            ->with('children')
            ->get();
        return $chief;
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'post' => ['required', 'string', 'max:40'],
            'device_date' => ['required'],
            'salary' => ['required','numeric'],
            'parent_id' => ['integer'],

        ]);
        if($request->fupload)
        {
            $request->validate([
                'fupload' => 'required|image',
            ]);
            $file=$request->fupload;


            $filename=$file->getClientOriginalName();
            $filename=time().random_int(1,10000).random_int(1,100000).$filename;
            //$mime=$file->getClientMimeType();
            // $size=$file->getSize();

            try{
                $file->move('images',$filename);


            }catch(Exception $e){

                $result['success']=$request->fupload;
            }

        }else{
            $filename=null;

        }

        $addworker=  new worker([
            'name'=>$request->name,
            'post'=>$request->post,
            'device_date'=>$request->device_date,
            'salary'=>$request->salary,
            'parent_id'=>$request->parent_id,
            'urlImage'=>$filename
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
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'post' => ['required', 'string', 'max:40'],
            'device_date' => ['required'],
            'salary' => ['required','numeric'],
            'parent_id' => ['integer'],

        ]);
        $editWorker=Worker::find($id);
        $editWorker->name=$request->input('name');
        $editWorker->post=$request->input('post');
        $editWorker->device_date=$request->input('device_date');
        $editWorker->salary=$request->input('salary');
        $editWorker->parent_id=$request->input('parent_id');
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
    public function Parent(Request $request){
        $deleteid=$request->deleteid;
        $parentid=$request->parentid;
       Worker::where('parent_id',$deleteid)->update(['parent_id' => $parentid]);



    }

    public function image(Request $request)
    {
        $result=['success'=>true];


        if($request->fupload)
        {
            $request->validate([
                'fupload' => 'required|image',
            ]);
            $file=$request->fupload;
            $id=$request->id;

            $ext=$file->getClientOriginalExtension();
            $filename=$file->getClientOriginalName();

            $filename=time().random_int(1,10000).random_int(1,100000).$filename;
            //$mime=$file->getClientMimeType();
           // $size=$file->getSize();

            try{

                $editWorker=worker::find($id);
                if($editWorker->urlImage!=null)
                {
                    if(file_exists(public_path().'/images/'.$editWorker->urlImage)) {
                        unlink(public_path() . '/images/' . $editWorker->urlImage);
                    }
                }
                $file->move('images',$filename);
                $editWorker->urlImage=$filename;
                $editWorker->save();

            }catch(Exception $e){
                $result['success']=false;
                $result['error']=$e->getMessage();
            }

        }

    }
}
