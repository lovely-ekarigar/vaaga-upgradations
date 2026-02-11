

<footer class="footer effect-section">
        <div class="effect-shape" style="background-color: #2d3748d1;"></div>
        <div class="py-7 py-md-8 py-lg-10 position-relative">
          <div class="container">
            <div class="row justify-content-between">
              <div class="col-12 col-lg-4 my-3">
                 <h5 class="footer-title-02 text-white">{{env('PROJECT_NAME')}}</h5>
                <p class="link-white">Online Live interactive learning platform for school students for all subjects by the best Tutors from all over the country. Personalised Private and Group classes for learning at home to achieve best results.  </p>

                <p>
                  <div class="nav">
                        <a class="icon-sm  rounded-circle me-2" href="https://www.facebook.com/profile.php?id=100095502854507" aria-label="VaaGaAcademy Facebook" target="_blank"><i style="    font-size: 20px;color:#1877f2" class="bi-facebook"></i> </a>
                        <a class="icon-sm  rounded-circle me-2" href="https://www.instagram.com/vaagaacademy/" aria-label="VaaGaAcademy Instagram" target="_blank"><i style="    font-size: 20px;color:#d74d5d" class="bi-instagram"></i> </a>
                        <a class="icon-sm  rounded-circle me-2" href="https://www.linkedin.com/company/96911976/admin/feed/posts/" aria-label="VaaGaAcademy Linkedin" target="_blank"><i style="    font-size: 20px;color:#0a66c2" class="bi-linkedin"></i></a>
                        <a class="icon-sm  rounded-circle me-2" href="https://www.youtube.com/channel/UCLc_WUBiIdvi1aMwMk4Ixyg" aria-label="VaaGaAcademy Youtube" target="_blank"><i style="    font-size: 25px;color:#d74d5d" class="bi-youtube"></i></a>
                        
                    <a class="icon-sm  rounded-circle me-2" href="https://twitter.com/VaaGaAcademy" aria-label="VaaGaAcademy Twitter" target="_blank"><i style="font-size: 25px; color:black" class="bi bi-twitter-x"></i></a>
                     </div>
                </p>
              </div>
              <div class="col-6 col-lg-2 my-3">
                <h5 class="footer-title-02 text-white">For Tutor</h5>
                <ul class="list-unstyled footer-link-01 m-0">
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/user/dashboard">Profile</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/userlogin">Login</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/become-tutor">Register</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/become-tutor#faqs">Tutor FAQs</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/blog">Blogs</a>
                  </li>
                  
                </ul>
              </div>
              <div class="col-6 col-lg-2 my-3">
                <h5 class="footer-title-02 text-white">For Student</h5>
                <ul class="list-unstyled footer-link-01 m-0">
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/user/dashboard">Profile</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/userlogin">Login</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/userregister">Register</a>
                  </li>
                  <li>
                    <a class="link-white" href="https://vaagaacademy.com/userregister#faqs">Student FAQs</a>
                  </li>
                  <li>
                    <a class="link-white" href="{{route('home.testimonials')}}">Testimonials</a>
                  </li>
                 
                </ul>
              </div>
              <div class="col-6 col-lg-2 my-3">
                <h6 class="footer-title-02 text-white">Quick Links</h6>
                <ul class="list-unstyled footer-link-01 m-0">
                    <li>
                    <a class="link-white" href="{{route('frontend.ourTeams')}}">Our Teams</a>
                  </li>
                  <li>
                    <a class="link-white" href="{{ route('frontend.note.categories') }}">Study Material</a>
                  </li>
                  <li>
                    <a class="link-white" href="/terms-and-conditions">Terms and Conditions</a>
                  </li>
                  
                  <li>
                    <a class="link-white" href="/privacy">Privacy and Policy</a>
                  </li>
                  <li>
                    <a class="link-white" href="/refund-policy">Refund Policy</a>
                  </li>
                  <li>
                    <a class="link-white" href="{{route('support-and-helpdesk')}}">Support and Helpdesk</a>
                  </li>
                  
                </ul>
              </div>
            </div>
          </div>
        </div>
        <hr class="m-0">
        <div class="container position-relative">
          <div class="row py-3 gy-3 align-items-center">
            
            <div class="col-md-12 text-center">
              <p class="small m-0 text-white text-opacity-85">&copy; {{date("Y")}} VaaGa Academy
. All rights reserved.</a>
              </p>
            </div>
          </div>
        </div>
      </footer>