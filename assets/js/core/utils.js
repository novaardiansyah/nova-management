$(document).ready(function() {
  console.log('Script is Ready!')
})

$(document).on('input focus change blur', '.is-invalid', function() {
  $(this).removeClass('is-invalid')
})

function alerts(params = {})
{
  let { text = 'An internal error occurred, please try again.', timer = 2000, timerProgressBar = true, confirmButtonText = 'Yes, Continue', cancelButtonText = 'Cancelled', confirmButtonClass = 'btn-danger', cancelButtonClass = 'btn-secondary', type = 'error', needConfirm = false, close = () => false, confirm = () => false } = params

  let icon = type

  if (needConfirm) {
    Swal.fire({
      html: `<p class="text-center pb-0 mb-0" style="font-size: .95rem">${stripHtml(text)}</p>`,
      icon: icon,
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonText: confirmButtonText,
      cancelButtonText: cancelButtonText,
      confirmButtonClass: `btn btn-sm ml-2 ${confirmButtonClass}`,
      cancelButtonClass: `btn btn-sm ${cancelButtonClass}`,
      buttonsStyling: false,
      reverseButtons: true,
      willClose: () => {
        close
      },
      preConfirm: () => {
        confirm()
      }
    })

    return true
  }

  Swal.fire({
    html: `<p class="text-center pb-0 mb-0" style="font-size: .95rem">${stripHtml(text)}</p>`,
    icon: icon,
    showCancelButton: false,
    showConfirmButton: false,
    timer: timer,
    timerProgressBar: timerProgressBar,
    willClose: close
  })
}

function stripHtml(html)
{
  let tmp = document.createElement('DIV')
  tmp.innerHTML = html
  return tmp.textContent || tmp.innerText || ''
}

function redirect(path = '', after = 0)
{
  setTimeout(() => {
    window.location.href = path
  }, after);
}

function base_url(path = '')
{
  return config.base_url + path
}

function cut_word(str, length) {
  let temp = str.split(' ');
  temp = temp.slice(0, length);
  temp = temp.join(' ');
  
  if (temp.length < str.length) {
    temp += '...';
  }

  return temp;
}

function onlyNumber(event) {
  let key = event.key;
  let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

  if (isMobile) {
    if (key === "Backspace" || key === "Tab" || key === "Delete") {
      return true;
    } else if (key < "0" || key > "9") {
      return false;
    }
  } else {
    let keyCode = event.which || event.keyCode;
    if (keyCode === 8 || keyCode === 9 || keyCode === 46) {
      return true;
    } else if (keyCode < 48 || keyCode > 57) {
      return false;
    }
  }
  return true;
}

function max_length(event, length)
{
  let value = event.target.value;
  if (value.length > length) {
    event.target.value = value.slice(0, length);
  }
}