/**
 * mailapi/js/contactmail.js - STRINGS SPORTS Contact Mail Client
 */

function validateContactForm(formSelector) {
    var $form = $(formSelector || '#contact-form');
    var alertBox = '';

    var name = $form.find('[name="name"]').val();
    if (!name || name.trim() === '') {
        $form.find('[name="name"]').focus();
        alertBox = '<div class="alert alert-danger" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-triangle-exclamation"></i> <div>Please enter your full name or club organization.</div></div>';
        $form.find('#alert-msg').html(alertBox);
        return false;
    }

    var email = $form.find('[name="email"]').val();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email.trim())) {
        $form.find('[name="email"]').focus();
        alertBox = '<div class="alert alert-danger" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-triangle-exclamation"></i> <div>Please enter a valid email address.</div></div>';
        $form.find('#alert-msg').html(alertBox);
        return false;
    }

    var message = $form.find('[name="message"]').val();
    if (!message || message.trim() === '') {
        $form.find('[name="message"]').focus();
        alertBox = '<div class="alert alert-danger" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-triangle-exclamation"></i> <div>Please detail your order requirements or message.</div></div>';
        $form.find('#alert-msg').html(alertBox);
        return false;
    }

    return true;
}

function SendMessage() {
    var $form = $('#contact-form');
    if (!$form.length) return;

    if (!validateContactForm('#contact-form')) {
        return;
    }

    var $submitBtn = $form.find('button[type="submit"]');
    var originalBtnHtml = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin"></i> Transmitting Inquiry...');

    if (typeof load_overlay === 'function') {
        try { load_overlay(); } catch (e) {}
    }

    $.ajax({
        type: "POST",
        url: "mailapi/contact.php",
        data: $form.serialize(),
        dataType: "json",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        success: function (res) {
            if (typeof close_overlay === 'function') {
                try { close_overlay(); } catch (e) {}
            }
            $submitBtn.prop('disabled', false).html(originalBtnHtml);

            var messageText = res.message || "Thank you! Your inquiry has been received.";
            var alertBox = '<div class="alert alert-success" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-circle-check"></i> <div>' + messageText + '</div></div>';
            $form.find('#alert-msg').html(alertBox);
            $form[0].reset();

            // Smooth scroll to alert
            var alertEl = $form.find('#alert-msg')[0];
            if (alertEl) {
                alertEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        },
        error: function (xhr) {
            if (typeof close_overlay === 'function') {
                try { close_overlay(); } catch (e) {}
            }
            $submitBtn.prop('disabled', false).html(originalBtnHtml);

            var errorMsg = "Unable to transmit inquiry. Please try again or email us directly.";
            try {
                var json = JSON.parse(xhr.responseText);
                if (json && json.message) errorMsg = json.message;
            } catch (e) {
                if (xhr.responseText && xhr.responseText.length < 200) {
                    errorMsg = xhr.responseText;
                }
            }

            var alertBox = '<div class="alert alert-danger" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-triangle-exclamation"></i> <div>' + errorMsg + '</div></div>';
            $form.find('#alert-msg').html(alertBox);
        }
    });
}