function denied_specialchar(string) {
  let forbiden_char = [`'`, '"', '<', '>', '+', '=', '%', ';'];

  forbiden_char.forEach((char) => {
    if (string.includes(char)) {
      string = string.replace(new RegExp(char, 'g'), '');
    }
  });

  return string;
}

function denied_specialchar_value(event) 
{
  let key = event.keyCode;
  let forbiden_char = ["'", '"', '<', '>', '+', '=', '%', ';'];

  if (forbiden_char.includes(String.fromCharCode(key))) {
    event.preventDefault();
  }
}

function stripHtml(html) {
  let tmp = document.createElement("div");
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || "";
}

function ReloadPage() {
  location.reload();
}

function RedirectTo(url) {
  window.location.href = url;
}

function base_url(path = '')
{
  return startup.baseurl + path;
}

function setupForm(formId, type = 'serialize') {
  let form   = document.getElementById(formId);
  let url    = form.getAttribute('action');
  let method = form.getAttribute('method');

  let formData = {};
  if (type == 'serialize') {
    formData = form.serialize();
  } else if (type == 'formData') {
    formData = new FormData(form);
  }

  return { form, url, method, formData };
}
