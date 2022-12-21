function login()
{
  const { form, url, method, formData } = setupForm('form-login', 'formData');
  loaderPage('show');
  
  formData.append(startup.crlf_name, startup.crlf_token);
  
  let response = fetch(url, {
    method: method,
    body: formData
  }).then((response) => response.json());
  
  response.then((callback) => {
    loaderPage('hide');

    let data = callback.data;
    startup.crlf_token = data.csrf_renewed;

    if (callback.status == true && callback.message !== undefined)
    {
      toastifyAlert({ message: callback.message, color: 'info', timer: 3, close: false });

      let next = base_url('main');
      return RedirectTo(next, {afterTimeout: 3});
    }

    if (callback.status == false && data.errors !== undefined)
    {      
      Object.entries(data.errors).forEach(([key, value]) => {
        let invalidFeedback = document.querySelector(`.invalid-feedback.${key}`);
        
        invalidFeedback.innerHTML     = stripHtml(value);
        invalidFeedback.style.display = 'inline-block';
      });

      return false;
    }

    if (callback.status == false && callback.message !== undefined) return toastifyAlert({ message: callback.message, color: 'danger', timer: 5, close: false });
    
    console.log('debug-callback:', callback);
    return toastifyAlert({ message: 'Something went wrong!', color: 'danger', timer: 5, close: false });
  });
}