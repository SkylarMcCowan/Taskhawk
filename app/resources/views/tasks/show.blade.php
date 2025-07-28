<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.edit', $task) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Task
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" 
                      method="POST" 
                      class="inline" 
                      onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Task
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Task Details -->
                        <div class="md:col-span-2">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Task Details</h3>
                                
                                @if($task->due_date)
                                    <div class="mb-4">
                                        <span class="font-medium text-gray-700">Due Date:</span>
                                        <span class="ml-2">{{ $task->due_date->format('M j, Y') }}</span>
                                        @if($task->due_date->isPast())
                                            <span class="ml-2 text-red-500 font-semibold">(Overdue)</span>
                                        @elseif($task->due_date->isToday())
                                            <span class="ml-2 text-orange-500 font-semibold">(Due Today)</span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($task->notes)
                                    <div class="mb-4">
                                        <span class="font-medium text-gray-700">Notes:</span>
                                        <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                            {{ $task->notes }}
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="text-sm text-gray-500">
                                    <p>Created: {{ $task->created_at->format('M j, Y g:i A') }}</p>
                                    @if($task->updated_at != $task->created_at)
                                        <p>Updated: {{ $task->updated_at->format('M j, Y g:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attachments -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Attachments</h3>
                            
                            @if($task->attachments->count() > 0)
                                <div class="space-y-2">
                                    @foreach($task->attachments as $attachment)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <div>
                                                    <a href="{{ asset('storage/' . $attachment->filepath) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        {{ $attachment->filename }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $attachment->created_at->format('M j, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <form action="{{ route('tasks.attachments.destroy', [$task, $attachment]) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Remove this attachment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-500 hover:text-red-700 text-xs">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No attachments</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('tasks.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Back to Tasks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
