<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CoilCollection;
use App\coil_detail;

class coilController extends Controller
{
    public function index()
    {
        $coil = coil_detail::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $coil = $coil->where('serial_Code', 'LIKE', '%' . request()->q . '%');
        }
        return new CoilCollection($coil->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'item_category' => 'required|exists:coils,code',
            'item_type' => 'required|string|max:100',
            'item_code' => 'required|string',
            'item_description' => 'required|max:13',
            'serial_Code' => 'required|string',
            'id_coil' => 'required|string',
            'balance' => 'required|string',
        ]);

        coil_detail::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $coil = coil_detail::whereid($id)->first();
        return response()->json(['status' => 'success', 'data' => $coil], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'item_category' => 'required',
            'item_type' => 'required|max:100',
            'item_code' => 'required',
            'item_description' => 'required|max:13',
            'serial_Code' => 'required|exists:coil_details,serial_Code',
            'id_coil' => 'required|',
            'balance' => 'required|',

        ]);

        $coil = coil_detail::whereid($id)->first();
        $coil->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $coil = coil_detail::find($id);
        $coil->delete();
        return response()->json(['status' => 'success'], 200);
    }

}
