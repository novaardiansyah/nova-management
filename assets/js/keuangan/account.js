const accountListUrl  = base_url('keuangan/account/accountList');
const wrapAccountList = document.querySelector('.card-body.accountList');

localStorage.setItem(accountListUrl, wrapAccountList.innerHTML);
accountList();

function accountList(url = accountListUrl)
{
  const formData = new FormData();
  formData.append(startup.crlf_name, startup.crlf_token);

  let response = fetch(url, {
    method: 'POST',
    body: formData
  }).then((response) => response.json());

  response.then((callback) => {
    if (callback.status == true && callback.message !== undefined)
    {
      let data = callback.data;
      startup.crlf_token = data.csrf_renewed;
      
      let accountList = document.querySelector('tbody.accountList');
      let template    = '';

      Object.entries(data.list).forEach(([key, value]) => {
        template += `
          <tr class="align-middle">
            <td class="align-middle">${parseInt(key) + 1}</td>
            <td class="align-middle">${value.name}</td>
            <td class="align-middle">${value.short_finance_currency}</td>
            <td class="align-middle">${value.f1_amount}</td>
            <td class="align-middle">${value.name_finance_types}</td>
            <td class="align-middle">
              <span class="badge ${parseInt(value.isActive) == 1 ? 'bg-success' : 'bg-danger'}">${parseInt(value.isActive) == 1 ? 'Active' : 'Non-Active'}</span>
            </td>
            <td class="align-middle">
              <img src="${base_url('assets/images/financeLogo/' + value.logo)}" alt="${value.logo}" class="img-fluid img-thumbnail" style="max-width: 100px;" />
            </td>
            <td class="align-middle">
              <button type="button" class="btn btn-primary btn-sm text-nowrap" onclick="return editAccount('editAccount', '${value.id}')">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-xl-inline">Edit</span>
              </button>

              <button type="button" class="btn btn-info btn-sm text-nowrap d-none">
                <i class="bi bi-eye"></i>
                <span class="d-none d-xl-inline">Detail</span>
              </button>

              <button type="button" class="btn btn-danger btn-sm text-nowrap" onclick="return deleteAccount('${value.id}')">
                <i class="bi bi-x-square"></i>
                <span class="d-none d-xl-inline">Delete</span>
              </button>
            </td>
          </tr>
        `;
      });

      accountList.innerHTML = template;
            
      return initDataTables('accountList');
    }

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (DY2GA).', color: 'danger', timer: 5, close: false});
  });
}

function r_accountList(params = {}, url = accountListUrl)
{
  const { afterTimeout } = params;
  let defaultTable = localStorage.getItem(url);

  if (afterTimeout !== undefined) {
    let delay = (afterTimeout * 1000) - (afterTimeout * 1000 * 0.50);

    setTimeout(() => {
      wrapAccountList.innerHTML = defaultTable;
      accountList(url);
    }, delay);

    return true;
  }
  
  wrapAccountList.innerHTML = defaultTable;
  return accountList(url);
}

// * Add data function (Start) [Nova Ardiansyah - December, 02 2020]
const formAddAccount = document.getElementById('form-addAccount');

formAddAccount.querySelector('[name="idCurrency"]').addEventListener('change', function() {
  let currency = this.value.split(';')[1];

  if (currency == undefined) {
    currency = '$'; 
  }

  return formAddAccount.querySelector('.input-group-text.amount').innerHTML = currency;
});

function addAccount(idModal)
{
  const idForm = `form-${idModal}`;

  toggleModal(idModal, 'open');
  loaderModalForm(idModal, 'unload');
  formModalReset(idForm);

  const togglePreviews = formAddAccount.querySelectorAll('.toggle-preview');
  Object.entries(togglePreviews).forEach(([key, value]) => {
    if (value.classList.contains('d-none') == false) {
      value.classList.add('d-none');
    }
  });

  let idCurrency  = formAddAccount.querySelector(`[name="idCurrency"]`);
  let groupAmount = formAddAccount.querySelector(`.input-group-text.amount`);

  let currency = '$';
  if (idCurrency !== null) {
    currency = idCurrency.value.split(';')[1];
  }

  return groupAmount.innerHTML = currency;
}

function storeAccount(idForm)
{
  const { form, url, method, formData } = setupForm(`form-${idForm}`, 'formData');
  formData.append(startup.crlf_name, startup.crlf_token);

  let response = fetch(url, {
    method: method,
    body: formData
  }).then((response) => response.json());

  response.then((callback) => {
    let data = callback.data;

    startup.crlf_token = data.csrf_renewed;

    if (callback.status == true && callback.message !== undefined)
    {
      toggleModal('addAccount', 'close');
      toastifyAlert({message: callback.message, timer: 3, close: true});
      return r_accountList({afterTimeout: 3});
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

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 3, close: true});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan, silahkan muat ulang halaman ini (WC2CE).', color: 'danger', timer: 3, close: true});
  });
}
// * Add data function (End)

// * Edit data function (Start)
const formEditAccount = document.getElementById('form-editAccount');

