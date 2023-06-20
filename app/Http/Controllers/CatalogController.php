<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalog;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    public function slugify($text)
    {
        // Strip html tags
        $text = strip_tags($text);
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterate
        setlocale(LC_ALL, 'en_US.utf8');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, '-');
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // Lowercase
        $text = strtolower($text);
        // Check if it is empty
        if (empty($text)) {
            return 'n-a';
        }
        // Return result
        return $text;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        $request->validate([
            'file' => 'required',
        ]);

        $catalog = new Catalog();
        $catalog->name = $request->name;
        $catalog->slug = $request->slug;
        $catalog->slug = Catalogontroller::slugify($request->name);
        $catalog->description = $request->description;

        if ($request->hasFile('file')) {
            $dash_catalog = "-";
            $dateCreated = Carbon::now()->timestamp;

            $filename = $request->file->getClientOriginalName();
            $newfilename = $dateCreated . $dash_catalog . $filename;
            //   dd($newfilename);
            $catalog['main_image'] = $newfilename;
            $request->file->storeAs('catalogs', $newfilename, 'public_uploads');
        }

        $check = DB::table('catalogs')
            ->where('slug', $catalog->slug)
            ->first();

        if ($check == null) {
            // dd("Not duplciate name");
            $dash = "-";
            $newslug = $catalog->slug;
            $catalog->slug = $newslug;
            $catalog->save();

            notify()->success('Catalog Successfully Created');
            return redirect()->back();
        } else {
            $dash = "-";
            $newslug = $catalog->slug;
            $catalog->slug = $newslug;
            //dd($newslug);
            $catalog->save();
            notify()->success('Catalog Successfully Created');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catalog = Catalog::findOrFail($id);

        return view('show-catalog')->withCatalog($catalog);
    }

    public function catalogs()
    {
        $catalogs = Catalog::orderBy('name', 'asc')->paginate(12);

        return view('catalogs')->withCatalogs($catalogs);
    }

    public function getCatalogs(Request $request)
    {
        if ($request->ajax()) {
            $data = Catalog::all();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<a href="/catalogs/view/' . $row->id . '" class="btn btn-success">View</a>';
                })
                ->addColumn('edit', function ($row) {
                    return '<a href="/catalog/' . $row->id . '/edit" class="btn btn-info">Edit</a>';
                })
                ->addColumn('active', function ($row) {
                    if ($row->active == 1) {
                        $form = '<form style=" margin-bottom: 0;" action="/catalogs/deactivate/' . $row->id . '" method="POST">';
                        $form .= csrf_field();
                        $form .= '<input type="submit" class="btn btn-primary" value="Deactivate" /></form>';
                        return $form;
                    }

                    if ($row->active == 0) {
                        $form = '<form style=" margin-bottom: 0;" action="/catalogs/activate/' . $row->id . '" method="POST">';
                        $form .= csrf_field();
                        $form .= '<input type="submit" class="btn btn-primary" style="background:#59a4a0;border-color:#59a4a0;" value="Activate" /></form>';
                        return $form;
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('main_image', function ($row) {
                    $logopath = asset('/uploads/catalogs') . '/' . $row->main_image;
                    $logo = '<img src="' . $logopath . '"/>';
                    return '<img class="img-fluid" style="width:150px;" src="/uploads/catalogs/' . $row->main_image . '"/>';
                })
                ->rawColumns(['main_image' => 'main_image', 'edit' => 'edit', 'active' => 'active', 'action' => 'action'])
                ->make(true);

            return view('catalogs');
            // return Datatables::of(User::query())->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catalog = Catalog::findOrFail($id);

        return view('edit-catalogs')->withCatalog($catalog);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);
        $catalog->name = $request->name;
        $catalog->slug = $request->slug;
        $catalog->slug = CatalogController::slugify($request->slug);

        if ($request->hasFile('file')) {
            $dash_catalog = "-";
            $dateCreated = Carbon::now()->timestamp;

            $filename = $request->file->getClientOriginalName();
            $newfilename = $dateCreated . $dash_catalog . $filename;
            //   dd($newfilename);
            $catalog['main_image'] = $newfilename;
            $request->file->storeAs('catalogs', $newfilename, 'public_uploads');
        }

        //dd($request);

        $catalog->save();

        notify()->success('Catalog Successfully Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
