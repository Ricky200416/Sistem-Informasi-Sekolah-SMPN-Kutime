<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudyClassAssignment;
use App\Models\StudyGroup;
use App\Models\StudySubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudyClassAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $assignments = StudyClassAssignment::with(['teacher', 'studySubject', 'studyGroup'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->paginate(20);

        return view('admin.study-class-assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teachers      = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();
        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $studyGroups   = StudyGroup::where('is_active', true)->orderBy('grade')->orderBy('section')->get();

        return view('admin.academic-planner.create-study-class-assignment', compact('teachers', 'studySubjects', 'studyGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'academic_year'    => 'required|string|max:9',
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Cek duplikat assignment
        $exists = StudyClassAssignment::where([
            'teacher_id'       => $validated['teacher_id'],
            'study_subject_id' => $validated['study_subject_id'],
            'study_group_id'   => $validated['study_group_id'],
            'academic_year'    => $validated['academic_year'],
            'semester'         => $validated['semester'],
        ])->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['teacher_id' => 'Assignment ini sudah ada untuk semester yang dipilih.']);
        }

        StudyClassAssignment::create($validated);

        return redirect()->route('academic-planner.index')
            ->with('success', 'Assignment guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudyClassAssignment $studyClassAssignment): View
    {
        return view('admin.study-class-assignments.show', compact('studyClassAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyClassAssignment $studyClassAssignment): View
    {
        $teachers      = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();
        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $studyGroups   = StudyGroup::where('is_active', true)->orderBy('grade')->orderBy('section')->get();

        return view('admin.academic-planner.edit-study-class-assignment', compact('studyClassAssignment', 'teachers', 'studySubjects', 'studyGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyClassAssignment $studyClassAssignment): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'academic_year'    => 'required|string|max:9',
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        $studyClassAssignment->update($validated);

        return redirect()->route('academic-planner.index')
            ->with('success', 'Assignment guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyClassAssignment $studyClassAssignment): RedirectResponse
    {
        $studyClassAssignment->delete();

        return redirect()->route('academic-planner.index')
            ->with('success', 'Assignment guru berhasil dihapus.');
    }

    /**
     * Assign teacher to subject and group (AJAX endpoint)
     */
    public function assignTeacher(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'academic_year'    => 'required|string|max:9',
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Cek duplikat
        $exists = StudyClassAssignment::where([
            'teacher_id'       => $validated['teacher_id'],
            'study_subject_id' => $validated['study_subject_id'],
            'study_group_id'   => $validated['study_group_id'],
            'academic_year'    => $validated['academic_year'],
            'semester'         => $validated['semester'],
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Assignment ini sudah ada.');
        }

        StudyClassAssignment::create($validated);

        return back()->with('success', 'Guru berhasil diassign ke mata pelajaran.');
    }
}
