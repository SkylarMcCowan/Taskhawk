<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Tasks') }}
            </h2>
            <a href="{{ route('tasks.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Task
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tasks->count() > 0)
                        <div class="grid gap-4">
                            @foreach($tasks as $task)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold mb-2">
                                                <a href="{{ route('tasks.show', $task) }}" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    {{ $task->name }}
                                                </a>
                                            </h3>
                                            
                                            @if($task->due_date)
                                                <p class="text-sm text-gray-600 mb-2">
                                                    <span class="font-medium">Due:</span> 
                                                    {{ $task->due_date->format('M j, Y') }}
                                                    @if($task->due_date->isPast())
                                                        <span class="text-red-500 font-semibold">(Overdue)</span>
                                                    @elseif($task->due_date->isToday())
                                                        <span class="text-orange-500 font-semibold">(Due Today)</span>
                                                    @endif
                                                </p>
                                            @endif
                                            
                                            @if($task->notes)
                                                <p class="text-gray-700 text-sm mb-2">
                                                    {{ Str::limit($task->notes, 100) }}
                                                </p>
                                            @endif
                                            
                                            @if($task->attachments->count() > 0)
                                                <p class="text-xs text-gray-500">
                                                    📎 {{ $task->attachments->count() }} attachment(s)
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('tasks.edit', $task) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                            <form action="{{ route('tasks.destroy', $task) }}" 
                                                  method="POST" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">You haven't created any tasks yet.</p>
                            <a href="{{ route('tasks.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Your First Task
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
