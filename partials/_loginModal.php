<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111 202 203);">
        <h5 class="modal-title" id="loginModal">Login Here</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/_handleLogin.php" method="post">
          <div class="text-left my-2">
            <b><label for="email">Email</label></b>
            <input class="form-control" id="loginemail" name="loginemail" placeholder="Enter your email" type="email" required>
          </div>
          <div class="text-left my-2">
            <b><label for="password">Password</label></b>
            <input class="form-control" id="loginpassword" name="loginpassword" placeholder="Enter your password" type="password" required data-toggle="password">
          </div>

          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="termsAndConditions" required>
            <label class="form-check-label" for="termsAndConditions">I agree to the <a href="/termsAndConditions.php" target="_blank">Terms & Conditions</a></label>
          </div>
          <button type="submit" class="btn btn-success">Submit</button>
        </form>
        <p class="mb-0 mt-1">Don't have an account? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signupModal">Sign up now</a>.</p>
      </div>
    </div>
  </div>
</div>