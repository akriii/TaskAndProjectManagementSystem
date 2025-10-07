<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task and Project Management System</title>
<style>
:root {
  --purple-dark: #5a3e75;
  --purple-mid: #a892c3;
  --purple-light: #f4effa;
  --mimos-magenta: #c062a0;
  --green-soft: #d4f1e1;
  --green-hover: #bfe3ce;
  --text-main: #3e2c4d;
  --text-secondary: #6b567a;
  --neutral-bg: #fbf8fd;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(to bottom, var(--neutral-bg), white);
  color: var(--text-main);
  line-height: 1.6;
}

a {
  text-decoration: none;
  color: inherit;
}

/* Navbar */
.navbar {
  width: 100%;
  background-color: var(--purple-mid);
  border-bottom: 1px solid var(--purple-dark);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
}

.navbar-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  height: 80px;
  width: auto;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.login-btn {
  background-color: var(--green-soft);
  color: #2f4c3d;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-size: 1rem;
  font-weight: 600;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.login-btn:hover {
  background-color: var(--green-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Hero Section */
.hero {
  padding: 10rem 1.5rem 6rem;
  background: linear-gradient(to bottom, var(--neutral-bg), white);
}

.hero-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 3rem;
  align-items: center;
}

@media (min-width: 992px) {
  .hero-container {
    flex-direction: row;
    align-items: center;
    gap: 4rem;
  }
}

.hero-text {
  flex: 1;
  padding: 0 1rem;
}

.hero-text h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.75rem;
  color: var(--purple-dark);
  line-height: 1.2;
}

.hero-text h2 {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: var(--mimos-magenta);
}

.hero-text p {
  font-size: 1.15rem;
  margin-bottom: 2rem;
  color: var(--text-secondary);
  max-width: 600px;
}

.hero-text a {
  display: inline-block;
  background-color: var(--green-soft);
  border: 2px solid var(--green-soft);
  color: #2f4c3d;
  padding: 0.9rem 1.8rem;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-text a:hover {
  background-color: var(--green-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.hero-image {
  flex: 1;
  position: relative;
  max-width: 600px;
}

.hero-image img {
  width: 100%;
  border-radius: 1rem;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  transition: transform 0.5s ease;
}

.hero-image:hover img {
  transform: scale(1.02);
}

/* Features Section */
.features {
  background-color: var(--purple-light);
  padding: 6rem 1.5rem;
  text-align: center;
}

.features-container {
  max-width: 1200px;
  margin: 0 auto;
}

.features h2 {
  font-size: 2.4rem;
  font-weight: 700;
  color: var(--purple-dark);
  margin-bottom: 3rem;
  position: relative;
  display: inline-block;
}

.features h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background-color: var(--mimos-magenta);
  border-radius: 2px;
}

.feature-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2.5rem;
  justify-content: center;
  margin-top: 3rem;
}

@media (min-width: 768px) {
  .feature-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 3rem;
  }
}

.feature-card {
  background-color: white;
  padding: 2.5rem 2rem;
  border-radius: 1rem;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
  max-width: 350px;
  width: 100%;
  text-align: center;
  transition: all 0.3s ease;
  margin: 0 auto;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.icon-placeholder {
  width: 60px;
  height: 60px;
  background-color: var(--green-soft);
  border-radius: 50%;
  margin: 0 auto 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: var(--purple-dark);
  font-weight: bold;
}

.feature-card h3 {
  font-size: 1.4rem;
  color: var(--text-main);
  margin-bottom: 1rem;
  font-weight: 700;
}

.feature-card p {
  font-size: 1.1rem;
  color: var(--text-secondary);
  line-height: 1.6;
}

/* CTA Section */
.cta {
  background-color: var(--purple-mid);
  color: white;
  text-align: center;
  padding: 5rem 1rem;
  position: relative;
  overflow: hidden;
}

.cta::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
  opacity: 0.5;
}

.cta-container {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

.cta h2 {
  font-size: 2.4rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.3;
}

.cta p {
  font-size: 1.2rem;
  margin-bottom: 1rem;
  line-height: 1.6;
}

/* Footer */
footer {
  background-color: var(--purple-light);
  padding: 3rem 1rem;
  text-align: center;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
}

footer img {
  height: 70px;
  width: auto;
  margin-bottom: 1.5rem;
  transition: transform 0.3s ease;
}

footer img:hover {
  transform: scale(1.05);
}

.copyright {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-top: 1.5rem;
}
</style>

</head>
<body>

  <!-- Navbar -->
<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-container">
    <img src="images/logoheader.jpg" alt="Mimos logo" class="logo" />
    <a href="indexLogin.php" class="login-btn">Login Now</a>
  </div>
</nav>


  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-container">
      <div class="hero-text">
        <h1>Task and Project Management System</h1>
        <h2>Streamlined Your Daily Workflow</h2>
        <p>A smart way helps users manage tasks efficiently, stay organized 
and meet deadlines through a centralized digital platform.</p>
        <a href="indexLogin.php">Get Started</a>
      </div>
      <div class="hero-image">
        <img src="images/screencapture.jpg" alt="System Screenshot" />
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="features">
    <div class="features-container">
      <h2>Who Can Access the System?</h2>
      <div class="feature-grid">
        <div class="feature-card">
          <div class="icon-placeholder">A</div>
          <h3>Administrator Access</h3>
          <p>Plan, assign, and monitor tasks with timelines, priorities, and seamless team oversight.</p>
        </div>
        <div class="feature-card">
          <div class="icon-placeholder">E</div>
          <h3>Employee Access</h3>
          <p>View assigned tasks, add updates, and collaborate easily with real-time progress tracking.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta">
    <div class="cta-container">
      <h2>Pioneering Innovations</h2>
      <p>MIMOS is Malaysia's national Applied Research and Development Centre that contributes to</p>
      <p>socio-economic growth through innovative technology platforms, products and solutions.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <img src="images/logofooter.jpg" alt="Mimos Footer Logo" />
      <p class="copyright">© 2025 Task and Project Management System. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>





