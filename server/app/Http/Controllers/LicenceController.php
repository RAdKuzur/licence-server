<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicenceCreateRequest;
use App\Http\Requests\LicenceRequest;
use App\Services\LicenceService;

class LicenceController extends Controller
{
    private LicenceService $licenceService;
    public function __construct(
        LicenceService $licenceService
    )
    {
        $this->licenceService = $licenceService;
    }
    public function all()
    {
        $data = $this->licenceService->all();
        return view('licences')->with('data', $data);
    }
    public function create(LicenceCreateRequest $request)
    {
        $this->licenceService->create($request->toDTO());
        return redirect('http://localhost:81/licences');
    }
    public function licence(LicenceRequest $request) {
        $appKey = $request->validated('app_key');
        $licenceKey = $request->validated('licence_key');
        $licence = $this->licenceService->licenceCheck($appKey, $licenceKey);
        return response()->json([
            'success' => true,
            'message' => $licence['message'],
            'data' => $licence['data']
        ], $licence['code']);
    }
    public function revoke($id){
        $this->licenceService->revoke($id);
        return redirect('http://localhost:81/licences');
    }
}
