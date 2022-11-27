const defaultUrl      = base_url('setting/auditlog/auditlogList');
const wrapperMenuList = document.querySelector('.card-body.auditlogList');

localStorage.setItem(defaultUrl, wrapperMenuList.innerHTML);
auditlogList();

function auditlogList(url = defaultUrl)
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
      
      let auditlogList = document.querySelector('tbody.auditlogList');
      let template = '';

      Object.entries(data.list).forEach(([key, value]) => {
        template += `
          <tr>
            <td>${parseInt(key) + 1}</td>
            <td class="text-nowrap">${value.username}</td>
            <td class="text-center">${value.idUser}</td>
            <td class="text-nowrap">${value.name_role}</td>
            <td class="text-nowrap">${value.ipAddress}</td>
            <td>${value.name_auditlogs_types}</td>
            <td>${value.description}</td>
            <td class="text-nowrap">${value.f1_created_at}</td>
          </tr>
        `;
      });

      auditlogList.innerHTML = template;
            
      return initDataTables('auditlogList');
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

function refreshList(url = defaultUrl, params = {})
{
  const { afterTimeout } = params;
  let defaultTable = localStorage.getItem(defaultUrl);

  if (afterTimeout !== undefined) {
    let delay = afterTimeout - (afterTimeout * 0.50);

    setTimeout(() => {
      wrapperMenuList.innerHTML = defaultTable;
      auditlogList(url);
    }, delay);

    return true;
  }
  
  wrapperMenuList.innerHTML = defaultTable;
  return auditlogList(url);
}