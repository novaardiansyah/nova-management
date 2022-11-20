let autohideInvalid = document.querySelectorAll('.autohide-invalid');
[...autohideInvalid].forEach((element) => {
  element.addEventListener('click', function(e) {
    let name = e.target.getAttribute('name');
    let invalidFeedback = document.querySelector(`.invalid-feedback.${name}`);
    invalidFeedback.style.display = 'none';
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
  const table       = document.getElementById(idTable);
  const loaderTable = document.getElementById('loader-table');

  let dataTable = new simpleDatatables.DataTable(table);
  dataTable.on("datatable.page", adaptPagination);

  adaptPageDropdown(dataTable);
  adaptPagination(dataTable);

  loaderTable.style.visibility = 'hidden';
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

function formModalReset()
{
  let invalidFeedback = document.querySelectorAll('.invalid-feedback');
  [...invalidFeedback].forEach((element) => {
    element.style.display = 'none';
  });
}