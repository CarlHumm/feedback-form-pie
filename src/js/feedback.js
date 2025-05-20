export default class FeedbackForm {
  constructor(formSelector) {
    this.isWordPress = import.meta.env.VITE_ENV === "wordpress";

    this.ajaxUrl = this.isWordPress
      ? window.FeedbackForm?.ajax_url
      : `submit.php`;

    this.nonce = this.isWordPress ? window.FeedbackForm?.nonce : null;

    this.form = document.querySelector(formSelector);
    this.emailInput = this.form.querySelector("#email");
    this.messageInput = this.form.querySelector("#message");
    this.inputs = this.form.querySelectorAll("input, textarea");
    this.indicator = this.form.querySelector(".feedback__progress-indicator");
    this.successDialog = document.querySelector(".feedback__success");
    this.emailRegExp = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    this.submitButton = this.form.querySelector(".feedback__button");
    this.currentIndicator = null;
    this.timer = null;

    this.init();
  }

  init() {
    this.form.addEventListener("submit", (e) => this.handleSubmit(e));
    this.emailInput.addEventListener("input", (e) => this.handleInput(e));
    this.messageInput.addEventListener("input", (e) => this.handleInput(e));
  }

  handleInput(e) {
    clearTimeout(this.timer);

    const target = e.target;
    const indicator = this.form.querySelector(
      `#${target.id} + .feedback__progress-indicator`
    );

    this.clearError(target);

    const isDifferentIndicator =
      this.currentIndicator && this.currentIndicator !== indicator;

    if (isDifferentIndicator) {
      this.currentIndicator.style.display = "none";
    }
    this.currentIndicator = indicator;

    this.form.querySelector(
      `#${target.id} + .feedback__progress-indicator`
    ).style.display = "flex";

    this.timer = setTimeout(() => {
      this.validateField(target);
      this.updateSubmitButtonState();
      this.form.querySelector(
        `#${target.id} + .feedback__progress-indicator`
      ).style.display = "none";
    }, 1000);
  }

  async handleSubmit(e) {
    e.preventDefault();

    if (this.isFormValid()) {
      const bodyData = this.isWordPress
        ? new URLSearchParams({
            action: "submit_feedback",
            nonce: this.nonce,
            email: this.emailInput.value,
            message: this.messageInput.value,
          })
        : new URLSearchParams({
            email: this.emailInput.value,
            message: this.messageInput.value,
          });

      try {
        const response = await this.post(this.ajaxUrl, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: bodyData,
        });

if (response.success) {
  this.showSuccess("Success!", response.message, "completed");
} else {
  console.error(response);
  this.showSuccess("Error!", response.message, "failed");
}
      } catch (err) {
        console.error("AJAX Error:", err);
      }
    }
  }

  async post(url, options) {
    try {
      const response = await fetch(url, options);
      return await response.json();
    } catch (error) {
      console.error("Network error:", error);
    }
  }

  validateField(input) {
    const value = input.value.trim();
    const errorSpan = this.form.querySelector(`#${input.id}-error`);
    let error = "";

    if (input.id === "email") {
      if (!value) {
        error = "Email is required.";
      } else if (!this.emailRegExp.test(value)) {
        error = "Please enter a valid email address.";
      }
    }

    if (input.id === "message") {
      if (!value) {
        error = "Message is required.";
      } else if (value.length < 10) {
        error = "Message must be at least 10 characters.";
      }
    }

    const isValid = !error;

    if (error) {
      errorSpan.innerHTML = `<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                   viewBox="0 0 416.979 416.979"
                  xml:space="preserve">
                <g>
                  <path d="M356.004,61.156c-81.37-81.47-213.377-81.551-294.848-0.182c-81.47,81.371-81.552,213.379-0.181,294.85
                    c81.369,81.47,213.378,81.551,294.849,0.181C437.293,274.636,437.375,142.626,356.004,61.156z M237.6,340.786
                    c0,3.217-2.607,5.822-5.822,5.822h-46.576c-3.215,0-5.822-2.605-5.822-5.822V167.885c0-3.217,2.607-5.822,5.822-5.822h46.576
                    c3.215,0,5.822,2.604,5.822,5.822V340.786z M208.49,137.901c-18.618,0-33.766-15.146-33.766-33.765
                    c0-18.617,15.147-33.766,33.766-33.766c18.619,0,33.766,15.148,33.766,33.766C242.256,122.755,227.107,137.901,208.49,137.901z"/>
                </g>
                </svg>  ${error}`;
      errorSpan.style.display = "flex";
      input.setAttribute("aria-invalid", "true");
    } else {
      errorSpan.textContent = "";
      errorSpan.style.display = "none";
      input.removeAttribute("aria-invalid");
    }

    return isValid;
  }

  updateSubmitButtonState() {
    this.submitButton.disabled = !this.isFormValid();
  }

  isFormValid() {
    const inputs = this.form.querySelectorAll("input, textarea");

    for (let input of inputs) {
      const value = input.value.trim();

      if (input.id === "email" && (!value || !this.emailRegExp.test(value))) {
        return false;
      }

      if (input.id === "message" && (!value || value.length < 10)) {
        return false;
      }
    }
    return true;
  }

  clearError(input) {
    this.form.querySelector(`#${input.id}-error`).style.display = "none";
  }


showSuccess(title, message, statusClass) {
  this.successDialog.querySelector("h2").textContent = title;
  this.successDialog.querySelector("p").textContent = message;
  
  this.successDialog.classList.remove("completed", "failed");
  
  this.successDialog.classList.add(statusClass);

  this.successDialog.style.display = "flex";

  setTimeout(() => {
  this.form.reset();
  this.updateSubmitButtonState()
  this.successDialog.style.display = "none";
}, 3000);
}
}
