<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;


class TrainingController extends Controller
{
    public function index()
    {
        $training_list = Training::get();
        return view('backend.training.index',compact('training_list'));
    }

    public function Store(Request $request)
    {
        $training_list = new Training();
        $training_list->training_for = $request->training_for;
        $training_list->title = $request->title;

        if ($request->has('file_upload')) {
            $idproofp=time().".".$request->file_upload->getClientOriginalExtension();
             $request->file_upload->move(public_path('storage/training'), $idproofp);
           
            $training_list->file_path = 'storage/training/'.$idproofp;
        }

        $training_list->save();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function delete($id)
    {
        $training_list = Training::findOrFail($id);
        $training_list->delete();
        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));

    }

}
