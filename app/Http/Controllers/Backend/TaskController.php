<?php

namespace App\Http\Controllers\Backend;

use App\Constants\TaskPriority;
use App\Constants\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\FileManageTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        // Define permissions for each action
        $permissions = [
            'store' => 'order-manage',
            'edit' => 'order-manage',
            'update' => 'order-manage',
            'statusUpdate' => 'order-manage',
            'taskShow' => 'order-manage',
            'taskFile' => 'order-manage',
            'destroy' => 'order-manage',
        ];
        $this->permissionAuthorization($permissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required',
            'name' => 'required|unique:tasks|max:255',
            'description' => 'required',
            'attachment' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
            'due_date' => 'required|date',
            'priority' => 'required|in:'.implode(',', TaskPriority::TYPE),  // Assuming you have specific priority levels
            'status' => 'required|in:'.implode(',', TaskStatus::STATUS), // Assuming predefined statuses
            'info_fields' => 'nullable|array',
        ]);

        $taskData = $validated;  // Store validated data
        $taskData['start_date'] = today();

        if ($request->hasFile('attachment')) {
            $taskData['attachment'] = $this->uploadFile($request->file('attachment'));
        }

        Task::create($taskData);  // Mass assignment to simplify creation

        notifyEvs('success', 'Task created successfully');

        return redirect()->back();
    }

    public function edit($id)
    {
        $task = Task::find($id);

        return view('backend.order.task.edit_append', compact('task'))->render();
    }

    public function update(Request $request, $id)
    {
        $taskData = $request->validate([
            'name' => 'required|unique:tasks,name,'.$id.'|max:255',
            'description' => 'required',
            'attachment' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
            'due_date' => 'required|date',
            'priority' => 'required|in:'.implode(',', TaskPriority::TYPE),  // Assuming you have specific priority levels
            'status' => 'required|in:'.implode(',', TaskStatus::STATUS), // Assuming predefined statuses
            'progress' => 'required|min:0|max:100',
            'info_fields' => 'nullable|array',
        ]);

        if (isset($taskData['info_fields'])) {
            $taskData['info_fields'] = array_values($taskData['info_fields']);
        } else {
            $taskData['info_fields'] = [];
        }

        $task = Task::findOrFail($id);
        if ($request->hasFile('attachment')) {
            $taskData['attachment'] = $this->uploadFile($request->file('attachment'), $task->attachment);
        }
        $task->fill($taskData);
        $task->save();

        notifyEvs('success', 'Task updated successfully');

        return redirect()->back();
    }

    public function statusUpdate(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|string',
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->save();

        return response()->json(['success' => true, 'message' => 'Task status updated successfully']);
    }

    public function taskShow($id)
    {
        $taskPriority = [
            'low' => 'primary',
            'medium' => 'warning',
            'high' => 'danger',
        ];

        $taskStatus = [
            'pending' => 'secondary',
            'in_progress' => 'info',
            'completed' => 'success',
        ];

        $task = Task::find($id);

        return view('backend.order.task.show_append', compact('task', 'taskPriority', 'taskStatus'))->render();
    }

    public function taskFile($id)
    {
        $task = Task::findOrFail($id);

        $path = 'assets/'.$task->attachment;

        if (! file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($path);

    }

    public function fileDownload()
    {
        $filename = request('filename');
        $path = 'assets/'.$filename;

        if (! file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->download($path);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task->attachment) {
            $this->deleteFile($task->attachment);
        }
        $task->delete();
        notifyEvs('success', 'Task deleted successfully');

        return redirect()->back();
    }
}
