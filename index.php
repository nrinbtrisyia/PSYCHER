<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PSYCHER</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
   
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-ez0S1ZU4vRVAlR8BrvYPepHYuhxP3toPUWEvL9nmh3bf5P6PRTt8+rRTt8+rR" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="landing_page.css">
    <style>
    .tile {
  background-color: #fafafa;
  height: 40rem;
  width: 25rem;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
  border-radius: 0.5rem;
  transition: all 0.2s;
  padding: 1.5rem;
  margin: 1.5rem;
  display: flex; /* Use flexbox */
  flex-direction: column; /* Arrange content vertically */
  justify-content: center; /* Center content vertically */
  align-items: center; /* Center content horizontally */
}

@media (max-width: 680px) {
  .tile {
    width: auto;
    max-width: 100%;
    min-width: 20rem;
    margin: 1.5rem auto;
    height: auto;
  }
}

.tile img {
  width: 70%;
  max-width: 70rem;
  border-radius: 0.5rem;
}

.tile h4 {
  font-size: 2rem;
  font-weight: 600;
  padding: 1rem 0;
  color: #e23d3d;
  margin: 0;
}

.tile p {
  font-size: 1.6rem;
  padding: 0;
  margin: 0;
}

.tile:hover {
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.19), 0 16px 16px rgba(0, 0, 0, 0.23);
}



      </style>
  </head>
  <body>
    <header id="header">
      <div class="header-content-div">
        <a href="#home-sec"></a>
          <img src="Resource/newlogo.jpg"
            alt="Company Logo"
            id="header-img"
        />
      <span class ="text-logo">psychER</span>
        <nav id="nav-bar">
          <a href="#about" class="nav-link">ABOUT </a>
          <a href="#varieties" class="nav-link">DOCTORS</a>
          <a href="#our-service" class="nav-link">OUR SERVICE</a>
          <a href="#benefits" class="nav-link">BENEFITS</a>
        </nav>
      </div>
    </header>
    <main>
      <section id="home-sec" class="flexible home-sec">
        <div class="eye-grabber-img">
          <img src="Resource/psycher1.jpg" alt="Image of Apples" />
        </div>
        <div class="eye-grabber">
          <h1>MAKING THE WORLD A HEALTHIER, HAPPIER PLACE</h1>
          <h2>
            Streamlining mental health care with digital patient records, 
            treatment plans, and appointment scheduling.
            Enhances efficiency, security, and patient engagement, 
            marking a transformative shift in mental health practices.
          </h2>
          
          <!-- Dropdown code -->
          <div class="dropdown">
            <button class="dropbtn">Login <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
              <a href="patient.login.php">Patient Login</a>
              <a href="doctor.login.php">Doctor Login</a>
              <a href="staff.login.php">Staff Login</a>
            </div>
          </div>
        </div>
      </section>
      <section id="about" class="sec-padding">
        <h3 class="section-heading">ABOUT US</h3>
        <div class="sec-content-div flexible">
            <p>Welcome to the Psychiatrist Electronic Health Record system, your trusted partner in managing and maintaining psychiatric patient records with ease and efficiency.</p>
                    <p>Our mission is to streamline the process of psychiatric patient care by providing a comprehensive digital platform that empowers healthcare professionals and improves patient outcomes.</p>
          </p>
          <img src="Resource/AboutUs.jpg" alt="A man plucking apples from the tree" />
        </div>
      </section>
      <section id="varieties" class="sec-padding">
        <h3 class="section-heading">DOCTORS</h3>
        <div class="sec-content-div flexible">
          <div class="tile">
            <img src="Resource/yuzana.jpg" alt="photo of amber apples" />
            <h4>Dr. Yuzana Binti Mohd Yusop </h4>
            <p>
            Pensyarah Universiti
            Social Work and Administration - S3011800-Public Health 
            S5022500-Social Psychology
            Call: 4 09-6275504
            Email: yuzanayusop
            
            </p>
          </div>
          <div class="tile">
            <img
              src="Resource/rosliza.jpg"
              alt="photo of american trel apples" />
            <h4> Dr. Rosliza Binti Yahaya</h4>
            <p>Kepakaran - F5010700-
              Psychiatry - F5011100-Medical
              Ethics - F7011300-Counselling and
              Psychotherapy
              Call: 4 09-6275528
             Email: roslizayahaya
             </p>
          </div>
          <div class="tile">
            <img src="Resource/hanisah.jpg" alt="photo of red delicious apple" />
            <h4> Dr. Hanisah Binti Mohd Noor</h4>
            <p>Pensyarah Perubatan Gred Khas C Ketua Jabatan Psikiatri Dan Kesihatan  Mental
              Email:  hanisahmnoor
             </p>
          </div>
          <div class="tile">
            <img src="Resource/rohayah.jpg" alt="photo of Maharaej apples" />
            <h4>Prof. Madya Dr. Rohayah Binti Husain</h4>
            <p>Pensyarah Perubatan Gred Khas C (Profesor Madya)
            Kepakaran - F5010700-Psychiatry 
            Call: 409-6275658
            Email: rohayah
            </p>
          </div>
          <div class="tile">
            <img src="Resource/khairi.jpg" alt="photo of Hazratbael apples" />
            <h4> Dr. Hj. Khairi Bin Che Mat</h4>
            <p> Pensyarah Perubatan Gred Khas C
            & Kepakaran - F5010700-
            Psychiatry - F5012200-Mental Health
            Call: 09-6275621
            Email: khairicm
          </p>
          </div>
          <div class="tile">
            <img src="Resource/isma.jpg" alt="photo of Golden Delicious apples" />
            <h4> Dr. Ismawati Binti Ismail</h4>
            <p>Pensyarah Perubatan Gred Khas C
            Kepakaran - F5010700-
            Emergency Medicine
            Email:ismawatiismail
           </p>
          </div>
        </div>
      </section>
      <section id="our-service" class="sec-padding">
        <h3 class="section-heading">OUR SERVICE</h3>
        <div class="sec-content-div">
          <div class="bars">
            <div class="icon-container">
              <img src="https://i.ibb.co/w6H542X/Fresh.png" alt="" />
            </div>
            <div class="txt-container">
              <h5>Fresh</h5>
              <p>Elevate your psychiatric practice with our secure and efficient EHR services.</p>
            </div>
          </div>
          <div class="bars">
            <div class="icon-container">
              <img src="https://i.ibb.co/FKNq4Qr/delivered.png" alt="" />
            </div>
            <div class="txt-container">
              <h5>Fast</h5>
              <p>
              Contact us for a personalized demonstration and enhance your practice with streamlined EHR.
              </p>
            </div>
          </div>
          <div class="bars">
            <div class="icon-container">
              <img src="https://i.ibb.co/HHQK1wV/happy.png" alt="" />
            </div>
            <div class="txt-container">
              <h5>Satisfying</h5>
              <p>
              Access detailed and confidential patient information, including telehealth integration.
              </p>
            </div>
          </div>
        </div>
      </section>
      <section id="benefits" class="sec-padding">
        <h3 class="section-heading">BENEFITS</h3>

        <div class="sec-content-div flexible">
  <iframe
    id="video"
    width="560"
    title="Benefits of Apple"
    height="315"
    src="https://www.youtube.com/embed/fWFuQR_Wt4M?si=H9ltwH06cSso86b6"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowfullscreen
  ></iframe>
</div>
    </section>

    <section class="sec-padding" id="contact">
    <h3 class="section-heading">CONTACT US</h3>
    <div class="sec-content-div flexible">
        <h6>For inquiries or orders, feel free to reach out:
        <p style="text-align: center">Contact Number: +60380977888</p>
        <p style="text-align: center">Email: <a href="mailto:info@example.com">psychER@gmail.com</a></p>
        <p style="text-align: center">Instagram: <a href="https://www.instagram.com/your_instagram_username/">@psychER</a></p></h6>
    </div>
</section>

    </main>
    <!-- <footer>
      Created by
      <a href="#">Mohd Shariq</a>
    </footer> -->
  </body>
</html>