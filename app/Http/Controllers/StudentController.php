<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\StudentResource;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $studentsQuery = Student::search($request);

        $classes = ClassesResource::collection(Classes::all());

        $students = StudentResource::collection($studentsQuery->paginate(8));
        
        return Inertia('Students/Index', [
            'students' => $students,
            'searchvalue' => $request->search ?? '',
            'classes' => $classes,
            'class_id' => $request->class_id ?? '',
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassesResource::collection(Classes::all());
        
        return Inertia('Students/Create', [
            'classes' => $classes,
          
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        Student::create($request->validated());
        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {

        $classes = ClassesResource::collection(Classes::all());
        
        return Inertia('Students/Edit', [
            'classes' => $classes,
            'student' => StudentResource::make($student)
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
       
    }
}
