<footer id="footer" class="footer">
  <div class="footer-newsletter">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-6">
          <h4>Join Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container footer-top">
    <div class="row gy-4">
      <div class="col-lg-4 col-md-6 footer-about">
        <a href="{{ route('home') }}" class="d-flex align-items-center">
          <span class="sitename">{{ config('app.name') }}</span>
        </a>
        <div class="footer-contact pt-3">
          <p>1391 NW ST LUCIE WEST BLVD</p>
          <p>SUITE 137 PORT SAINT LUCIE, FL 34986</p>
          <p class="mt-3"><strong>Phone:</strong> <span>305-900-7179</span></p>
          <p><strong>Email:</strong> <span>admin@swifterecordingservices.com</span></p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}">Home</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#about">About us</a></li>
          <li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#contact">Contact us</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-12">
        <h4>Follow Us</h4>
        <p>Time is Money and we intend to save you that money by cutting back the time it takes for you to record your documents.</p>
        <div class="social-links d-flex">
          <a target="_blank" href="https://x.com"><i class="bi bi-twitter-x"></i></a>
          <a target="_blank" href="https://facebook.com"><i class="bi bi-facebook"></i></a>
          <a target="_blank" href="https://instagram.com"><i class="bi bi-instagram"></i></a>
          <a target="_blank" href="https://linkedin.com"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ config('app.name') }}</strong> <span>All Rights Reserved</span></p>
    @include('shared.designby')
  </div>
</footer>