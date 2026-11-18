<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Register.css') }}">

    <div class="desktop">
        <div class="div">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="text-wrapper-2">Account Registration</div>
                <hr class="section-divider">

                <!-- User Credentials Section -->
                <div class="text-wrapper">
                    <i class="fas fa-user icon-user-info"></i>&nbsp;&nbsp;User Credentials
                </div>

                <!-- Username -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user"></i></div>
                        <x-input-label for="username" :value="__('Username')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                            :value="old('username')" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-envelope"></i></div>
                        <x-input-label for="email" :value="__('Email')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-lock"></i></div>
                        <x-input-label for="password" :value="__('Password')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-lock"></i></div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user-tag"></i></div>
                        <x-input-label for="role" :value="__('Role')" />
                    </div>
                    <div class="input-wrapper">
                        <select id="role" class="block mt-1 w-full" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Student" {{ old('role') == 'Student' ? 'selected' : '' }}>Student</option>
                            <option value="Non-Student" {{ old('role') == 'Non-Student' ? 'selected' : '' }}>Non-Student
                            </option>
                            <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <hr class="section-divider">

                <!-- Basic Information Section -->
                <div class="text-wrapper">
                    <i class="fas fa-address-card"></i>&nbsp;&nbsp;Basic Information
                </div>

                <!-- Salutation -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user-tag"></i></div>
                        <x-input-label for="salutation" :value="__('Salutation')" />
                    </div>
                    <div class="input-wrapper">
                        <select id="salutation" class="block mt-1 w-full" name="salutation" required>
                            <option value="">Select Salutation</option>
                            <option value="Mr" {{ old('salutation') == 'Mr' ? 'selected' : '' }}>Mr.</option>
                            <option value="Ms" {{ old('salutation') == 'Ms' ? 'selected' : '' }}>Ms.</option>
                            <option value="Mrs" {{ old('salutation') == 'Mrs' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Dr" {{ old('salutation') == 'Dr' ? 'selected' : '' }}>Dr.</option>
                            <option value="Prof" {{ old('salutation') == 'Prof' ? 'selected' : '' }}>Prof.</option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('salutation')" class="mt-2" />
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user"></i></div>
                        <x-input-label for="first_name" :value="__('First Name')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name"
                            :value="old('first_name')" required />
                    </div>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Middle Name -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user"></i></div>
                        <x-input-label for="middle_name" :value="__('Middle Name')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name"
                            :value="old('middle_name')" />
                    </div>
                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-user"></i></div>
                        <x-input-label for="last_name" :value="__('Last Name')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                            :value="old('last_name')" required />
                    </div>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-venus-mars"></i></div>
                        <x-input-label for="gender" :value="__('Gender')" />
                    </div>
                    <div class="input-wrapper">
                        <select id="gender" class="block mt-1 w-full" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-calendar"></i></div>
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth"
                            :value="old('date_of_birth')" required />
                    </div>
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>

                <!-- Nationality -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-globe"></i></div>
                        <x-input-label for="nationality" :value="__('Nationality')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality"
                            :value="old('nationality')" required />
                    </div>
                    <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                </div>

                <hr class="section-divider">

                <!-- Contact Information Section -->
                <div class="text-wrapper">
                    <i class="fas fa-address-book"></i>&nbsp;&nbsp;Contact Information
                </div>


                <!-- Mobile Number -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-mobile-alt"></i></div>
                        <x-input-label for="mobile_no" :value="__('Mobile Number')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="mobile_no" class="block mt-1 w-full" type="tel" name="mobile_no"
                            :value="old('mobile_no')" />
                    </div>
                    <x-input-error :messages="$errors->get('mobile_no')" class="mt-2" />
                </div>

                <!-- Telephone Number -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-phone"></i></div>
                        <x-input-label for="telephone_no" :value="__('Telephone Number')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="telephone_no" class="block mt-1 w-full" type="tel" name="telephone_no"
                            :value="old('telephone_no')" />
                    </div>
                    <x-input-error :messages="$errors->get('telephone_no')" class="mt-2" />
                </div>

                <div class="text-wrapper">
                    <i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;Address Information
                </div>

                <!-- Street -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-road"></i></div>
                        <x-input-label for="street" :value="__('Street Address')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="street" class="block mt-1 w-full" type="text" name="street"
                            :value="old('street')" />
                    </div>
                    <x-input-error :messages="$errors->get('street')" class="mt-2" />
                </div>

                <!-- City -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-city"></i></div>
                        <x-input-label for="city" :value="__('City')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="city" class="block mt-1 w-full" type="text" name="city"
                            :value="old('city')" />
                    </div>
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>

                <!-- Province -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-map-marker-alt"></i></div>
                        <x-input-label for="province" :value="__('Province')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="province" class="block mt-1 w-full" type="text" name="province"
                            :value="old('province')" required />
                    </div>
                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                </div>

                <!-- Country -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-flag"></i></div>
                        <x-input-label for="country" :value="__('Country')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="country" class="block mt-1 w-full" type="text" name="country"
                            :value="old('country')" required />
                    </div>
                    <x-input-error :messages="$errors->get('country')" class="mt-2" />
                </div>

                <!-- Zipcode -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-map-pin"></i></div>
                        <x-input-label for="zipcode" :value="__('Zipcode')" />
                    </div>
                    <div class="input-wrapper">
                        <x-text-input id="zipcode" class="block mt-1 w-full" type="text" name="zipcode"
                            :value="old('zipcode')" />
                    </div>
                    <x-input-error :messages="$errors->get('zipcode')" class="mt-2" />
                </div>


                <hr class="section-divider">


                <!-- Privacy Agreement -->
                <div class="form-group">
                    <div class="input-icon-label" style="display: flex; align-items: center;">
                        <div class="input-icon" style="margin-right: 8px;"><i class="fas fa-shield-alt"></i></div>
                        <label class="flex items-center">
                            <input type="checkbox" name="privacy_agreement" required
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ __('I agree to the Privacy Policy') }}</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('privacy_agreement')" class="mt-2" />
                </div>

                <div class="button-group">
                    <x-primary-button class="register-btn">
                        {{ __('Register') }}
                    </x-primary-button>

                    <a href="{{url('/')}}" class="cancel-btn">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>