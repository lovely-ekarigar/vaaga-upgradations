<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreBoardsRequest;
use App\Http\Requests\Admin\UpdateBoardsRequest;
use App\Models\Category;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class BoardsController extends Controller
{

    use FileUploadTrait;

    /**
     * Display a listing of Board.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boards_list = Board::orderBy('id','desc')->get();

        return view('backend.boards.index', compact('boards_list'));
    }

    public function create()
    {
        return view('backend.boards.create');
    }

    public function store(StoreBoardsRequest $request)
    {

        $this->validate($request, [
            'name' => 'required',
        ]);

       
        $board = Board::where('slug','=',str_slug($request->name))->first();
        if($board == null){
            $board = new  Board();
        }
        $board->name = $request->name;
        $board->status = "1";
        $board->slug = str_slug($request->name);
        
       if($request->hasFile('course_image')){
 $file = \Illuminate\Support\Facades\Request::file('course_image');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $board->board_image = $filename ;

          
        }
$board->save();
        return redirect()->route('admin.boards.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function edit($id)
    {
        $board = Board::find($id);
 
        return view('backend.boards.edit', compact('board'));
    }


    public function update(UpdateBoardsRequest $request, $id)
    {
        $board = Board::find($id);

        $board->name = $request->name;
        $board->slug = str_slug($request->name);


         if($request->hasFile('course_image')){
 $file = \Illuminate\Support\Facades\Request::file('course_image');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $board->board_image = $filename ;

          
        }
       
$board->update();

        return redirect()->route('admin.boards.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    public function delete($id)
    {
        $board = Board::find($id);
        $board->delete();

        return redirect()->route('admin.boards.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

  
}
