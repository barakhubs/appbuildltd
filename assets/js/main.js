// AppBuild Ltd. Website JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const mobileMenuButton = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
      mobileMenu.classList.toggle("show");

      // Toggle icon
      const icon = mobileMenuButton.querySelector("i");
      if (icon.classList.contains("fa-bars")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Header scroll effect
  let lastScrollTop = 0;
  const header = document.querySelector("header");

  window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Add/remove shadow based on scroll position
    if (scrollTop > 10) {
      header.classList.add("header-shadow");
    } else {
      header.classList.remove("header-shadow");
    }

    lastScrollTop = scrollTop;
  });

  // Contact form handling
  const contactForm = document.getElementById("contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(contactForm);
      const submitButton = contactForm.querySelector('button[type="submit"]');
      const buttonText = submitButton.innerHTML;

      // Show loading state
      submitButton.innerHTML = '<span class="loading"></span> Sending...';
      submitButton.disabled = true;

      fetch("process-contact.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showNotification(
              "Thank you! Your message has been sent successfully.",
              "success"
            );
            contactForm.reset();
          } else {
            showNotification(
              data.message || "An error occurred. Please try again.",
              "error"
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("An error occurred. Please try again.", "error");
        })
        .finally(() => {
          // Restore button
          submitButton.innerHTML = buttonText;
          submitButton.disabled = false;
        });
    });
  }

  // Newsletter subscription
  const newsletterForm = document.querySelector("footer form");
  if (newsletterForm) {
    newsletterForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = this.querySelector('input[type="email"]').value;

      if (validateEmail(email)) {
        showNotification(
          "Thank you for subscribing to our newsletter!",
          "success"
        );
        this.reset();
      } else {
        showNotification("Please enter a valid email address.", "error");
      }
    });
  }

  // Image lazy loading
  const images = document.querySelectorAll("img[data-src]");

  if (images.length > 0) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove("img-placeholder");
          observer.unobserve(img);
        }
      });
    });

    images.forEach((img) => {
      img.classList.add("img-placeholder");
      imageObserver.observe(img);
    });
  }

  // Search functionality for blog/projects
  const searchInput = document.getElementById("search-input");
  if (searchInput) {
    let searchTimeout;

    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      const query = this.value.toLowerCase();

      searchTimeout = setTimeout(() => {
        const items = document.querySelectorAll(".searchable-item");

        items.forEach((item) => {
          const title = item
            .querySelector(".item-title")
            .textContent.toLowerCase();
          const description = item
            .querySelector(".item-description")
            .textContent.toLowerCase();

          if (
            title.includes(query) ||
            description.includes(query) ||
            query === ""
          ) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });

        // Show "no results" message if needed
        const visibleItems = document.querySelectorAll(
          '.searchable-item[style*="block"], .searchable-item:not([style])'
        );
        const noResults = document.getElementById("no-results");

        if (visibleItems.length === 0 && query !== "") {
          if (noResults) {
            noResults.style.display = "block";
          }
        } else {
          if (noResults) {
            noResults.style.display = "none";
          }
        }
      }, 300);
    });
  }

  // Category filtering
  const categoryFilters = document.querySelectorAll(".category-filter");
  if (categoryFilters.length > 0) {
    categoryFilters.forEach((filter) => {
      filter.addEventListener("click", function (e) {
        e.preventDefault();

        const category = this.dataset.category;
        const items = document.querySelectorAll(".filterable-item");

        // Update active filter
        categoryFilters.forEach((f) => f.classList.remove("active"));
        this.classList.add("active");

        // Filter items
        items.forEach((item) => {
          const itemCategory = item.dataset.category;

          if (category === "all" || itemCategory === category) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });
      });
    });
  }

  // Testimonial slider
  const testimonialSlider = document.querySelector(".testimonial-slider");
  if (testimonialSlider) {
    let currentSlide = 0;
    const slides = testimonialSlider.querySelectorAll(".testimonial-slide");
    const totalSlides = slides.length;

    function showSlide(index) {
      slides.forEach((slide) => slide.classList.remove("active"));
      slides[index].classList.add("active");
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      showSlide(currentSlide);
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      showSlide(currentSlide);
    }

    // Auto-advance testimonials
    setInterval(nextSlide, 5000);

    // Navigation buttons
    const nextButton = document.querySelector(".testimonial-next");
    const prevButton = document.querySelector(".testimonial-prev");

    if (nextButton) nextButton.addEventListener("click", nextSlide);
    if (prevButton) prevButton.addEventListener("click", prevSlide);
  }

  // Scroll-to-top button
  const scrollTopButton = document.getElementById("scroll-to-top");
  if (scrollTopButton) {
    window.addEventListener("scroll", function () {
      if (window.pageYOffset > 300) {
        scrollTopButton.style.display = "block";
      } else {
        scrollTopButton.style.display = "none";
      }
    });

    scrollTopButton.addEventListener("click", function () {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Admin WYSIWYG editor initialization (if TinyMCE is loaded)
  if (typeof tinymce !== "undefined") {
    tinymce.init({
      selector: ".wysiwyg-editor",
      height: 400,
      plugins:
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      toolbar_mode: "sliding",
      toolbar:
        "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile image media template link anchor codesample | ltr rtl",
      content_style:
        "body { font-family: Arial, sans-serif; font-size: 14px; }",
    });
  }
});

// Utility functions
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function showNotification(message, type = "info") {
  // Create notification element
  const notification = document.createElement("div");
  notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

  // Set color based on type
  switch (type) {
    case "success":
      notification.className += " bg-green-500 text-white";
      break;
    case "error":
      notification.className += " bg-red-500 text-white";
      break;
    case "warning":
      notification.className += " bg-yellow-500 text-white";
      break;
    default:
      notification.className += " bg-blue-500 text-white";
  }

  notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.remove("translate-x-full");
  }, 100);

  // Auto-hide after 5 seconds
  setTimeout(() => {
    notification.classList.add("translate-x-full");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);
}

function formatPhoneNumber(input) {
  // Format phone number as (XXX) XXX-XXXX
  const value = input.value.replace(/\D/g, "");
  const formattedValue = value.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
  input.value = formattedValue;
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Google Analytics (replace with your tracking ID)
// gtag('config', 'GA_TRACKING_ID');

// Performance monitoring
if ("performance" in window) {
  window.addEventListener("load", function () {
    setTimeout(function () {
      const perfData = window.performance.timing;
      const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;

      // Log performance data (you can send this to your analytics)
      console.log("Page load time:", pageLoadTime + "ms");
    }, 0);
  });
}
