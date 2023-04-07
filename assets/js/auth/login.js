$(document).ready(function() {
  generate_captcha()
})

function generate_captcha()
{
  $('.image.captcha').attr('src', '').fadeOut('slow')

  $.ajax({
    url: 'auth/generate_captcha',
    type: 'POST',
    data: { [config.csrf_token_name]: config.csrf_hash },
    dataType: 'json',
    success: function(response) {
      let url = config.base_url + 'assets/images/captcha/' + response.captcha.captcha_filename
      $('.image.captcha').attr('src', url).fadeIn('slow')
      config.csrf_hash = response.csrf
      localStorage.setItem('captcha', JSON.stringify(response.captcha))
    }
  });
}

function enter_captcha(event)
{
  let value = $(event.target).val()
  $(event.target).val(value.toUpperCase())
}

// * Login
function attempt_login(event = null)
{
  if (event) event.preventDefault()

  let form    = $(event.target).closest('form')
  let data    = new FormData(form[0])
  let captcha = JSON.parse(localStorage.getItem('captcha'))
  let url     = form.attr('action')

  if (captcha) {
    data.append('captcha[captcha_word]', captcha.captcha_word)
    data.append('captcha[captcha_time]', captcha.captcha_time)
  }

  data.append(config.csrf_token_name, config.csrf_hash)

  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: 'json',
    processData: false,
    contentType: false,
    beforeSend: function() {
      form.find('.invalid-feedback').html('').fadeOut('slow')
      form.find('.is-invalid').removeClass('is-invalid')
    },
    success: function(response) {
      config.csrf_hash = response.csrf
      
      if (response.status == false) {
        if (response.errors != undefined) {
          Object.keys(response.errors).forEach(function(key) {
            let input = form.find(`[name="${key}"]`)
            input.addClass('is-invalid')

            if (input.next().hasClass('input-group-append')) {
              input.next().after(`<div class="invalid-feedback ${key}">${response.errors[key]}</div>`)
            } else {
              input.after(`<div class="invalid-feedback ${key}">${response.errors[key]}</div>`)
            }

            form.find(`.invalid-feedback.${key}`)
          })

          if (response.errors.user_captcha != undefined) {
            captcha.count_attempt += 1
            localStorage.setItem('captcha', JSON.stringify(captcha))
  
            if (captcha.count_attempt >= 3) generate_captcha()
            return false
          }

          return false
        }


        alerts({ type: 'error', text: response.message, close: () => form.find('input[name="password"]').val(''), timer: 3000 })
      }

      if (response.status == true) {
        alerts({ type: 'success', text: response.message, close: () => redirect(response.redirect), timer: 3000 })
      }
    }
  });
}

function toggle_password(e)
{
  let element = $(`input[name="password"]`)
  let type = element.attr('type')

  e.target.classList.toggle('fa-eye-slash')
  e.target.classList.toggle('fa-eye')

  if (type == 'password') return element.attr('type', 'text')
  if (type == 'text') return element.attr('type', 'password')
}