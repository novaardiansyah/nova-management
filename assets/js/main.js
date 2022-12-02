let autohideInvalid = document.querySelectorAll('.autohide-invalid');
[...autohideInvalid].forEach((element) => {
  element.addEventListener('focus', function(e) {
    let name = e.target.getAttribute('name');
    let invalidFeedback = document.querySelector(`.invalid-feedback.${name}`);
    if (invalidFeedback !== null) invalidFeedback.style.display = 'none';
  });
});

function adaptPageDropdown(dataTable) {
  const selector = dataTable.wrapper.querySelector(".dataTable-selector")
  selector.parentNode.parentNode.insertBefore(selector, selector.parentNode)
  selector.classList.add("form-select")
}

function adaptPagination(dataTable) {
  const paginations = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list"
  )

  for (const pagination of paginations) {
    pagination.classList.add(...["pagination", "pagination-primary"])
  }

  const paginationLis = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list li"
  )

  for (const paginationLi of paginationLis) {
    paginationLi.classList.add("page-item")
  }

  const paginationLinks = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list li a"
  )

  for (const paginationLink of paginationLinks) {
    paginationLink.classList.add("page-link")
  }
}

function initDataTables(idTable)
{
  const table = document.getElementById(idTable);
  let loaderTable = document.getElementById('loader-table');

  if (loaderTable == null) {
    loaderTable = document.querySelector(`.loader-table.${idTable}`);
  }

  let dataTable = new simpleDatatables.DataTable(table);
  dataTable.on("datatable.page", adaptPagination);

  adaptPageDropdown(dataTable);
  adaptPagination(dataTable);

  loaderTable.style.display = 'none';
  loaderTable.classList.remove('d-flex');
}

function toggleLoader(idLoader, action = 'hide')
{
  const loader = document.getElementById(idLoader);

  if (action == 'hide')
  {
    loader.style.display = 'none';
    loader.classList.remove('d-flex');
  }
}

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

function ReloadPage(params = {}) {
  if (params.after !== undefined) 
  {
    setTimeout(() => {
      location.reload();
    }, params.after);

    return true;  
  }
  
  location.reload();
}

function RedirectTo(url, params = {}) {
  if (params.afterTimeout !== undefined) 
  {
    setTimeout(() => {
      window.location.href = url;
    }, params.afterTimeout);

    return true;  
  }

  return window.location.href = url;
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

function formModalReset(idForm = null)
{
  if (idForm !== null) {
    const form = document.getElementById(idForm);

    // * Hide all invalid feedback
    let invalidFeedback = document.querySelectorAll(`#${idForm} .invalid-feedback`);
    [...invalidFeedback].forEach((element) => {
      element.style.display = 'none';
    });

    return form.reset();
  }
  
  let invalidFeedback = document.querySelectorAll('.invalid-feedback');
  [...invalidFeedback].forEach((element) => {
    element.style.display = 'none';
  });
}

function loaderModalForm(idForm, type = 'unload')
{
  const loaderForm = document.querySelector(`.loader.form-${idForm}`);
  const form       = document.querySelector(`#form-${idForm}`);
  
  loaderForm.classList.remove('d-none');
  form.classList.add('d-none');

  if (type == 'unload') {
    setTimeout(() => {
      loaderForm.classList.add('d-none');
      form.classList.remove('d-none');
    }, 1000);
  }
}

function toggleModal(idModal, type = 'open')
{
  const wrapperToggleModal = document.getElementById('wrapper-toggle-modal');
  let button = '';

  if (type == 'open') {
    button = `<button type="button" class="btn btn-success btn-sm d-none" data-bs-toggle="modal" data-bs-target="#${idModal}">${type}</button>`;
  }

  if (type == 'close') {
    button = `<button type="button" class="btn btn-danger btn-sm d-none" data-bs-dismiss="modal" data-bs-target="#${idModal}">${type}</button>`;
  }

  wrapperToggleModal.innerHTML = button;
  let buttonToggle = document.querySelector(`button[data-bs-target="#${idModal}"]`);
  return buttonToggle.click();
}

const sweetAlertConfirmDanger = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-sm btn-danger ms-2',
    cancelButton: 'btn btn-sm btn-secondary'
  },
  buttonsStyling: false
});

function customSweetalert(text, icon, timer, timerProgressBar, willClose, type = 'normal') {
  if (type == 'loading') {
    Swal.fire({
      text: text,
      icon: icon,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      onBeforeOpen: () => {
        Swal.showLoading();
      }
    });

    return true;
  }

  if (timerProgressBar == true)
  {
    Swal.fire({
      text: stripHtml(text),
      icon: icon,
      showConfirmButton: false,
      timer: timer,
      timerProgressBar: timerProgressBar,
      willClose: willClose
    });
  } else {
    Swal.fire({
      text: stripHtml(text),
      icon: icon,
      showConfirmButton: false,
      willClose: willClose
    });
  }
}

function toastifyAlert(params = {})
{
  const { message, color, timer, close } = params;

  let style = {
    background: startup.colors.info
  };

  if (color == 'danger')
  {
    style = {
      background: startup.colors.danger
    };
  }

  Toastify({
    text: stripHtml(message),
    duration: timer ? timer * 1000 : 3000,
    close: close == undefined ? true : close,
    style: style
  }).showToast();
}