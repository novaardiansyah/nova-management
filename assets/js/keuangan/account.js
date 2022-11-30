const defaultUrl      = base_url('keuangan/account/accountList');
const wrapperMenuList = document.querySelector('.card-body.accountList');

localStorage.setItem(defaultUrl, wrapperMenuList.innerHTML);
accountList();

function accountList(url = defaultUrl)
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
          <tr>
            <td>${parseInt(key) + 1}</td>
            <td>${value.name}</td>
            <td>${value.idCurrency}</td>
            <td>${value.amount}</td>
            <td>
              <img src="${base_url('assets/images/financeLogo/' + value.logo)}" alt="${value.logo}" class="img-fluid" style="max-width: 100px;" />
            </td>
            <td>${value.name_finance_types}</td>
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

      accountList.innerHTML = template;
            
      return initDataTables('accountList');
    }

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (DY2GA).', color: 'danger', timer: 5, close: false});;
  });
}

function toggleTabs(event, section)
{
  event.preventDefault();
  let listTabs = document.querySelectorAll('.nav-link');
  
  listTabs.forEach((element) => {
    element.classList.remove('active');
  });

  event.target.classList.add('active');
}