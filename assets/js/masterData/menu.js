document.addEventListener("DOMContentLoaded", function() { 
}, false);

function addData()
{
  const { form, url, method, formData } = setupForm('form-addData', 'formData');
  
  let response = fetch(url, {
    method: method,
    body: formData
  }).then((response) => response.text());
  
  response.then((callback) => {
    callback = JSON.parse(callback);

    if (callback.status == true && callback.message !== undefined)
    {
      toggleModal('addData', 'hide');

      let data = callback.data;
      document.querySelector(`[name="${startup.crlf_name}"]`).value = data.csrf_renewed;  

      Toastify({
        text: stripHtml(callback.message),
        duration: 5000,
        close: true,
        style: {
          background: startup.colors.info,
        }
      }).showToast();

      return true;
    }

    if (callback.status == false && callback.message !== undefined)
    {
      Toastify({
        text: stripHtml(callback.message),
        duration: 5000,
        close: true,
        style: {
          background: startup.colors.danger,
        }
      }).showToast();

      return false;
    }
    
    Toastify({
      text: 'Terjadi Kesalahan Internal (000a).',
      duration: 5000,
      close: true,
      style: {
        background: startup.colors.danger,
      }
    }).showToast();
  })
    .catch(() => {
      Toastify({
        text: 'Terjadi Kesalahan Internal (000b).',
        duration: 5000,
        close: true,
        style: {
          background: startup.colors.danger,
        }
      }).showToast();
    });
}

function toggleModal(selector, type = 'show')
{
  if (type == 'hide') {
    return document.querySelector(`#${selector}-close`).click();
  }

  return document.querySelector(`#${selector}-show`).click();
}