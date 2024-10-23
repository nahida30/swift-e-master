@extends('shared.layout')

@section('title', 'Welcome')

@push('styles')
<style>

</style>
@endpush

@section('content')
@include('shared.header2')

<main class="main">
  <section id="hero" class="hero section">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <!-- <h1 data-aos="fade-up">IMAGERY OF CONSTRUCTION INDUSTRY</h1> -->
          <p data-aos="fade-up" data-aos-delay="100">As society advances the courts are typically the last to innovate, but even they too have gone digital. We can connect the dots eRecording your
            Judgements, Settlement Agreements and much more.</p>
          <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('signup') }}" class="btn-get-started">Get Started <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
          <img src="{{ asset('img/hero-img.png') }}" class="img-fluid animated" alt="" />
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="about section">
    <div class="container" data-aos="fade-up">
      <div class="row gx-0">
        <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
          <div class="content">
            <h3>Who We Are</h3>
            <h2>Time is Money and we intend to save you that money by cutting back the time it takes for you to record your documents.</h2>
            <p>Your number 1 priority as a business is steady growth. And we know that if you are growing that means that you have found ways in your business to increase both productivity and efficiency. That is where we come in. By eliminating the need, costs, and liability of driving to a county recorders office, going through security check points, towing a line, and dealing with possible clerical errors when your finally served. We will streamline this part of your business so that you can worry most on what matters. We have been in the physical recording business for 18 years hence we can speak from experience and then can use our experience to provide you with an excellent product. Time is money and we intend to save you some.</p>
          </div>
        </div>
        <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
          <img src="{{ asset('img/about.jpg') }}" class="img-fluid" alt="" />
        </div>
      </div>
    </div>
  </section>

  <section id="values" class="values section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Our Values</h2>
      <p>What we value most<br></p>
    </div>
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card">
            <img src="{{ asset('img/values-1.jpg') }}" class="img-fluid" height="400" alt="" />
            <!-- <h3>IMAGERY OF LEGAL INDUSTRY</h3> -->
            <p>As society advances the courts are typically the last to innovate, but even they too have gone digital. We can connect the dots eRecording your Judgements, Settlement Agreements and much more.</p>
          </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
          <div class="card">
            <img src="{{ asset('img/values-2.jpg') }}" class="img-fluid" height="400" alt="" />
            <!-- <h3>IMAGERY OF CONSTRUCTION INDUSTRY</h3> -->
            <p>Whether your on a roof, installing windows and doors, or laying the decking around a pool, you're putting sweat and equity into your projects. Protect your interests and save time while doing so
              by Erecording your NOC's and if need be releasing them with Notices of Termination</p>
          </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
          <div class="card">
            <img src="{{ asset('img/values-3.jpg') }}" class="img-fluid" height="400" alt="" />
            <!-- <h3>IMAGERY OF REAL ESTATE INDUSTRY</h3> -->
            <p>For Title companies you are at the finish line, instead of driving there simply click here and record your Deeds, Affidavits of Titles, and Easements</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="faq" class="faq section">
    <div class="container section-title" data-aos="fade-up">
      <h2>F.A.Q</h2>
      <p>Frequently Asked Questions</p>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <div class="faq-container">
            <div class="faq-item">
              <h3>What is E-Recording?</h3>
              <div class="faq-content">
                <p>eRecording, or electronic recording, is the process of submitting, receiving, and processing documents for recording in county offices via the internet. This technology replaces the traditional paper-based method of recording legal documents, such as deeds, mortgages, and liens, with an electronic system.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
            <div class="faq-item">
              <h3>Why should we e-record?</h3>
              <div class="faq-content">
                <p><b>1. Efficiency:</b> Faster processing times and immediate feedback on the status of recordings.</p>
                <p><b>2. Accuracy:</b> Reduction in errors due to electronic validation and automated processes.</p>
                <p><b>3. Convenience:</b> Ability to submit documents from anywhere with internet access.</p>
                <p><b>4. Security:</b> Enhanced security through encryption and sec</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
            <div class="faq-item">
              <h3>How does it help me in Real-Estate</h3>
              <div class="faq-content">
                <p>eRecording is widely used in real estate transactions, helping streamline the closing process and ensure timely recording of important documents.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
          <div class="faq-container">
            <div class="faq-item">
              <h3>Title Companies and Real Estate Professionals?</h3>
              <div class="faq-content">
                <p><b>Efficiency:</b> Quicker turnaround times for recording documents, which can speed up real estate transactions and closings.</p>
                <p><b>Convenience:</b> Ability to submit documents electronically from any location, reducing the need for physical travel to recording offices.</p>
                <p><b>Cost Savings:</b> Reduced costs associated with printing, mailing, and courier services.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
            <div class="faq-item">
              <h3>How does it help Construction Industry?</h3>
              <div class="faq-content">
                <p>No longer must you pay a worker or even worse drag yourself to the county recorders office, wait in line, and pray that the document was filled correctly or the notary stamp isn't legible. This amounts to a wasted trip and time is money. Instead, your office simply uploads the document where it will get reviewed for accuracy and submitted on your behalf saving time and money.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
            <div class="faq-item">
              <h3>Is it cheaper to go in person?</h3>
              <div class="faq-content">
                <p>Perhaps if your office is across the street from the courthouse. But most likely you are not. So your company will actually save money by eliminating the liability of driving to and from the County recording office. Save time by recording documents from wherever you have an internet connection. And the saving money on wasted trips due to clerical error on the documents.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p>Contact Us</p>
    </div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-lg-6">
          <div class="row gy-4">
            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="200">
                <i class="bi bi-geo-alt"></i>
                <h3>Address</h3>
                <p>1391 NW ST LUCIE WEST BLVD</p>
                <p>SUITE 137 PORT SAINT LUCIE, FL 34986</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="300">
                <i class="bi bi-telephone"></i>
                <h3>Call Us</h3>
                <p>305-900-7179</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="400">
                <i class="bi bi-envelope"></i>
                <h3>Email Us</h3>
                <p>admin@swifterecordingservices.com</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-item" data-aos="fade" data-aos-delay="500">
                <i class="bi bi-clock"></i>
                <h3>Open Hours</h3>
                <p>Monday - Friday</p>
                <p>9:00AM - 05:00PM</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">
              <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
              </div>
              <div class="col-md-6 ">
                <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
              </div>
              <div class="col-md-12">
                <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
              </div>
              <div class="col-md-12">
                <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
              </div>
              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
                <button type="submit">Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

@include('shared.footer2')
@endsection


@push('scripts')
<script>

</script>
@endpush