<section>
    <header>
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update', $user->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        {{-- @method('patch') --}}

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="photo" :value="__('Profile Photo')" />
            <div class="mt-1 flex items-center">
                <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewImage(this)" />
                <label for="photo" class="cursor-pointer bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md text-sm text-gray-700">
                    {{ __('Choose a photo') }}
                </label>
                <span id="file-name" class="ml-2 text-sm text-gray-500"></span>
            </div>
            <div class="mt-2" id="image-preview-container" style="display: none;">
                <img id="image-preview" class="w-32 h-32 object-cover rounded-md">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <x-input-label for="municipality" :value="_('Municipality')"/>
            <select id="municipality" name="municipality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">{{__("Select Municipality")}}</option>
                <option value="Urbiztondo" {{ old('municipality', $user->municipality) == 'Urbiztondo' ? 'selected' : '' }}>Urbiztondo</option>
                <option value="Calasio" {{ old('municipality', $user->municipality) == 'Calasio' ? 'selected' : '' }}>Calasio</option>
                <option value="Bayambang" {{ old('municipality', $user->municipality) == 'Bayambang' ? 'selected' : '' }}>Bayambang</option>
                <option value="Basista" {{ old('municipality', $user->municipality) == 'Basista' ? 'selected' : '' }}>Basista</option>
                <option value="Malasique" {{ old('municipality', $user->municipality) == 'Malasique' ? 'selected' : '' }}>Malasique</option>
                <option value="San Carlos" {{ old('municipality', $user->municipality) == 'San Carlos' ? 'selected' : '' }}>San Carlos</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('municipality')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center">
            <span class="text-gray-500 bg-gray-100 px-3 py-2 rounded-l-md">+63</span>
            <x-text-input 
                id="contact_number" 
                name="contact_number" 
                type="text" 
                class="mt-1 block w-full rounded-r-md" 
                :value="old('contact_number', substr($user->contact_number, 3))" 
                required 
                maxlength="10" 
                autofocus 
                autocomplete="contact_number" 
            />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />




        <div class="flex items-center mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
    

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-4"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');
        const fileNameSpan = document.getElementById('file-name');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
            fileNameSpan.textContent = input.files[0].name;
        } else {
            preview.src = '';
            container.style.display = 'none';
            fileNameSpan.textContent = '';
        }
    }
</script>
