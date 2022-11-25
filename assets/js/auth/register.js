function register()
{
  const { form, url, method, formData } = setupForm('form-register', 'formData');
  
  formData.append(startup.crlf_name, startup.crlf_token);
  
  let response = fetch(url, {
    method: method,
    body: formData
  }).then((response) => response.json());
  
  response.then((callback) => {
    let data = callback.data;
    console.log('callback:', callback);

    startup.crlf_token = data.csrf_renewed;

    if (callback.status == true && callback.message !== undefined)
    {
      Toastify({
        text: stripHtml(callback.message),
        duration: 3000,
        close: true,
        style: {
          background: startup.colors.info,
        }
      }).showToast();
      let next = base_url('main');

      return RedirectTo(next, {afterTimeout: 3000});
    }

    if (callback.status == false && data.errors !== undefined)
    {      
      Object.entries(data.errors).forEach(([key, value]) => {
        let invalidFeedback = document.querySelector(`.invalid-feedback.${key}`);
        
        if (invalidFeedback !== null)
        {
          invalidFeedback.innerHTML     = stripHtml(value);
          invalidFeedback.style.display = 'inline-block';
        }
      });

      return false;
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
    
    return false;
  });
}