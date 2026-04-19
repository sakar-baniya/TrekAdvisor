@props(['hotel' => null])

@php
    $rooms = old('rooms', $hotel ? $hotel->rooms->toArray() : [['room_type' => '', 'price_per_night' => '', 'total_rooms' => '']]);
@endphp

<div x-data="{ 
    rooms: {{ json_encode($rooms) }},
    addRoom() {
        this.rooms.push({ room_type: '', price_per_night: '', total_rooms: '' });
    },
    removeRoom(index) {
        if (this.rooms.length > 1) {
            this.rooms.splice(index, 1);
        }
    }
}" class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <i class="fas fa-bed text-slate-400"></i>
            Manage Room Types
        </h3>
        <button type="button" @click="addRoom()" class="px-4 py-2 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
            <i class="fas fa-plus mr-2"></i> Add Room Type
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <template x-for="(room, index) in rooms" :key="index">
            <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm relative group animate-in fade-in slide-in-from-top-2 duration-300">
                <!-- Delete Button -->
                <button type="button" 
                    x-show="rooms.length > 1"
                    @click="removeRoom(index)" 
                    class="absolute -top-2 -right-2 w-8 h-8 bg-white border border-slate-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-100 transition-all shadow-sm z-10">
                    <i class="fas fa-trash-can text-xs"></i>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- ID (Hidden) -->
                    <input type="hidden" x-bind:name="'rooms[' + index + '][id]'" x-model="room.id">

                    <!-- Room Type -->
                    <div class="space-y-2">
                        <x-ui.input-label value="Room Type *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                        <x-ui.text-input type="text" 
                            x-bind:name="'rooms[' + index + '][room_type]'" 
                            x-model="room.room_type" 
                            required 
                            placeholder="e.g. Deluxe Double Room" />
                    </div>

                    <!-- Price per Night -->
                    <div class="space-y-2">
                        <x-ui.input-label value="Price (NPR) / Night *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rs.</span>
                            <x-ui.text-input type="number" 
                                x-bind:name="'rooms[' + index + '][price_per_night]'" 
                                x-model="room.price_per_night" 
                                required 
                                min="0"
                                class="pl-10"
                                placeholder="0.00" />
                        </div>
                    </div>

                    <!-- Total Rooms -->
                    <div class="space-y-2">
                        <x-ui.input-label value="Rooms Available *" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                        <x-ui.text-input type="number" 
                            x-bind:name="'rooms[' + index + '][total_rooms]'" 
                            x-model="room.total_rooms" 
                            required 
                                min="1"
                            placeholder="e.g. 5" />
                    </div>
                </div>
            </div>
        </template>
    </div>

    @error('rooms')
        <p class="text-[10px] font-semibold text-red-600 uppercase tracking-widest">{{ $message }}</p>
    @enderror
</div>
