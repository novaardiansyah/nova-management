const contactUsListUrl = base_url('customerService/contactUs/contactUsList');
const wrapAccountList  = document.querySelector('.card-body.contactUsList');

localStorage.setItem(contactUsListUrl, wrapAccountList.innerHTML);
contactUsList();

function contactUsList(url = contactUsListUrl)
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
      
      let contactUsList = document.querySelector('tbody.contactUsList');
      let template    = '';

      Object.entries(data.list).forEach(([key, value]) => {
        template += `
          <tr class="align-middle">
            <td class="align-middle">${parseInt(key) + 1}</td>
            <td class="align-middle">${value.name}</td>
            <td class="align-middle">${value.email}</td>
            <td class="align-middle">${value.phone || '-'}</td>
            <td class="align-middle">${value.message}</td>
            <td class="align-middle">
              <span class="badge ${parseInt(value.isActive) == 1 ? 'bg-success' : 'bg-danger'}">${parseInt(value.isActive) == 1 ? 'Active' : 'Non-Active'}</span>
            </td>
            <td class="align-middle">
              <button type="button" class="btn btn-primary btn-sm text-nowrap" onclick="return editContactUs('editContactUs', '${value.id}')">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-xl-inline">Edit</span>
              </button>

              <button type="button" class="btn btn-info btn-sm text-nowrap d-none">
                <i class="bi bi-eye"></i>
                <span class="d-none d-xl-inline">Detail</span>
              </button>

              <button type="button" class="btn btn-danger btn-sm text-nowrap" onclick="return deleteContactUs('${value.id}')">
                <i class="bi bi-x-square"></i>
                <span class="d-none d-xl-inline">Delete</span>
              </button>
            </td>
          </tr>
        `;
      });

      contactUsList.innerHTML = template;
            
      return initDataTables('contactUsList');
    }

    if (callback.status == false && callback.message !== undefined)
    {
      noDataInTable('contactUsList');
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (DY2GA).', color: 'danger', timer: 5, close: false});
  });
}

function r_contactUsList(params = {}, url = contactUsListUrl)
{
  const { afterTimeout } = params;
  let defaultTable = localStorage.getItem(url);

  if (afterTimeout !== undefined) {
    let delay = (afterTimeout * 1000) - (afterTimeout * 1000 * 0.50);

    setTimeout(() => {
      wrapAccountList.innerHTML = defaultTable;
      contactUsList(url);
    }, delay);

    return true;
  }
  
  wrapAccountList.innerHTML = defaultTable;
  return contactUsList(url);
}

// * Add Contact Us (Start)
function addContactUs(idModal)
{
  const idForm = `form-${idModal}`;

  toggleModal(idModal, 'open');
  loaderModalForm(idModal, 'unload');
  formModalReset(idForm);
}

function storeContactUs(idModal)
{
  const { form, url, method, formData } = setupForm(`form-${idModal}`, 'formData');
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
      toggleModal(idModal, 'close');
      toastifyAlert({message: callback.message, timer: 3, close: true});
      return r_contactUsList({afterTimeout: 3});
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
// * Add Contact Us (End)

// * Edit Contact Us (Start)
function editContactUs(idModal, _id)
{
  const idForm = `form-${idModal}`;
  
  toggleModal(idModal, 'open');
  loaderModalForm(idModal, 'unload');
  formModalReset(idForm);
}
// * Edit Contact Us (End)

// * Delete Contact Us (Start)
function deleteContactUs(_id)
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
        console.log('Delete Contact Us');
      }
    });
}
// * Delete Contact Us (End)
