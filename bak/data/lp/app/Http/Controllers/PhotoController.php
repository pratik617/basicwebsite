<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Image;
use Carbon\Carbon;
use File;

class PhotoController extends Controller
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
    public function index($project_id)
    {
      try {
        $project = Project::where('id', $project_id)->with('photos')->first();

        if ($project == null) {
          abort(404);
        } else {
          return view('photos.index', compact('project'));
        }
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
      try {
        $project = Project::findOrFail($project_id);
        return view('photos.create', compact('project'));
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
          'area' => 'required',
          'floor' => 'required',
          'building' => 'required',
          'unit' => 'required',
          'probe_location' => 'required',
          'probe_number' => 'required',
          'photos' => 'required',
        ],
        [
          'area.required' => 'Please enter area',
          'floor.required' => 'Please enter floor',
          'building.required' => 'Please enter building',
          'unit.required' => 'Please enter unit',
          'probe_location.required' => 'Please enter probe location',
          'probe_number.required' => 'Please enter probe number',
          'photos.required' => 'Please upload photos',
        ]);

        if ($request->hasFile('photos')) {
          foreach ($request->file('photos') as $key => $photo_item) {
            $file_temp_path = $photo_item->getPathName();
            $file_properties = @exif_read_data($file_temp_path);

            $file = $photo_item->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = pathinfo($file, PATHINFO_EXTENSION);


            $imagename_without_extension = $filename.'-'.time();
            $imagename = $imagename_without_extension.'.'.$extension;

            $photo = new Photo;
            $photo->project_id = $request->input('project_id');
            $photo->area = $request->input('area');
            $photo->floor = $request->input('floor');
            $photo->building = $request->input('building');
            $photo->unit = $request->input('unit');
            $photo->probe_location = $request->input('probe_location');
            $photo->probe_number = $request->input('probe_number');
            $photo->image_url = $imagename;
            $photo->orientation = (isset($file_properties['Orientation']))?$file_properties['Orientation']:null;

            // count & set display order
            $photos_count = Photo::where('project_id', $request->input('project_id'))->count();
            $display_order = 1;
            if ($photos_count>0) {
              $display_order = $photos_count + 1;
            }
            $photo->display_order = $display_order;

            $photo->captured_at = (isset($file_properties['DateTimeOriginal']))?$file_properties['DateTimeOriginal']:null;
            if (!$photo->save()) {
                return redirect()->route('photos.create', $request->input('project_id'))->with('error', 'Something went wrong!');
            }

            // Move uploaded file to storage directory
            $directory = 'uploads/'.env('PROJECT_PHOTOS_DIR').'/'.$photo->project_id;
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            //Storage::makeDirectory($directory);
            //$photo_item->storeAs($directory, $imagename);

            // Start thumb
            $thumb_directory = $directory.'/'.env('THUMB_DIR');
            if (!file_exists($thumb_directory)) {
                mkdir($thumb_directory, 0777, true);
            }

            $thumbs_size = Project::project_thumbs;

            foreach ($thumbs_size as $thumb_type => $thumb_size) {
              $thumb = Image::make($photo_item->getRealPath())->resize($thumb_size['width'], $thumb_size['height']);
              //dd($thumb);
              $thumbname = $imagename_without_extension.'-'.$thumb_type.'.'.$extension;
              //$thumb->save(env('STORAGE_DIR').'/'.$thumb_directory.'/'.$thumbname);
              $thumb->save($thumb_directory.'/'.$thumbname);
            }
            // End thumb

            $photo_item->move(public_path($directory), $imagename);

            /*
            $img_test = Image::make($photo_item->getRealPath());
            $img_test->save('storage/'.$directory.'/'.$imagename);
            $img_test->destroy();
            dd('tetet');
            */
            // thumb
            /*
            $thumb_directory = $directory.'/'.env('THUMB_DIR');
            Storage::makeDirectory($thumb_directory);

            $thumbs_size = Project::project_thumbs;
            foreach ($thumbs_size as $thumb_type => $thumb_size) {
              //dd('tetete');
              $thumb = Image::make($photo_item->getRealPath())->resize($thumb_size['width'], $thumb_size['height']);
              //dd($thumb);
              $thumbname = $imagename_without_extension.'-'.$thumb_type.'.'.$extension;
              $thumb->save(env('STORAGE_DIR').'/'.$thumb_directory.'/'.$thumbname);
            }*/
          }
        } else {
          return redirect()->route('photos.create', $request->input('project_id'))->with('error', 'Something went wrong!');
        }

      } catch (Exception $e) {
          Exceptions::exception($e);
      }
      return redirect()->route('photos.index', $request->input('project_id'))->with('success', 'Photos added!');
    }

    public function storeOrder(Request $request) {
      try {
        if (is_array($request->input('idsInOrder'))) {
          $table = Photo::getModel()->getTable();

          $cases = [];
          $ids = [];
          $params = [];

          foreach ($request->input('idsInOrder') as $display_index => $photo_id) {
              $photo_id = (int) $photo_id;
              $cases[] = "WHEN {$photo_id} then ?";
              $params[] = $display_index+1;
              $ids[] = $photo_id;
          }

          $ids = implode(',', $ids);
          $cases = implode(' ', $cases);
          $params[] = Carbon::now();

          $result = \DB::update("UPDATE `{$table}` SET `display_order` = CASE `id` {$cases} END, `updated_at` = ? WHERE `id` in ({$ids})", $params);
          $response = 1;
          if (!$result) {
            $response = 0;
          }

          echo $response;
        }
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        try {
          $photo = Photo::findOrFail($id);
          if ($request->input('caption') != null || $request->input('caption') != '') {
            $photo->caption = $request->input('caption');
            $photo->save();
          }

          if ($request->input('pic_rotate')) {
            $directory = 'uploads/'.env('PROJECT_PHOTOS_DIR').'/'.$photo->project_id;

            $filename = pathinfo($photo->image_url, PATHINFO_FILENAME);
            $extension = pathinfo($photo->image_url, PATHINFO_EXTENSION);

            $image_url = public_path($directory.'/'.$photo->image_url);
            $img = Image::make($image_url);
            $img->rotate($request->input('pic_rotate'));
            $img->save($image_url);

            // START thumb
            $thumb_directory = $directory.'/'.env('THUMB_DIR');
            $thumbs_size = Project::project_thumbs;
            foreach ($thumbs_size as $thumb_type => $thumb_size) {
              $thumb = Image::make($image_url)->resize($thumb_size['width'], $thumb_size['height']);
              $thumbname = $filename.'-'.$thumb_type.'.'.$extension;
              $thumb->save(public_path($thumb_directory.'/'.$thumbname));
            }
            // END thumb
          }

          return redirect()->route('photos.index', $photo->project_id)->with('success', 'Photo updated!');
        } catch (Exception $e) {
        	Exceptions::exception($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      try {
        $photo = Photo::findOrFail($request->input('photoId'));
        $directory = 'uploads/'.env('PROJECT_PHOTOS_DIR').'/'.$photo->project_id;

        // Start thumb delete
        $thumb_directory = $directory.'/'.env('THUMB_DIR');
        $filename = pathinfo($photo->image_url, PATHINFO_FILENAME);
        $extension = pathinfo($photo->image_url, PATHINFO_EXTENSION);

        $thumbs_size = Project::project_thumbs;
        foreach ($thumbs_size as $thumb_type => $thumb_size) {
          $thumbname = $filename.'-'.$thumb_type.'.'.$extension;
          //$thumb_url = $thumb_directory.'/'.$thumbname;
          $thumb_url = $thumb_directory.'/'.$thumbname;
          //Storage::delete($thumb_url);
          if(File::exists($thumb_url)) {
              File::delete($thumb_url);
          }
        }

        // Start original delete
        $image_url = $directory.'/'.$photo->image_url;
        //Storage::delete($image_url);
        if(File::exists($image_url)) {
            File::delete($image_url);
        }

        $response = 1;
        if(!$photo->delete()) {
          $response = 0;
        }
        echo $response;
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }
    }


}
