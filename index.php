<?php
session_start();
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$isAdmin = $isLoggedIn && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PADI MART</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/landingpage.css" />
    <link rel="stylesheet" href="css/responsive-utilities.css" />
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar">
      <div class="container">
        <!-- Left side: hamburger + left  uningnav links -->
        <div class="nav-left">
          <button
            class="hamburger-menu"
            id="hamburgerBtn"
            aria-label="Open menu"
          >
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
          </button>

          <div class="main-nav left-nav">
            <a class="nav-link" href="#about"
              ><svg
                class="icon"
                viewBox="0 0 24 24"
                width="16"
                height="16"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
              >
                <path
                  d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"
                  fill="currentColor"
                /></svg
              >Tentang</a
            >
            <a class="nav-link" href="#products"
              ><svg
                class="icon"
                viewBox="0 0 24 24"
                width="16"
                height="16"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
              >
                <path
                  d="M3 7h18v2H3zM3 11h18v2H3zM3 15h12v2H3z"
                  fill="currentColor"
                /></svg
              >Produk</a
            >
          </div>
        </div>

        <!-- Center: brand/logo -->
        <a class="navbar-brand center-brand" href="#">
          <img
            src="img/PADI%20MART.png"
            alt="PADI MART logo"
            class="brand-logo"
          />
        </a>

        <!-- Right side: right nav links -->
        <div class="nav-right">
          <div class="main-nav right-nav">
            <a class="nav-link" href="#testimonials"
              ><svg
                class="icon"
                viewBox="0 0 24 24"
                width="16"
                height="16"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
              >
                <path
                  d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"
                  fill="currentColor"
                /></svg
              >Testimoni</a
            >
            <a class="nav-link" href="#contact"
              ><svg
                class="icon"
                viewBox="0 0 24 24"
                width="16"
                height="16"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
              >
                <path
                  d="M21 8V7l-3 2-2-1-6 6 1 2 2-1 6-6 1 2 3-2zM3 21h18v-2H3v2z"
                  fill="currentColor"
                /></svg
              >Kontak</a
            >
            <?php if ($isAdmin): ?>
            <a class="nav-link admin-link" href="admin.php" title="Admin Panel - <?php echo htmlspecialchars($adminName); ?>"
              ><svg
                class="icon"
                viewBox="0 0 24 24"
                width="16"
                height="16"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
              >
                <path
                  d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"
                  fill="currentColor"
                /></svg
              >Admin Panel</a
            >
            <?php endif; ?>
          </div>

          <div class="toggle-cont">
            <input
              class="toggle-input"
              id="themeToggle"
              name="themeToggle"
              type="checkbox"
            />
            <label class="toggle-label" for="themeToggle">
              <div class="cont-icon">
                <span
                  style="--width: 2; --deg: 25; --duration: 11"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 100; --duration: 18"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 280; --duration: 5"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 200; --duration: 3"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 30; --duration: 20"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 300; --duration: 9"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 250; --duration: 4"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 210; --duration: 8"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 100; --duration: 9"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 15; --duration: 13"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 75; --duration: 18"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 65; --duration: 6"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 50; --duration: 7"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 320; --duration: 5"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 220; --duration: 5"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 215; --duration: 2"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 135; --duration: 9"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 45; --duration: 4"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 78; --duration: 16"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 89; --duration: 19"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 65; --duration: 14"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 97; --duration: 1"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 174; --duration: 10"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 236; --duration: 5"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 1; --deg: 215; --duration: 2"
                  class="sparkle"
                ></span>
                <span
                  style="--width: 2; --deg: 135; --duration: 9"
                  class="sparkle"
                ></span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 30 30"
                  class="icon"
                >
                  <path
                    d="M0.96233 28.61C1.36043 29.0081 1.96007 29.1255 2.47555 28.8971L10.4256 25.3552C13.2236 24.11 16.4254 24.1425 19.2107 25.4401L27.4152 29.2747C27.476 29.3044 27.5418 29.3023 27.6047 29.32C27.6563 29.3348 27.7079 29.3497 27.761 29.3574C27.843 29.3687 27.9194 29.3758 28 29.3688C28.1273 29.3617 28.2531 29.3405 28.3726 29.2945C28.4447 29.262 28.5162 29.2287 28.5749 29.1842C28.6399 29.1446 28.6993 29.0994 28.7509 29.0477L28.9008 28.8582C28.9468 28.7995 28.9793 28.7274 29.0112 28.656C29.0599 28.5322 29.0811 28.4036 29.0882 28.2734C29.0939 28.1957 29.0868 28.1207 29.0769 28.0415C29.0705 27.9955 29.0585 27.9524 29.0472 27.9072C29.0295 27.8343 29.0302 27.7601 28.9984 27.6901L25.1638 19.4855C23.8592 16.7073 23.8273 13.5048 25.0726 10.7068L28.6145 2.75679C28.8429 2.24131 28.7318 1.63531 28.3337 1.2372C27.9165 0.820011 27.271 0.721743 26.7491 0.9961L19.8357 4.59596C16.8418 6.15442 13.2879 6.18696 10.2615 4.70062L1.80308 0.520214C1.7055 0.474959 1.60722 0.441742 1.50964 0.421943C1.44459 0.409215 1.37882 0.395769 1.3074 0.402133C1.14406 0.395769 0.981436 0.428275 0.818095 0.499692C0.77284 0.519491 0.719805 0.545671 0.67455 0.578198C0.596061 0.617088 0.524653 0.675786 0.4596 0.74084C0.394546 0.805894 0.335843 0.877306 0.296245 0.956502C0.263718 1.00176 0.237561 1.05477 0.217762 1.10003C0.152708 1.24286 0.126545 1.40058 0.120181 1.54978C0.120181 1.61483 0.126527 1.6735 0.132891 1.73219C0.15269 1.85664 0.178881 1.97332 0.237571 2.08434L4.41798 10.5427C5.91139 13.5621 5.8725 17.1238 4.3204 20.1099L0.720514 27.0233C0.440499 27.5536 0.545137 28.1928 0.96233 28.61Z"
                  ></path>
                </svg>
                <!-- Cloud elements for light mode -->
                <div class="cloud-elements">
                  <svg
                    class="cloud cloud1"
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"
                    />
                  </svg>
                  <svg
                    class="cloud cloud2"
                    viewBox="0 0 24 24"
                    width="16"
                    height="16"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"
                    />
                  </svg>
                  <svg
                    class="cloud cloud3"
                    viewBox="0 0 24 24"
                    width="18"
                    height="18"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"
                    />
                  </svg>
                </div>
                <!-- Star elements for dark mode -->
                <div class="star-elements">
                  <svg
                    class="star star1"
                    viewBox="0 0 24 24"
                    width="12"
                    height="12"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                  <svg
                    class="star star2"
                    viewBox="0 0 24 24"
                    width="10"
                    height="10"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                  <svg
                    class="star star3"
                    viewBox="0 0 24 24"
                    width="14"
                    height="14"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                  <svg
                    class="star star4"
                    viewBox="0 0 24 24"
                    width="8"
                    height="8"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                  <svg
                    class="star star5"
                    viewBox="0 0 24 24"
                    width="11"
                    height="11"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="currentColor"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                </div>
                <!-- small knob icons: sun / moon (will be shown/hidden by CSS) -->
                <svg
                  class="knob-sun"
                  viewBox="0 0 24 24"
                  width="14"
                  height="14"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                >
                  <path
                    fill="currentColor"
                    d="M12 4.5a1 1 0 010-2 1 1 0 010 2zm0 17a1 1 0 010-2 1 1 0 010 2zM4.5 12a1 1 0 01-2 0 1 1 0 012 0zm17 0a1 1 0 01-2 0 1 1 0 012 0zM6.22 6.22a1 1 0 011.41 0 1 1 0 01-1.41 0zM16.36 16.36a1 1 0 011.41 0 1 1 0 01-1.41 0zM16.36 7.64a1 1 0 011.41 0 1 1 0 01-1.41 0zM6.22 17.78a1 1 0 011.41 0 1 1 0 01-1.41 0zM12 8a4 4 0 100 8 4 4 0 000-8z"
                  />
                </svg>
                <svg
                  class="knob-moon"
                  viewBox="0 0 24 24"
                  width="14"
                  height="14"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                >
                  <path
                    fill="currentColor"
                    d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"
                  />
                </svg>
              </div>
            </label>
          </div>

          <!-- User Dropdown -->
          <div class="user-dropdown-container">
            <button class="user-dropdown-btn" id="userDropdownBtn" aria-label="User menu">
              <svg class="user-icon" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="currentColor"/>
              </svg>
            </button>

            <div class="user-dropdown-menu" id="userDropdownMenu">
              <?php if ($isLoggedIn): ?>
                <!-- Logged in user -->
                <div class="dropdown-header">
                  <div class="dropdown-user-info">
                    <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="currentColor"/>
                    </svg>
                    <div>
                      <div class="dropdown-username"><?php echo htmlspecialchars($userName); ?></div>
                      <div class="dropdown-role"><?php echo $isAdmin ? 'Administrator' : 'User'; ?></div>
                    </div>
                  </div>
                </div>
                <?php if ($isAdmin): ?>
                  <div class="dropdown-divider"></div>
                  <a href="admin.php" class="dropdown-item">
                    <svg class="dropdown-icon" viewBox="0 0 24 24" width="18" height="18">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" fill="currentColor"/>
                    </svg>
                    Admin Panel
                  </a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item logout-item" onclick="return confirm('Yakin ingin logout?')">
                  <svg class="dropdown-icon" viewBox="0 0 24 24" width="18" height="18">
                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/>
                  </svg>
                  Logout
                </a>
              <?php else: ?>
                <!-- Not logged in -->
                <a href="login.php" class="dropdown-item">
                  <svg class="dropdown-icon" viewBox="0 0 24 24" width="18" height="18">
                    <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z" fill="currentColor"/>
                  </svg>
                  Sign In
                </a>
                <a href="register.php" class="dropdown-item">
                  <svg class="dropdown-icon" viewBox="0 0 24 24" width="18" height="18">
                    <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
                  </svg>
                  Register
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <!-- Theme toggle moved out of .nav-right so it remains visible on mobile -->
      </div>
    </nav>

    <div class="side-menu-overlay" id="sideMenuOverlay"></div>
    <div class="side-menu" id="sideMenu" aria-hidden="true">
      <div class="side-menu-header">
        <h3>PadiMart</h3>
        <button class="close-menu" id="closeMenuBtn" aria-label="Close menu">
          ✕
        </button>
      </div>
      <div class="side-menu-content">
        <ul class="menu-list">
          <li><a href="#home">Beranda</a></li>
          <li><a href="#products">Produk</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#contact">Kontak</a></li>
          <?php if ($isAdmin): ?>
          <li><a href="admin.php" style="color: #667eea; font-weight: 600;">🔐 Admin Panel</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- Hero -->
    <section class="hero-section" id="home">
      <div class="hero-background"></div>
      <div class="hero-overlay"></div>
      <div class="hero-content container">
        <div>
          <h1 class="hero-title">Pemasaran Digital untuk Petani Lokal</h1>
          <p class="hero-description">
            PADI MART (Pemasaran Digital Meningkatkan Kesejahteraan Petani dan
            Pelaku Usaha Produk Turunan Agribisnis) adalah platform digital
            untuk memperkenalkan dan memasarkan produk lokal seperti beras,
            camilan, dan kerajinan tangan, sekaligus menjadi media branding bagi
            pelaku usaha agribisnis.
          </p>
          <div class="hero-buttons">
            <a href="#products" class="btn-primary-custom">Lihat Katalog</a>
            <a href="#about" class="btn-secondary-custom">Tentang Kami</a>
          </div>
        </div>
      </div>
      <div class="scroll-indicator" aria-hidden="true">
        <svg
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M12 5V19M12 19L19 12M12 19L5 12"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
    </section>

    <!-- About + Video -->
    <section id="about" class="section container">
      <div class="about-grid">
        <div class="about-text">
          <h2>Profil Singkat</h2>

          <p>
            PADI MART (Pemasaran Digital untuk Produk Turunan Pertanian) adalah program pengembangan usaha berbasis teknologi yang bertujuan untuk meningkatkan nilai tambah dan daya jual produk turunan hasil pertanian. 
            Melalui program ini, para petani, pelaku UMKM, dan kelompok perempuan tani diberikan pendampingan dalam hal pengolahan produk, branding, serta strategi pemasaran digital agar mampu bersaing di pasar modern.
          </p>
          <br>
          <p>
            Program ini tidak hanya fokus pada peningkatan keterampilan produksi, tetapi juga membuka akses pemasaran yang lebih luas melalui platform digital seperti marketplace, media sosial, dan katalog online. 
            Dengan memanfaatkan teknologi, PADI MART membantu pelaku usaha meningkatkan kualitas kemasan, membangun identitas brand, serta mempromosikan produk unggulan pertanian secara profesional.
          </p>
          <br>
          <p>
            Melalui kegiatan pelatihan, pendampingan, dan praktik langsung, 
            PADI MART berkomitmen mendorong kemandirian ekonomi lokal, memperluas pasar, dan meningkatkan pendapatan masyarakat 
            melalui optimalisasi produk turunan pertanian yang inovatif, higienis, dan bernilai jual tinggi.
          </p>
          <br>
          <p>
            Target pengunjung: masyarakat umum, toko, dan instansi. Sistem admin
            untuk pengelolaan konten sedang dalam perencanaan dan akan
            dikembangkan lebih lanjut.
          </p>
        </div>

        <div class="about-video">
          <!-- Responsive YouTube embed (replace with actual channel video id) -->
          <div class="video-wrapper">
            <iframe
              id="profileVideo"
              src="https://www.youtube.com/embed/MTqNC1MBX2Y"
              title="PADI MART Profile"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </section>

    <!-- Products -->
    <section id="products" class="section container">
      <div class="section-header">
        <h2>Katalog Produk</h2>
        <p class="muted">
          Temukan produk lokal — gunakan filter, cari, dan hubungi penjual
          langsung melalui WhatsApp.
        </p>
      </div>

      <div class="product-controls">
        <input
          id="searchInput"
          type="search"
          placeholder="Cari produk, misal: beras, camilan"
        />
        <select id="categoryFilter">
          <option value="all">Semua Kategori</option>
          <option value="beras">Beras</option>
          <option value="camilan">Camilan & Olahan</option>
          <option value="kerajinan">Kerajinan & Oleh-oleh</option>
        </select>
        <select id="sortSelect">
          <option value="popular">Terpopuler</option>
          <option value="new">Terbaru</option>
          <option value="price_asc">Harga: Rendah ke Tinggi</option>
          <option value="price_desc">Harga: Tinggi ke Rendah</option>
        </select>
      </div>
      <div id="productGrid" class="product-grid" aria-live="polite"></div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="section container" aria-label="Testimoni Pelanggan">
      <div class="section-header">
        <h2>Testimoni Pelanggan</h2>
        <p class="muted">Dengarkan pengalaman pelanggan kami yang puas dengan produk dan layanan PADI MART</p>
      </div>
      <div class="testimonials-grid" role="list">
        <article class="testimonial" role="listitem">
          <div class="testimonial-header">
            <div class="testimonial-avatar" style="background: linear-gradient(135deg, #58c234 0%, #58b530 100%);" aria-label="Avatar Siti Nurhaliza">
              <span aria-hidden="true">S</span>
            </div>
            <div class="testimonial-meta">
              <h4 class="testimonial-name">Siti Nurhaliza</h4>
              <p class="testimonial-role">Pembeli Reguler</p>
            </div>
          </div>
          <div class="testimonial-rating" aria-label="Rating 5 dari 5 bintang">
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
          </div>
          <blockquote class="testimonial-text">
            <p>"Produk berkualitas, pengiriman cepat! Saya sangat puas dengan layanan PADI MART. Beras yang saya pesan selalu fresh dan dikemas dengan baik."</p>
          </blockquote>
        </article>
        <article class="testimonial" role="listitem">
          <div class="testimonial-header">
            <div class="testimonial-avatar" style="background: linear-gradient(135deg, #ed9f24 0%, #e8a535 100%);" aria-label="Avatar Budi Santoso">
              <span aria-hidden="true">B</span>
            </div>
            <div class="testimonial-meta">
              <h4 class="testimonial-name">Budi Santoso</h4>
              <p class="testimonial-role">Pemilik Toko</p>
            </div>
          </div>
          <div class="testimonial-rating" aria-label="Rating 4 dari 5 bintang">
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star" aria-hidden="true">★</span>
          </div>
          <blockquote class="testimonial-text">
            <p>"Banyak pilihan lokal, harga bersaing. Platform ini memudahkan saya menemukan supplier berkualitas dengan harga yang kompetitif."</p>
          </blockquote>
        </article>
        <article class="testimonial" role="listitem">
          <div class="testimonial-header">
            <div class="testimonial-avatar" style="background: linear-gradient(135deg, #58c234 0%, #ed9f24 100%);" aria-label="Avatar Toko Makmur">
              <span aria-hidden="true">T</span>
            </div>
            <div class="testimonial-meta">
              <h4 class="testimonial-name">Toko Makmur</h4>
              <p class="testimonial-role">Usaha Kecil Menengah</p>
            </div>
          </div>
          <div class="testimonial-rating" aria-label="Rating 5 dari 5 bintang">
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
          </div>
          <blockquote class="testimonial-text">
            <p>"Membantu pelaku usaha kami dikenal lebih luas. Berkat PADI MART, produk kami dapat menjangkau pasar yang lebih besar dan meningkatkan penjualan."</p>
          </blockquote>
        </article>
        <article class="testimonial" role="listitem">
          <div class="testimonial-header">
            <div class="testimonial-avatar" style="background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);" aria-label="Avatar Rina Wijaya">
              <span aria-hidden="true">R</span>
            </div>
            <div class="testimonial-meta">
              <h4 class="testimonial-name">Rina Wijaya</h4>
              <p class="testimonial-role">Reseller Produk Organik</p>
            </div>
          </div>
          <div class="testimonial-rating" aria-label="Rating 5 dari 5 bintang">
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
          </div>
          <blockquote class="testimonial-text">
            <p>"Sistem pembayaran yang aman dan terpercaya. Saya sangat menghargai transparansi harga dan kualitas produk yang konsisten dari PADI MART setiap kali saya berbelanja."</p>
          </blockquote>
        </article>
        <article class="testimonial" role="listitem">
          <div class="testimonial-header">
            <div class="testimonial-avatar" style="background: linear-gradient(135deg, #dc2626 0%, #f97316 100%);" aria-label="Avatar Ahmad Hidayat">
              <span aria-hidden="true">A</span>
            </div>
            <div class="testimonial-meta">
              <h4 class="testimonial-name">Ahmad Hidayat</h4>
              <p class="testimonial-role">Distributor Beras Premium</p>
            </div>
          </div>
          <div class="testimonial-rating" aria-label="Rating 5 dari 5 bintang">
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
            <span class="star filled" aria-hidden="true">★</span>
          </div>
          <blockquote class="testimonial-text">
            <p>"Layanan pelanggan yang responsif dan membantu. Tim PADI MART selalu siap menjawab pertanyaan saya dan memberikan solusi terbaik untuk kebutuhan bisnis saya."</p>
          </blockquote>
        </article>
      </div>
    </section>

    <!-- Contact / Footer -->
    <footer id="contact" class="section footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-grid">
            <!-- Brand Section -->
            <div class="footer-section footer-brand">
              <div class="footer-logo">
                <img src="img/PADI%20MART.png" alt="PADI MART" class="footer-brand-logo" />
              </div>
              <p class="footer-description">
                Platform digital untuk memperkenalkan dan memasarkan produk lokal berkualitas dari petani dan pelaku usaha agribisnis.
              </p>
              <div class="footer-social">
                <a href="https://wa.me/628123456789" target="_blank" rel="noopener" aria-label="WhatsApp" class="social-link">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.391-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.255.949c-1.238.503-2.335 1.236-3.356 2.259-1.02 1.02-1.756 2.119-2.259 3.357-.606 1.605-.949 3.362-.949 5.209 0 1.551.203 3.087.6 4.571.793 2.901 2.478 5.41 4.744 7.224 1.7 1.372 3.71 2.301 5.942 2.712 1.52.288 3.11.288 4.63 0 2.232-.411 4.242-1.34 5.942-2.712 2.266-1.814 3.951-4.323 4.744-7.224.397-1.484.6-3.02.6-4.571 0-1.847-.343-3.604-.949-5.209-.503-1.238-1.239-2.337-2.259-3.357-1.02-1.023-2.118-1.756-3.357-2.259-1.605-.606-3.362-.949-5.209-.949z"/>
                  </svg>
                </a>
                <a href="mailto:info@padimart.example" aria-label="Email" class="social-link">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                  </svg>
                </a>
              </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
              <h4 class="footer-title">Navigasi</h4>
              <ul class="footer-links">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#products">Katalog Produk</a></li>
                <li><a href="#testimonials">Testimoni</a></li>
              </ul>
            </div>

            <!-- Information -->
            <div class="footer-section">
              <h4 class="footer-title">Informasi</h4>
              <ul class="footer-info">
                <li>
                  <strong>Lokasi:</strong>
                  <span>Tabalong, Kalimantan Selatan</span>
                </li>
                <li>
                  <strong>Email:</strong>
                  <a href="mailto:info@padimart.example">info@padimart.example</a>
                </li>
                <li>
                  <strong>Telepon:</strong>
                  <a href="tel:+628123456789">+62 812-3456-789</a>
                </li>
              </ul>
            </div>

            <!-- Operating Hours -->
            <div class="footer-section">
              <h4 class="footer-title">Jam Operasional</h4>
              <ul class="footer-hours">
                <li>
                  <span class="day">Senin - Jumat</span>
                  <span class="time">08:00 - 17:00</span>
                </li>
                <li>
                  <span class="day">Sabtu</span>
                  <span class="time">09:00 - 14:00</span>
                </li>
                <li>
                  <span class="day">Minggu</span>
                  <span class="time">Tutup</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Footer Bottom -->
          <div class="footer-bottom">
            <div class="footer-divider"></div>
            <div class="footer-bottom-content">
              <p class="copyright">
                © 2025 PADI MART. Semua hak dilindungi. Platform digital untuk petani dan pelaku usaha lokal.
              </p>
              <div class="footer-bottom-links">
                <a href="#" class="footer-link-small">Kebijakan Privasi</a>
                <span class="divider">•</span>
                <a href="#" class="footer-link-small">Syarat & Ketentuan</a>
                <span class="divider">•</span>
                <a href="#" class="footer-link-small">Hubungi Kami</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Product Detail Modal -->
    <div id="productModal" class="product-modal" aria-hidden="true">
      <div class="modal-overlay"></div>
      <div class="modal-content">
        <button class="modal-close" aria-label="Tutup modal">
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>

        <div class="modal-body">
          <div class="modal-image-section">
            <div class="modal-image" id="modalImage"></div>
            <div class="modal-badge" id="modalBadge" style="display: none">
              POPULER
            </div>
          </div>

          <div class="modal-info-section">
            <div class="modal-header">
              <h2 class="modal-title" id="modalTitle">Nama Produk</h2>
              <div class="modal-category" id="modalCategory">Kategori</div>
            </div>

            <div class="modal-price-section">
              <div class="modal-price" id="modalPrice">Rp 0</div>
              <div class="modal-stock-status">
                <span class="stock-indicator available"></span>
                <span>Tersedia</span>
              </div>
            </div>

            <div class="modal-description">
              <h3>Deskripsi Produk</h3>
              <p id="modalDescription">
                Deskripsi produk akan ditampilkan di sini. Informasi lengkap
                tentang kualitas, asal, dan keunggulan produk.
              </p>
            </div>

            <div class="modal-details">
              <h3>Detail Produk</h3>
              <div class="detail-grid">
                <div class="detail-item">
                  <span class="detail-label">Kategori:</span>
                  <span class="detail-value" id="modalDetailCategory">-</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Berat:</span>
                  <span class="detail-value" id="modalWeight">1 kg</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Asal:</span>
                  <span class="detail-value" id="modalOrigin"
                    >Tabalong, Kalimantan Selatan</span
                  >
                </div>
                <div class="detail-item">
                  <span class="detail-label">Kondisi:</span>
                  <span class="detail-value" id="modalCondition">Baru</span>
                </div>
              </div>
            </div>

            <div class="modal-seller">
              <h3>Informasi Penjual</h3>
              <div class="seller-info">
                <div class="seller-avatar">
                  <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </div>
                <div class="seller-details">
                  <div class="seller-name" id="modalSellerName">
                    Petani Lokal
                  </div>
                  <div class="seller-location">
                    Tabalong, Kalimantan Selatan
                  </div>
                  <div class="seller-rating">
                    <div class="stars">
                      <span class="star filled">★</span>
                      <span class="star filled">★</span>
                      <span class="star filled">★</span>
                      <span class="star filled">★</span>
                      <span class="star">★</span>
                    </div>
                    <span class="rating-text">4.0 (25 ulasan)</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-actions">
              <button class="btn-whatsapp" id="modalWhatsappBtn">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path
                    d="M21 15a4 4 0 0 1-4 4H7l-4 4V5a2 2 0 0 1 2-2h14a4 4 0 0 1 4 4z"
                  ></path>
                </svg>
                Chat via WhatsApp
              </button>
              <button class="btn-secondary" id="modalShareBtn">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <circle cx="18" cy="5" r="3"></circle>
                  <circle cx="6" cy="12" r="3"></circle>
                  <circle cx="18" cy="19" r="3"></circle>
                  <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                  <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
                Bagikan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- WhatsApp Floating Button (Uiverse style) -->
    <a
      id="whatsappBtn"
      class="uiverse-whatsapp"
      href="https://wa.me/628123456789"
      target="_blank"
      rel="noopener"
      aria-label="Chat via WhatsApp"
    >
      <div class="Btn" role="button" aria-hidden="false">
        <div class="sign">
          <svg
            class="socialSvg whatsappSvg"
            viewBox="0 0 16 16"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
          >
            <path
              d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"
            ></path>
          </svg>
        </div>
        <div class="text">Whatsapp</div>
      </div>
    </a>

    <script src="js/config.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/products-data.js"></script>
    <script src="js/landingpage.js"></script>

    <script>
      // User Dropdown Toggle
      document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('userDropdownBtn');
        const dropdownMenu = document.getElementById('userDropdownMenu');

        if (dropdownBtn && dropdownMenu) {
          // Toggle dropdown on button click
          dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
          });

          // Close dropdown when clicking outside
          document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
              dropdownMenu.classList.remove('show');
            }
          });

          // Close dropdown when clicking a link inside (except for items that need confirmation)
          dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
              // Small delay to allow navigation
              setTimeout(() => {
                dropdownMenu.classList.remove('show');
              }, 100);
            });
          });
        }
      });
    </script>
  </body>
</html>
