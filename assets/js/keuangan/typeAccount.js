const typeAccountListUrl = base_url('keuangan/account/accountList');
const wrap_typeAccount   = document.querySelector('.card-body.typeAccountList');

localStorage.setItem(typeAccountListUrl, wrap_typeAccount.innerHTML);
// typeAccountList();

function typeAccountList(url = typeAccountListUrl)
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
      
      let typeAccountList = document.querySelector('tbody.typeAccountList');
      let template        = '';

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
              <img src="${base_url('assets/images/financeLogo/' + value.logo)}" alt="${value.logo}" class="img-fluid" style="max-width: 100px;" />
            </td>
            <td class="align-middle">
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

      typeAccountList.innerHTML = template;
            
      return initDataTables('typeAccountList');
    }

    if (callback.status == false && callback.message !== undefined)
    {
      return toastifyAlert({message: callback.message, color: 'danger', timer: 5});
    }
    
    return toastifyAlert({message: 'Terjadi kesalahan internal, silahkan coba lagi (MGABF).', color: 'danger', timer: 5, close: false});;
  });
}