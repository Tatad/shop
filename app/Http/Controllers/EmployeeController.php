<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Shop;
use App\Service;
use App\EmployeeService;
use Validator;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        
        return view('employee.index');
    }

    public function lists(){
        //
        // $employees = Employee::with('employee_services.services','shop')->get();
        // return response()->json($employees);
      $employees = Employee::with('employee_services.services','shop')->get()->toArray();
      //$shops = Shop::all();
      $result = [];
      foreach($employees as $employee){
        //$result[] = $shop;

        $employee_services = [];
        foreach($employee['employee_services'] as $service){
          $employee_services[] = $service;
        }

        $result[] = [
          '_id' => $employee['_id'],
          'first_name' => $employee['first_name'],
          'last_name' => $employee['last_name'],
          'middle_name' => $employee['middle_name'],
          'birth_date' => $employee['birth_date'],
          'experience_years' => $employee['experience_years'],
          'shop' => $employee['shop']['name'],
          'service' => $employee_services,
          'shop_id' => $employee['shop_id'],
        ];
      }

      return response()->json($result);
    }

    public function shops(){
        $shops = Shop::all()->toArray();
        
        return response()->json($shops);
    }

    public function services(){
        $services = Service::all()->toArray();
        
        return response()->json($services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_add_shop_rules(array $data)
    {
      
      $validator = Validator::make($data, [
        'first_name' => 'required|max:25',
        'last_name' => 'required',
        'middle_name' => 'required|max:25',  
      ]);

      return $validator;
    }

    public function create(Request $request)
    {
        //dd($request->all());
        //
        $validator = $this->admin_add_shop_rules($request->all());
        if($validator->fails())
        {
          return response()->json(array('error' => $validator->getMessageBag()->toArray()));
        }
        else
        {   
            $employee = new Employee([
              'first_name' => $request->get('first_name'),
              'last_name' => $request->get('last_name'),
              'middle_name' => $request->get('middle_name'),
              'birth_date' => $request->get('birth_date'),
              'experience_years' => $request->get('experience_years'),
              'shop_id' => $request->get('shop_id'),
              // 'district' => $request->get('district'),
              // 'open_time' => $request->get('open_time'),
              // 'close_time' => $request->get('close_time'),
              // 'website' => $request->get('website'),
            ]);
            $employee->save();  

            foreach($request->service as $service){
                $data = [
                    'employee_id' => $employee->_id,
                    'service_id' => $service
                ];
                $result = EmployeeService::create($data);
            }

            $response = [
                'Employee' => $employee,
                'Service' => $result
            ];

          return $response;
        }  
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required'
        ]);
        $input = $request->all();
        
        $employee->fill($input)->save();

        $result = [
            'Employee' => $employee,
        ];

        EmployeeService::where('employee_id', $id)->delete();
        foreach($request->service as $service){
            $data = [
                'employee_id' => $employee->_id,
                'service_id' => $service
            ];
            $result = EmployeeService::create($data);
        }

        return $result;
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
        $employee = Employee::find($id);
        $employee->delete();

        EmployeeService::where('employee_id', $id)->delete();
        return $employee;
    }
}