formEditAccount.querySelector('[name="idCurrency"]').addEventListener('change', function() {
  let currency = this.value.split(';')[1];

  if (currency == undefined) {
    currency = '$'; 
  }

  return formEditAccount.querySelector('.input-group-text.amount').innerHTML = currency;
});

function editAccount(idModal, _id)
{
  const idForm = `form-${idModal}`;
  
  toggleModal(idModal, 'open');
  loaderModalForm(idModal, 'unload');
  formModalReset(idForm);

  const formData = new FormData();
  formData.append(startup.crlf_name, startup.crlf_token);
  formData.append('_id', _id);

  let response = fetch(`${base_url('keuangan/account/editAccount')}`, {
    method: 'POST',
    body: formData
  }).then((response) => response.json());

  response.then((callback) => {
    if (callback.status == true && callback.message !== undefined)
    {
      let data = callback.data;
      
      startup.crlf_token = data.csrf_renewed;

      formEditAccount.querySelector('[name="name"]').value          = data.name;
      formEditAccount.querySelector('[name="idCurrency"]').value    = data.idCurrency + ';' + data.short_finance_currency;
      formEditAccount.querySelector('[name="idType"]').value        = data.idType;
      formEditAccount.querySelector('[name="amount"]').value        = data.amount;
      formEditAccount.querySelector('.toggle-preview.logo img').src = base_url('assets/images/financeLogo/' + data.logo);
      
      const status = formEditAccount.querySelectorAll('[name="isActive"]');
      Object.entries(status).forEach(([key, value]) => {
        value.checked = false;
        if (value.value == data.isActive) {
          value.checked = true;
        }
      });

      let idCurrency  = formEditAccount.querySelector(`[name="idCurrency"]`);
      let groupAmount = formEditAccount.querySelector(`.input-group-text.amount`);
      
      let currency = '$';
      if (idCurrency !== null) {
        currency = idCurrency.value.split(';')[1];
      }
      
      groupAmount.innerHTML = currency;

      const modalFooter = document.querySelector(`.editAccount.modal-footer`);
      modalFooter.innerHTML = `<button type="button" class="btn btn-sm btn-secondary" onclick="return toggleModal('editAccount', 'close')">Close</button>  
        <button type="button" class="btn btn-sm btn-primary" onclick="return updateAccount('editAccount', '${data.id}')">Save</button>`;

      return true;
    }

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }

    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (TDX7M).', color: 'danger', timer: 5, close: false});
  });
}

function updateAccount(idModal, _id)
{
  const { form, url, method, formData } = setupForm(`form-${idModal}`, 'formData');
  formData.append(startup.crlf_name, startup.crlf_token);
  formData.append('_id', _id);

  let response = fetch(url, {
    method: method,
    body: formData
  }).then((response) => response.json());

  response.then((callback) => {
    let data = callback.data;
    
    startup.crlf_token = data.csrf_renewed;

    if (callback.status == true && callback.message !== undefined)
    {
      toggleModal('editAccount', 'close');
      toastifyAlert({message: callback.message, timer: 3, close: true});
      return r_accountList({afterTimeout: 3});
    }

    if (callback.status == false && data.errors !== undefined)
    {      
      Object.entries(data.errors).forEach(([key, value]) => {
        let invalidFeedback = form.querySelector(`.invalid-feedback.${key}`);
        
        invalidFeedback.innerHTML     = stripHtml(value);
        invalidFeedback.style.display = 'inline-block';
      });

      return false;
    }

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 3, close: true});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan, silahkan muat ulang halaman ini (CCXGY).', color: 'danger', timer: 3, close: true});
  });
}
// * Edit data function (End)

// * Delete data function (Start)
function deleteAccount(_id)
{
  sweetAlertConfirmDanger.fire({
    text: 'Apakah anda yakin?',
    icon: 'warning',
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonText: 'Lanjutkan',
    cancelButtonText: 'Batal'
  })
    .then((confirm) => {
      if (confirm.isConfirmed == true)
      {
        const formData = new FormData();
        formData.append(startup.crlf_name, startup.crlf_token);
        formData.append('_id', _id);

        let url = base_url('keuangan/account/deleteAccount');

        let response = fetch(url, {
          method: 'POST',
          body: formData
        }).then((response) => response.json());
        
        response.then((callback) => {
          let data = callback.data;
          console.log(callback);

          startup.crlf_token = data.csrf_renewed;

          if (callback.status == true && callback.message !== undefined)
          {
            toastifyAlert({message: callback.message, timer: 3, close: true});
            return r_accountList({afterTimeout: 3});
          }

          if (callback.status == false && callback.message !== undefined) 
          {
            return toastifyAlert({message: callback.message, color: 'danger', timer: 3, close: true});
          }

          return toastifyAlert({message: 'Terjadi kesalahan, silahkan muat ulang halaman ini (4IIT2).', color: 'danger', timer: 3, close: true});
        });
      }
    })
}
// * Delete data function (End)