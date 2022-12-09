const defaultUrl      = base_url('masterData/menu/menuList');
const wrapperMenuList = document.querySelector('.card-body.menuList');

localStorage.setItem(defaultUrl, wrapperMenuList.innerHTML);
menuList();

function menuList(url = defaultUrl)
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
      
      let menuList = document.querySelector('tbody.menuList');
      let template = '';

      Object.entries(data.menu).forEach(([key, value]) => {
        template += `
          <tr>
            <td>${parseInt(key) + 1}</td>
            <td>${value.name}</td>
            <td>${value.icon}</td>
            <td>${value.link}</td>
            <td>${value.sortOrder}</td>
            <td>
              <span class="badge ${parseInt(value.isActive) == 1 ? 'bg-success' : 'bg-danger'}">${parseInt(value.isActive) == 1 ? 'Active' : 'Non-Active'}</span>
            </td>
            <td>
              <button type="button" class="btn btn-primary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#editData" id="editData-show" onclick="return editData('${value.id}')">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-xl-inline">Edit</span>
              </button>

              <button type="button" class="btn btn-info btn-sm text-nowrap d-none">
                <i class="bi bi-eye"></i>
                <span class="d-none d-xl-inline">Detail</span>
              </button>

              <button type="button" class="btn btn-danger btn-sm text-nowrap" onclick="return deleteData('${value.id}')">
                <i class="bi bi-x-square"></i>
                <span class="d-none d-xl-inline">Delete</span>
              </button>
            </td>
          </tr>
        `;
      });

      menuList.innerHTML = template;
            
      return initDataTables('menuList');
    }

    if (callback.status == false && callback.message !== undefined)
    {
      noDataInTable('menuList');
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (DY2GA).', color: 'danger', timer: 5, close: false});
  });
}

function refreshList(url = defaultUrl, params = {})
{
  const { afterTimeout } = params;
  let defaultTable = localStorage.getItem(defaultUrl);

  if (afterTimeout !== undefined) {
    let delay = afterTimeout - (afterTimeout * 0.50);

    setTimeout(() => {
      wrapperMenuList.innerHTML = defaultTable;
      menuList(url);
    }, delay);

    return true;
  }
  
  wrapperMenuList.innerHTML = defaultTable;
  return menuList(url);
}

function addData()
{
  const { form, url, method, formData } = setupForm('form-addData', 'formData');
  
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
      toggleModal('addData', 'hide');

      Toastify({
        text: stripHtml(callback.message),
        duration: 3000,
        close: true,
        style: {
          background: startup.colors.info,
        }
      }).showToast();

      return refreshList();
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
      Toastify({
        text: stripHtml(callback.message),
        duration: 5000,
        close: true,
        style: {
          background: startup.colors.danger,
        }
      }).showToast();

      return refreshList();
    }
    
    Toastify({
      text: 'Terjadi Kesalahan Internal (P9KQDL).',
      duration: 5000,
      close: true,
      style: {
        background: startup.colors.danger,
      }
    }).showToast();

    return refreshList();
  })
    .catch((error) => {
      Toastify({
        text: 'Terjadi Kesalahan Internal (JH1P29).',
        duration: 5000,
        close: true,
        style: {
          background: startup.colors.danger,
        }
      }).showToast();
      console.log(error);

      return refreshList();
    });
}

function deleteData(_id)
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

        let url = base_url('masterData/menu/deleteData');

        let response = fetch(url, {
          method: 'POST',
          body: formData
        }).then((response) => response.json());
        
        response.then((callback) => {
          let data = callback.data;

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

            return refreshList();
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
            Toastify({
              text: stripHtml(callback.message),
              duration: 5000,
              close: true,
              style: {
                background: startup.colors.danger,
              }
            }).showToast();

            return refreshList();
          }
          
          Toastify({
            text: 'Terjadi Kesalahan Internal (0I0ZG).',
            duration: 5000,
            close: true,
            style: {
              background: startup.colors.danger,
            }
          }).showToast();

          return refreshList();
        })
          .catch((error) => {
            Toastify({
              text: 'Terjadi Kesalahan Internal (PZJFW).',
              duration: 5000,
              close: true,
              style: {
                background: startup.colors.danger,
              }
            }).showToast();
            console.log(error);

            return refreshList();
          });
      }
    })
}

function editData(_id)
{
  formModalReset();
  loaderModalForm('editData', 'load');

  const formData = new FormData();
  formData.append(startup.crlf_name, startup.crlf_token);
  formData.append('_id', _id);

  let url = base_url('masterData/menu/editData');

  let response = fetch(url, {
    method: 'POST',
    body: formData
  }).then((response) => response.json());
  
  response.then((callback) => {
    let data = callback.data;
    
    startup.crlf_token = data.csrf_renewed;

    if (callback.status == true && callback.message !== undefined)
    {
      Object.entries(data).forEach(([key, value]) => {
        let fieldRadio = document.querySelectorAll(`input[name="${key}"][type="radio"]`);
        
        fieldRadio.forEach((value1) => {
          if (value1.value == value) return value1.checked = true;
          value1.checked = false;
        });

        let field = document.querySelector(`#editData [name="${key}"]`);
        
        if (field !== null)
        {
          if (key == 'icon') value = value.replace(value.substr(0, 6), '');
          if (key == 'link') value = value.replace(value.substr(0, 1), '');

          if (field.type !== 'radio' && field.type !== 'checkbox') return field.value = value;
        }
      });

      return loaderModalForm('editData', 'unload');
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
      toggleModal('editData', 'hide');

      return false;
    }

    return false;
  });
}

function updateData()
{
  const { form, url, method, formData } = setupForm('form-editData', 'formData');
  
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
      toggleModal('editData', 'hide');

      Toastify({
        text: stripHtml(callback.message),
        duration: 3000,
        close: true,
        style: {
          background: startup.colors.info,
        }
      }).showToast();

      return refreshList(defaultUrl, { afterTimeout: 3000 });
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

function loaderModalForm(idForm, type = 'load')
{
  const loaderForm = document.querySelector(`.loader.form-${idForm}`);
  const form       = document.querySelector(`#form-${idForm}`);

  if (type == 'unload')
  {
    loaderForm.style.display = 'none';
    loaderForm.classList.remove('d-flex');

    form.style.display = 'block';
    return true;
  }

  loaderForm.classList.add('d-flex');
  form.style.display = 'none';
}

function toggleModal(selector, type = 'show')
{  
  if (type == 'hide') {
    return document.querySelector(`#${selector}-close`).click();
  }

  return document.querySelector(`#${selector}-show`).click();
}