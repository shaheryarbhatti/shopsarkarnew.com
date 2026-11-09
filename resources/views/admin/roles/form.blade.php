<x-admin :title="$role->exists ? 'Edit Role' : 'Add Role'" active="roles">

    <form method="POST" action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}" class="card max-w-3xl p-6"
          x-data="{ all: @js($assigned) }">
        @csrf
        @if($role->exists) @method('PUT') @endif
        @if($errors->any())<div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>@endif

        <div class="mb-6">
            <label class="label">Role Name *</label>
            <input name="name" class="input max-w-sm" required value="{{ old('name', $role->name) }}"
                   @if(in_array($role->name, ['customer','contributor'])) readonly @endif>
            @if(in_array($role->name, ['customer','contributor']))
                <p class="mt-1 text-xs text-gray-400">This is a system role; name is locked.</p>
            @endif
        </div>

        <h2 class="mb-3 font-serif text-lg font-semibold">Permissions</h2>
        <div class="space-y-5">
            @foreach($groups as $gkey => $group)
                <div class="rounded-lg border border-gray-100 p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="font-semibold text-ink">{{ $group['label'] }}</p>
                        <button type="button" class="text-xs text-gold-600 hover:underline"
                                @click="@foreach($group['permissions'] as $pk=>$pl) if(!all.includes('{{ $pk }}')) all.push('{{ $pk }}'); @endforeach">Select all</button>
                    </div>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach($group['permissions'] as $pkey => $plabel)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $pkey }}" class="rounded text-gold-500"
                                       :checked="all.includes('{{ $pkey }}')"
                                       @change="$event.target.checked ? all.push('{{ $pkey }}') : all.splice(all.indexOf('{{ $pkey }}'),1)">
                                <span>{{ $plabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-gold group"><span>{{ $role->exists ? 'Update' : 'Create' }} Role</span></button>
            <a href="{{ route('admin.roles.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>

</x-admin>
