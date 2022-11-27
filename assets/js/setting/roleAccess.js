listRoleAccount();

function listRoleAccount()
{
  const formData = new FormData();
  formData.append(startup.crlf_name, startup.crlf_token);

  let response = fetch(`${base_url('setting/roleAccess/listRoleAccount')}`, {
    method: 'POST',
    body: formData
  }).then((response) => response.json());

  response.then((callback) => {
    console.log(callback);
    if (callback.status == true && callback.message !== undefined)
    {
      let data = callback.data || [];
      let list = data.list || [];

      startup.crlf_token = data?.csrf_renewed;
      
      let listRoleAccount = document.querySelector('.list-group.listRoleAccount');
      listRoleAccount.innerHTML = '';

      let template = '';
      Object.entries(list).forEach(([key, value]) => {
        template += `
          <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            ${value.name}
            <span class="badge bg-info rounded-pill">1</span>
          </a>
        `;
      });

      listRoleAccount.innerHTML = template;
      toggleLoader('loader-1');
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