document.addEventListener("DOMContentLoaded", function() { 
}, false);

const defaultUrl = base_url('masterData/menu/menuList');
menuList(defaultUrl);

function menuList(url)
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
            <td>
              <span class="badge ${parseInt(value.isActive) == 1 ? 'bg-success' : 'bg-danger'}">${parseInt(value.isActive) == 1 ? 'Active' : 'Non-Active'}</span>
            </td>
            <td>
              <button type="button" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-xl-inline">Edit</span>
              </button>

              <button type="button" class="btn btn-info btn-sm text-nowrap">
                <i class="bi bi-eye"></i>
                <span class="d-none d-xl-inline">Detail</span>
              </button>

              <button type="button" class="btn btn-danger btn-sm text-nowrap">
                <i class="bi bi-x-square"></i>
                <span class="d-none d-xl-inline">Delete</span>
              </button>
            </td>
          </tr>
        `;
      });

      menuList.innerHTML = template;
      initDataTables('menuList');
      
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