<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.reviews.index');
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $reviews = "";
        $courses_id = auth()->user()->courses()->has('reviews')->pluck('id')->toArray();
        $reviews = Review::where('reviewable_type','=','App\Models\Course')
            ->whereIn('reviewable_id',$courses_id)
            ->orderBy('created_at', 'desc')
            ->get();


        return DataTables::of($reviews)
            ->addIndexColumn()
            ->editColumn('created_at', function ($q) {
                return $q->created_at->format('d M, Y | H:i A');
            })
            ->addColumn('course', function ($q) {
               $course_name = $q->reviewable->title;
               $course_slug = $q->reviewable->slug;
               $link = "<a href='".route('courses.show', [$course_slug])."' target='_blank'>".$course_name."</a>";
               return $link;
            })
            ->addColumn('user',function ($q){
                return $q->user->full_name;
            })
            ->rawColumns(['course'])
            ->make();
    }

    /**
     * Show the form for creating new Review.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.reviews.create');
    }

    /**
     * Store a newly created Review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create($request->all());

        return redirect()->route('admin.reviews.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified Review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::findOrFail($id);

        return view('backend.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing Review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        return view('backend.reviews.edit', compact('review'));
    }

    /**
     * Update Review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::findOrFail($id);
        $review->update($request->all());

        return redirect()->route('admin.reviews.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove Review from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Approve a review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->approved = 1;
        $review->save();

        return redirect()->route('admin.reviews.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }
}
