<x-layouts.app>
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Indicator -->
            <div class="flex items-center justify-between mb-12 relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-200 -z-0"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold shadow-lg shadow-slate-900/10">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Info</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold shadow-lg shadow-slate-900/20">2</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-900">Passengers</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-semibold">3</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Confirm</span>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-900/5 border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    @php
                        $initialPassengers = old('passengers', array_fill(0, $bookingData['total_passengers'], [
                            'full_name' => '',
                            'passport_number' => '',
                            'age' => ''
                        ]));
                    @endphp

                    <form action="{{ route('bookings.confirm') }}" method="POST" 
                          x-data="{
                              passengers: {{ Js::from($initialPassengers) }},
                              price: {{ $bookingData['price_per_person'] }},
                              get discount() {
                                  let count = this.passengers.length;
                                  if (count >= 10) return 15;
                                  if (count >= 6) return 10;
                                  if (count >= 3) return 5;
                                  return 0;
                              },
                              get subtotal() {
                                  return this.passengers.length * this.price;
                              },
                              get total() {
                                  return this.subtotal - ((this.subtotal * this.discount) / 100);
                              },
                              validate() {
                                  let passports = this.passengers.map(p => (p.passport_number || '').toString().trim());
                                  
                                  // Min length check
                                  if (passports.some(p => p.length < 5)) {
                                      alert('All passport numbers must be at least 5 characters long.');
                                      return false;
                                  }

                                  // Missing number check
                                  if (passports.some(p => !/\d/.test(p))) {
                                      alert('Each passport number must contain at least one digit.');
                                      return false;
                                  }

                                  // Duplicate check
                                  let uniquePassports = new Set(passports.filter(p => p !== ''));
                                  if (uniquePassports.size !== passports.length) {
                                      alert('Each passenger must have a unique passport number.');
                                      return false;
                                  }

                                  return true;
                              }
                          }"
                          @submit.prevent="if (validate()) $el.submit()"
                          class="max-w-3xl mx-auto space-y-12">
                        @csrf
                        
                        <div class="text-center">
                            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight mb-3">Passenger Details</h1>
                            <p class="text-slate-500 font-medium">Provide travel details for everyone on this trek booking.</p>
                        </div>

                        <!-- Passenger List -->
                        <div class="space-y-8">
                            @for($i = 0; $i < $bookingData['total_passengers']; $i++)
                                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                                     <div class="bg-slate-900 px-8 py-3 flex items-center justify-between">
                                         <h3 class="text-xs font-semibold text-white uppercase tracking-widest">Passenger #{{ $i + 1 }}</h3>
                                         <i class="fas fa-id-card text-emerald-400"></i>
                                     </div>
                                     <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                         <div class="md:col-span-2">
                                             <x-ui.input-label :value="__('Full Name')" class="mb-2" />
                                             <x-ui.text-input type="text" name="passengers[{{ $i }}][full_name]" x-model="passengers[{{ $i }}].full_name" placeholder="Enter legal name" required />
                                             @error("passengers.$i.full_name")
                                                 <p class="mt-1 text-[10px] font-bold text-red-600 uppercase">{{ $message }}</p>
                                             @enderror
                                         </div>
                                         <div>
                                             <x-ui.input-label :value="__('Passport Number')" class="mb-2" />
                                             <x-ui.text-input type="text" name="passengers[{{ $i }}][passport_number]" x-model="passengers[{{ $i }}].passport_number" placeholder="Passport #" required />
                                             @error("passengers.$i.passport_number")
                                                 <p class="mt-1 text-[10px] font-bold text-red-600 uppercase">{{ $message }}</p>
                                             @enderror
                                         </div>
                                         <div>
                                             <x-ui.input-label :value="__('Age')" class="mb-2" />
                                             <x-ui.text-input type="number" name="passengers[{{ $i }}][age]" x-model="passengers[{{ $i }}].age" placeholder="Age" min="1" max="120" required />
                                             @error("passengers.$i.age")
                                                 <p class="mt-1 text-[10px] font-bold text-red-600 uppercase">{{ $message }}</p>
                                             @enderror
                                         </div>
                                     </div>
                                </div>
                            @endfor
                        </div>

                        <!-- Order Summary & Payment Selection -->
                        <div class="space-y-6">
                            <!-- Summary Card -->
                             <div class="p-8 bg-slate-900 rounded-[2rem] border border-slate-800 text-white shadow-2xl shadow-slate-900/20">
                                 <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">Order Summary</h4>
                                 <div class="space-y-4">
                                     <div class="flex justify-between items-center">
                                         <span class="text-sm font-medium text-slate-400">Subtotal ({{ $bookingData['total_passengers'] }} travellers)</span>
                                         <span class="text-sm font-semibold tracking-wide">NPR {{ number_format($bookingData['price_per_person'] * $bookingData['total_passengers'], 0) }}</span>
                                     </div>
                                     <div class="flex justify-between items-center text-emerald-400">
                                         <span class="text-sm font-medium">Group Discount Applied</span>
                                         <span class="text-sm font-bold tracking-widest" x-text="'-' + discount + '%'"></span>
                                     </div>
                                     <div class="pt-6 border-t border-slate-800 flex justify-between items-center">
                                         <span class="text-lg font-semibold tracking-tight text-slate-200">Total Amount</span>
                                         <strong class="text-3xl font-bold tracking-tighter text-white" x-text="'NPR ' + new Number(total).toLocaleString()"></strong>
                                     </div>
                                 </div>
                             </div>

                             <!-- Payment Selection -->
                             <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
                                  <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">Choose Payment Method</h4>
                                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                      <label class="relative flex items-center gap-4 p-5 bg-white rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-slate-900 transition-all group">
                                          <input type="radio" name="payment_method" value="stripe" checked class="w-5 h-5 text-slate-900 border-slate-300 focus:ring-slate-900 transition-colors">
                                          <div class="flex items-center gap-3">
                                              <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                                  <i class="fab fa-stripe-s"></i>
                                              </div>
                                              <div>
                                                  <strong class="block text-sm font-bold text-slate-900">Stripe (Card)</strong>
                                                  <span class="text-[10px] font-semibold text-slate-400 uppercase">Visa, MasterCard, Amex</span>
                                              </div>
                                          </div>
                                      </label>
                                      <label class="relative flex items-center gap-4 p-5 bg-white rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-slate-900 transition-all group">
                                          <input type="radio" name="payment_method" value="esewa" class="w-5 h-5 text-slate-900 border-slate-300 focus:ring-slate-900 transition-colors">
                                          <div class="flex items-center gap-3">
                                              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-semibold italic">
                                                  e
                                              </div>
                                              <div>
                                                  <strong class="block text-sm font-bold text-slate-900">eSewa</strong>
                                                  <span class="text-[10px] font-semibold text-slate-400 uppercase">Nepali Digital Wallet</span>
                                              </div>
                                          </div>
                                      </label>
                                  </div>
                                  @error('payment_method')
                                      <p class="mt-4 text-[10px] font-bold text-red-600 uppercase tracking-tighter">{{ $message }}</p>
                                  @enderror
                             </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-6 border-t border-slate-100">
                             <button type="submit" class="w-full py-5 bg-slate-900 text-white font-semibold rounded-2xl shadow-2xl shadow-slate-900/20 hover:bg-slate-800 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-3 group">
                                 Complete Booking & Pay
                                 <i class="fas fa-lock text-[10px] text-emerald-400"></i>
                             </button>
                             <p class="text-[10px] text-slate-400 font-bold text-center mt-6 uppercase tracking-tighter">By continuing, you agree to our Terms of Service and Cancellation Policy.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

