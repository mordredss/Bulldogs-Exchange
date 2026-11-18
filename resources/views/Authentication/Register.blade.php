<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Register.css') }}">
  </head>
  <body>
    <div class="desktop">
      <div class="div">
        <form id="registrationForm" method="POST" action="#">
           <div class="text-wrapper-2">Account Registration</div> 
          <hr class="section-divider">

          <div class="text-wrapper"><i class="fas fa-user icon-user-info"> </i>&nbsp;&nbsp;User Credentials</div>

          <!-- User Credentials Section -->
          <div class="text-wrapper-3">Username: *</div>
          <input type="text" name="username" placeholder="Enter username" required />

          <div class="text-wrapper-4">Password: *</div>
          <input type="password" name="password" placeholder="Enter password" required />

          <div class="text-wrapper-5">Confirm Password: *</div>
          <input type="password" name="confirmPassword" placeholder="Confirm password" required />

          <div class="text-wrapper-6">Email Address: *</div>
          <input type="email" name="email" placeholder="Enter email address" required />

          <hr class="section-divider">

          <!-- Address and Contact Section -->
          <div class="text-wrapper"><i class="fas fa-address-book icon-address-contact"></i>&nbsp;&nbsp;Address and Contact</div>

          <div class="text-wrapper-8">Street/Barangay:</div>
          <input type="text" name="street" placeholder="Enter street/barangay" />

          <div class="text-wrapper-9">City/Municipality:</div>
          <input type="text" name="city" placeholder="Enter city/municipality" />

          <div class="text-wrapper-10">State/Province: *</div>
          <input type="text" name="province" placeholder="Enter state/province" required />

          <div class="text-wrapper-11">Country: *</div>
          <select name="country" required>
            <option value="">Select Country</option>
            <option value="Philippines">Philippines</option>
            <option value="United States">United States</option>
            <option value="Canada">Canada</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="Australia">Australia</option>
            <option value="Singapore">Singapore</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Japan">Japan</option>
          </select>

          <hr class="section-divider">

          <!-- Basic Information Section -->
          <div class="text-wrapper"><i class="fas fa-address-card icon-basic-info"></i>&nbsp;&nbsp;Basic Information</div>

          <div class="text-wrapper-13">Salutation: *</div>
          <select name="salutation" required>
            <option value="">Select Salutation</option>
            <option value="Mr">Mr.</option>
            <option value="Ms">Ms.</option>
            <option value="Mrs">Mrs.</option>
            <option value="Dr">Dr.</option>
            <option value="Prof">Prof.</option>
          </select>

          <div class="last-name">Last Name: *</div>
          <input type="text" name="lastName" placeholder="Enter last name" required />

          <div class="text-wrapper-14">First Name: *</div>
          <input type="text" name="firstName" placeholder="Enter first name" required />

          <div class="text-wrapper-15">Middle Name:</div>
          <input type="text" name="middleName" placeholder="Enter middle name" />

          <div class="text-wrapper-16">Mobile No. :</div>
          <input type="tel" name="mobileNo" placeholder="Enter mobile number" />

          <div class="text-wrapper-17">Zipcode:</div>
          <input type="text" name="zipcode" placeholder="Enter zipcode" />

          <div class="text-wrapper-18">Telephone No. :</div>
          <input type="tel" name="telephoneNo" placeholder="Enter telephone number" />

          <div class="text-wrapper-19">Date of Birth: *</div>
          <input type="date" name="dateOfBirth" required />

          <div class="text-wrapper-20">Gender: *</div>
          <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
            <option value="prefer-not-to-say">Prefer not to say</option>
          </select>

          <div class="text-wrapper-21">Nationality: *</div>
          <input type="text" name="nationality" placeholder="Enter nationality" required />

          <hr class="section-divider">

          <!-- Privacy Agreement -->
        <label class="privacy-agreement">
          <input type="checkbox" id="privacyAgreement" name="privacyAgreement" required class="checkbox" />
          <span class="agreement">I agree with the <a href="" class="text-wrapper-23" target="_blank" rel="noopener noreferrer">Data Privacy Agreement</a></span>
        </label>


          <!-- Form Buttons -->
          <button type="submit" class="Register">
            <div class="text-wrapper-22">REGISTER</div>
          </button>

          <button type="button" class="Cancel" onclick="window.location.href='{{route('Login')}}'">
            <div class="text-wrapper-24">CANCEL</div>
          </button>
        </form>
      </div>
    </div>

  </body>
</html>
