<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\Datatables\Datatables;
use Auth;
use Carbon\Carbon;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      try {
        return view('admin.members.index');
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }
    }

    public function getData() {
      $members = User::latest()->get();

  		return Datatables::of($members)
  			->addColumn('action', function ($member) {
                  return '<a href="'.route('admin.members.show', $member->id).'" title="Show member details" class="btn btn-xs btn-flat btn-info"><i class="glyphicon glyphicon-eye-open"></i> Show</a>
                  <a href="'.route('admin.members.edit', $member->id).'" title="Edit Member" class="btn btn-xs btn-flat btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
  				        <a href="#" class="btn btn-xs btn-flat btn-danger btn-delete" data-remote="'. route('admin.members.destroy', $member->id) .'" data-table="members-table" title="Delete Member"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
        })
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
        return view('admin.members.create');
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
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required|string|min:8|confirmed',
        ],
        [
          'name.required' => 'Please enter name',
          'email.required' => 'Please enter email address',
        ]);

        $member = new User();
        $member->name = $request->input('name');
        $member->email = $request->input('email');
        $member->password = bcrypt($request->input('new_password'));
        $member->joined_at = Carbon::now();
        $member->last_login_at = Carbon::now();
        $member->last_ip = $_SERVER['REMOTE_ADDR'];
        $member->status = $request->input('status');

        if (!$member->save()) {
          return redirect()->route('amdin.members.create')->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect()->route('admin.members.index')->with('success', 'Member added!');
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
        $member = User::findOrFail($id);
        return view('admin.members.show', compact('member'));
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
        $member = User::findOrFail($id);
        return view('admin.members.edit', compact('member'));
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
          'name' => 'required',
          'email' => 'required|email|unique:users,email,'.$id,
        ],
        [
          'name.required' => 'Please enter first name',
          'email.required' => 'Please enter email address',
        ]);
        
        $member = User::findOrFail($id);
        $member->name = $request->input('name');
        $member->email = $request->input('email');
        $member->updated_at = Carbon::now();
        $member->status = $request->input('status');

        if ($request->input('change_password')) {
          $this->validate($request, [
              'new_password' => 'required|string|min:6|confirmed',
          ]);
          $member->password = bcrypt($request->input('new_password'));
        }

        if (!$member->save()) {
          return redirect()->route('admin.members.edit', $id)->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect()->route('admin.members.index')->with('success', 'Member updated!');
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
        if (!User::destroy($id)) {
          return redirect()->route('admin.members.index')->with('error', 'Something went wrong!');
        }
      } catch (Exception $e) {
      	Exceptions::exception($e);
      }

      return redirect()->route('admin.members.index')->with('success', 'Member deleted!');
    }
}
