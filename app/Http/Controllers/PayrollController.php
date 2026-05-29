<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayroleRequest;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Requests\UpdatePayrollRequest;
use App\Service\Hr\Payroll\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;
    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payroll = $this->payrollService->index();
        return response()->json([
            "message" => 'success',
            "data" => $payroll
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayrollRequest $request)
    {

     
     $payroll = $this->payrollService->store($request->validated());

        return response()->json([
            'message' => 'Payroll created successfully',
            'payroll' => $payroll,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payroll = $this->payrollService->show($id);
        return $payroll;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayrollRequest $request, string $id)
    {
        
        $payroll = $this->payrollService->update($request->validated(), $id);

        return response()->json([
            'message' => 'payroll updated successfully',
            'payroll' => $payroll,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->payrollService->delete($id);

        return response()->json([
            'message' => 'Payroll deleted successfully',
        ]);
    }
}
