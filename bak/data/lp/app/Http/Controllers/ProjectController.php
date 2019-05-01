<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Yajra\Datatables\Datatables;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      try {
        $projects = Project::with('photos')->latest()->get();
        return view('projects.index');
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }
    }

    public function getData() {
      $projects = Project::with('photos')->latest()->get();

  		return Datatables::of($projects)
        ->addColumn('photos_count', function($project) {
          return count($project->photos);
        })
        ->addColumn('images', function($project) {
          return  '<a href="'.route('photos.index', $project->id).'" title="Images" class="btn btn-xs btn-flat btn-info"><i class="glyphicon glyphicon-picture"></i> View</a>';
        })
  			->addColumn('action', function ($project) {
                  return '<a href="'.route('projects.show', $project->id).'" title="Print" class="btn btn-xs btn-flat btn-warning"><i class="glyphicon glyphicon-print"></i> Print</a>
                  <a href="'.route('projects.edit', $project->id).'" title="Edit Project" class="btn btn-xs btn-flat btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
  				        <a href="#" class="btn btn-xs btn-flat btn-danger btn-delete" data-remote="'. route('projects.destroy', $project->id) .'" data-table="projects-table" title="Delete Project"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
        })
        ->rawColumns(['images', 'action'])
  		->make(true);
  	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      try {
        return view('projects.create');
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
        $this->validate($request, [
          'code' => 'required',
          'name' => 'required',
          'title' => 'required',
          'address' => 'required',
          'city' => 'required',
          'state' => 'required',
          'country' => 'required',
          'postal_code' => 'required',
        ],
        [
          'code.required' => 'Please enter project code',
          'name.required' => 'Please enter project name',
          'title.required' => 'Please enter project title',
          'address.required' => 'Please enter address',
          'city.required' => 'Please enter city',
          'state.required' => 'Please enter state',
          'country.required' => 'Please enter country',
          'postal_code.required' => 'Please enter postal code',
        ]);

        $project = Project::create($request->all());

        if (!$project) {
          return redirect()->route('projects.create')->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect('projects')->with('success', 'Project added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
          $project = Project::with('photos')->findOrFail($id);
          return view('projects.show', compact('project', $project));
        } catch (Exception $e) {
        	Exceptions::exception($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {//dd($id);
      //dd('heheh');
      //return view('projects.print');

        try {
          $fields = array();
          $id = $request->input('project_id');
          $project = Project::with('photos')->findOrFail($id);
          //dd($project);

          foreach ($request->all() as $key => $value) {
            if ($key == '_token' || $key == 'project_id') {
              continue;
            }
            //$fields[$key] = $value;
            array_push($fields, $key);
          }
          //dd($fields);
          $result = array();
          foreach ($project->photos as $key => $photo) {
            $temp = array();
            //$temp['image_url'] = $photo->image_url;

            $filename = pathinfo($photo->image_url, PATHINFO_FILENAME);
            $extension = pathinfo($photo->image_url, PATHINFO_EXTENSION);
            $thumbname = $filename.'-large.'.$extension;
            $temp['image_url'] = $thumbname;
            //dd($thumbname);

            $temp2 = array();
            foreach ($fields as $field_name) {

              if ($field_name == 'area') {
                $t = array('label' => 'Area', 'value' => $photo->area);
                array_push($temp2, $t);
              }
              if ($field_name == 'floor') {
                $t = array('label' => 'Floor', 'value' => $photo->floor);
                array_push($temp2, $t);
              }
              if ($field_name == 'building') {
                $t = array('label' => 'Building', 'value' => $photo->building);
                array_push($temp2, $t);
              }
              if ($field_name == 'unit') {
                $t = array('label' => 'Unit', 'value' => $photo->unit);
                array_push($temp2, $t);
              }
              if ($field_name == 'probe_location') {
                $t = array('label' => 'Probe Location', 'value' => $photo->probe_location);
                array_push($temp2, $t);
              }
              if ($field_name == 'probe_number') {
                $t = array('label' => 'Probe Number', 'value' => $photo->probe_number);
                array_push($temp2, $t);
              }
              if ($field_name == 'caption') {
                $t = array('label' => 'Probe Number', 'value' => $photo->probe_number);
                array_push($temp2, $t);
              }

            }
            $temp['data'] = $temp2;
            //array_push($temp, $temp2);
            array_push($result, $temp);

          }

          //dd($result);
          return view('projects.print', compact('project', 'result'));
        } catch (Exception $e) {
        	Exceptions::exception($e);
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
      try {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }
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
      try {
        $this->validate($request, [
          'code' => 'required',
          'name' => 'required',
          'title' => 'required',
          'address' => 'required',
          'city' => 'required',
          'state' => 'required',
          'country' => 'required',
          'postal_code' => 'required',
        ],
        [
          'code.required' => 'Please enter project code',
          'name.required' => 'Please enter project name',
          'title.required' => 'Please enter project title',
          'address.required' => 'Please enter address',
          'city.required' => 'Please enter city',
          'state.required' => 'Please enter state',
          'country.required' => 'Please enter country',
          'postal_code.required' => 'Please enter postal code',
        ]);

        $project = Project::findOrFail($id);
        if (!$project->update($request->all())) {
          return redirect()->route('projects.edit', $id)->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect('projects')->with('success', 'Project updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try {
        if (!Project::destroy($id)) {
          return redirect()->route('projects.index')->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect('projects')->with('success', 'Project deleted!');
    }
}
