<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->with('attachments')->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $task = Auth::user()->tasks()->create([
            'name' => $request->name,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('attachments', 'public');
                
                $task->attachments()->create([
                    'filename' => $originalName,
                    'filepath' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load('attachments');
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $task->update([
            'name' => $request->name,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('attachments', 'public');
                
                $task->attachments()->create([
                    'filename' => $originalName,
                    'filepath' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        // Delete associated files
        foreach ($task->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->filepath);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Remove attachment from task.
     */
    public function removeAttachment(Task $task, Attachment $attachment)
    {
        $this->authorize('update', $task);

        if ($attachment->task_id !== $task->id) {
            abort(404);
        }

        Storage::disk('public')->delete($attachment->filepath);
        $attachment->delete();

        return back()->with('success', 'Attachment removed successfully!');
    }
}
